<?php

namespace App;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Helpers\EmailTemplateConstants;
use App\Models\UserBadge;
use Bavix\Wallet\Interfaces\Wallet;
use Chatify\Http\Models\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Modules\Booking\Models\Booking;
use Modules\Review\Models\Review;
use Modules\User\Emails\EmailUserVerifyRegister;
use Modules\User\Emails\ResetPasswordToken;
use Modules\User\Emails\EmailUserWelcome;
use Modules\User\Emails\EmailUserRegistered;
use Modules\User\Emails\EmailNewUserAdmin;
//    use Modules\Vendor\Models\VendorPlan;
use Modules\Vendor\Models\VendorPayout;
use Modules\Vendor\Models\VendorRequest;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\HasWallet;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;
use App\Models\UserTransaction;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class User extends Authenticatable implements MustVerifyEmail, JWTSubject, Wallet
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use HasWallet;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'address',
        'address2',
        'phone',
        'birthday',
        'city',
        'state',
        'country',
        'zip_code',
        'last_login_at',
        'avatar_id',
        'bio',
        'business_name',
        'map_lat',
        'map_lng',
        'map_zoom',
        'instagram_link',
        'facebook_link',
        'site_link'
        //            'vendor_plan_id',
        //            'vendor_plan_enable',
        //            'vendor_plan_start_date',
        //            'vendor_plan_end_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getMeta($key, $default = '')
    {
        //if(isset($this->cachedMeta[$key])) return $this->cachedMeta[$key];

        $val = DB::table('user_meta')->where([
            'user_id' => $this->id,
            'name' => $key
        ])->first();

        if (!empty($val)) {
            //$this->cachedMeta[$key]  = $val->val;
            return $val->val;
        }

        return $default;
    }

    public function addMeta($key, $val, $multiple = false)
    {
        if (is_array($val) or is_object($val))
            $val = json_encode($val);
        if ($multiple) {
            return DB::table('user_meta')->insert([
                'name' => $key,
                'val' => $val,
                'user_id' => $this->id,
                'create_user' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $old = DB::table('user_meta')->where([
                'user_id' => $this->id,
                'name' => $key
            ])->first();

            if ($old) {
                return DB::table('user_meta')->where('id', $old->id)->update([
                    'val' => $val,
                    'update_user' => Auth::id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                return DB::table('user_meta')->insert([
                    'name' => $key,
                    'val' => $val,
                    'user_id' => $this->id,
                    'create_user' => Auth::id(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public function updateMeta($key, $val)
    {

        return DB::table('user_meta')->where('user_id', $this->id)
            ->where('name', $key)
            ->update([
                'val' => $val,
                'update_user' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function batchInsertMeta($metaArrs = [])
    {
        if (!empty($metaArrs)) {
            foreach ($metaArrs as $key => $val) {
                $this->addMeta($key, $val, true);
            }
        }
    }

    public function getNameOrEmailAttribute()
    {
        if ($this->first_name)
            return $this->first_name;

        return $this->email;
    }


    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case "publish":
                return __("Publish");
                break;
            case "blocked":
                return __("Blocked");
                break;
        }
    }

    public static function getUserBySocialId($provider, $socialId)
    {
        return parent::query()->select('users.*')->join('user_meta as m', 'm.user_id', 'users.id')
            ->where('m.name', 'social_' . $provider . '_id')
            ->where('m.val', $socialId)->first();
    }

    public function getAvatarUrl()
    {
        if (!empty($this->avatar_id)) {
            return get_file_url($this->avatar_id, 'thumb');
        }
        if (!empty($meta_avatar = $this->getMeta("social_meta_avatar", false))) {
            return $meta_avatar;
        }
        if ($this->avatar === "avatar.png") {
            $this->avatar = null;
        }
        if ($this->avatar == null) {
            $randomAvatarKey = rand(1, 24);
            $this->avatar = CodeHelper::withAppUrl("images/avatars/avatar-" . $randomAvatarKey . "-min.png");
            $this->save();
        }
        return $this->avatar;
        // return asset('images/avatar.png');
    }

    public function getPublicName()
    {
        return $this->name;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getAvatarUrl();
    }

    public function getDisplayName($email = false)
    {
        $name = $this->name ?? "";
        if (!empty($this->first_name) or !empty($this->last_name)) {
            $name = implode(' ', [$this->first_name, $this->last_name]);
        }
        if (!empty($this->business_name)) {
            $name = $this->business_name;
        }
        if (!trim($name) and $email)
            $name = $this->email;
        if (empty($name)) {
            $name = ' ';
        }
        return $name;
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->name;
        if (!empty($this->first_name) or !empty($this->last_name)) {
            $name = implode(' ', [$this->first_name, $this->last_name]);
        }
        if (!empty($this->business_name)) {
            $name = $this->business_name;
        }
        return $name;
    }

    public function sendPasswordResetNotification($token)
    {
        $EmailSubject = EmailSubject::where('token', 'MYOFFICE___USER__FORGOT_PASSWORD')->first(); 
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        Mail::to($this->email)->send(new ResetPasswordToken($token, $this, $EmailSubject['subject'], $EmailTemplate));
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($table) {
            $table->name = implode(' ', [$table->first_name, $table->last_name]);
        });
    }

    //        public function vendorPlan()
    //        {
    //            return $this->belongsTo(VendorPlan::class, 'vendor_plan_id');
    //        }
    //
    //        public function getVendorPlanDataAttribute()
    //        {
    //            $data = [];
    //            if ($this->hasRole('vendor')) {
    //                $plan = $this->vendorPlan()->first();
    //                if (!empty($plan)) {
    //                    $enable = !empty($this->vendor_plan_enable) ? true : false;
    //                    if ($plan->status == 'publish') {
    //                        $enable = true;
    //                    } else {
    //                        $enable = false;
    //                    }
    //                    $data['vendor_plan_enable'] = $enable;
    //                    $data['base_commission'] = $plan->base_commission;
    //                    $planMeta = $plan->meta;
    //                    foreach ($planMeta as $meta){
    //                        $data[$meta->post_type] = $meta->toArray();
    //                    }
    //                }
    //            }
    //            return $data;
    //        }

    public function getVendorServicesQuery($moduleClass, $limit = 10)
    {
        return $moduleClass::getVendorServicesQuery()->take($limit);
    }

    // public function getReviewCountAttribute()
    // {
    //     return Review::query()->where('vendor_id', $this->id)->where('status', 'approved')->count('id');
    // }
    public function vendorRequest()
    {
        return $this->hasOne(VendorRequest::class);
    }

    public function getPayoutAccountsAttribute()
    {
        return json_decode($this->getMeta('vendor_payout_accounts'));
    }

    /**
     * Get total available amount for payout at current time
     */
    public function getAvailablePayoutAmountAttribute()
    {
        $status = setting_item_array('vendor_payout_booking_status');
        if (empty($status))
            return 0;

        $query = Booking::query();

        $total = $query
            ->whereIn('status', $status)
            ->where('vendor_id', $this->id)
            ->sum(DB::raw('total_before_fees - commission + vendor_service_fee_amount')) - $this->total_paid;
        return max(0, $total);
    }

    public function getTotalPaidAttribute()
    {
        return VendorPayout::query()->where('status', '!=', 'rejected')->where([
            'vendor_id' => $this->id
        ])->sum('amount');
    }

    public function getAvailablePayoutMethodsAttribute()
    {
        $vendor_payout_methods = json_decode(setting_item('vendor_payout_methods'));
        if (!is_array($vendor_payout_methods))
            $vendor_payout_methods = [];

        $vendor_payout_methods = array_values(\Illuminate\Support\Arr::sort($vendor_payout_methods, function ($value) {
            return $value->order ?? 0;
        }));

        $res = [];

        $accounts = $this->payout_accounts;

        if (!empty($vendor_payout_methods) and !empty($accounts)) {
            foreach ($vendor_payout_methods as $vendor_payout_method) {
                $id = $vendor_payout_method->id;

                if (!empty($accounts->$id)) {
                    $vendor_payout_method->user = $accounts->$id;
                    $res[$id] = $vendor_payout_method;
                }
            }
        }

        return $res;
    }

    public function formattedAddress()
    {
        $address2 = $this->address2;
        $address = $this->address;
        $city = $this->city;
        $state = $this->state;
        $country = $this->country;
        $zip_code = $this->zip_code;

        $formattedAddress = "";
        if (trim($address2) != "") {
            $formattedAddress .= $address2;
        }
        if (trim($address) != "") {
            $formattedAddress .= "-" . $address;
        }
        if (trim($city) != "") {
            $formattedAddress .= "</br>" . $city;
        }
        if (trim($state) != "") {
            $formattedAddress .= ", " . $state;
        }
        if (trim($country) != "") {
            $formattedAddress .= ", " . $country;
        }
        if (trim($zip_code) != "") {
            $formattedAddress .= "</br>" . $zip_code;
        }
        return $formattedAddress;
    }

    public function getRoleNameAttribute()
    {
        $all = $this->getRoleNames();

        if (count($all)) {
            return ucfirst($all[0]);
        }
        return '';
    }

    public function getRoleIdAttribute()
    {
        return $this->roles[0]->id ?? '';
    }

    /**
     * @todo get All Fields That you need to verification
     * @return array
     */
    public function getVerificationFieldsAttribute()
    {

        $all = get_all_verify_fields();
        $role_id = $this->role_id;
        $res = [];
        foreach ($all as $id => $field) {
            if (!empty($field['roles']) and is_array($field['roles']) and in_array($role_id, $field['roles'])) {
                $field['id'] = $id;
                $field['field_id'] = 'verify_data_' . $id;
                $field['is_verified'] = $this->isVerifiedField($id);
                $field['data'] = old('verify_data_' . $id, $this->getVerifyData($id));

                switch ($field['type']) {
                    case "multi_files":
                        $field['data'] = json_decode($field['data'], true);
                        if (!empty($field['data'])) {
                            foreach ($field['data'] as $k => $v) {
                                if (!is_array($v)) {
                                    $field['data'][$k] = json_decode($v, true);
                                }
                            }
                        }
                        break;
                }
                $res[$id] = $field;
            }
        }

        return \Illuminate\Support\Arr::sort($res, function ($value) {
            return $value['order'] ?? 0;
        });
    }

    public function isVerifiedField($field_id)
    {
        return (bool) $this->getMeta('is_verified_' . $field_id);
    }
    public function getVerifyData($field_id)
    {
        return $this->getMeta('verify_data_' . $field_id);
    }

    public static function countVerifyRequest()
    {
        return parent::query()->whereIn('verify_submit_status', ['new', 'partial'])->count(['id']);
    }

    public static function countUpgradeRequest()
    {
        return parent::query()->whereIn('verify_submit_status', ['new', 'partial'])->count(['id']);
    }

    public function sendEmailUserVerificationNotification($register_as)
    {
        $actionUrl = $this->verificationUrl($this);
        if($register_as=='host'){
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___HOST__ACTIVATION_EMAIL')->first();
        } else {
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___USER__ACTIVATION_EMAIL')->first();
        }
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        $EmailSubject = setting_item('subject_email_verify_register_user');
        $a = Mail::to($this->email)->send(new EmailUserVerifyRegister($this, $EmailSubject, $EmailTemplate, $actionUrl));
    }

    public function sendEmailWelcomeNotification($register_as)
    {
        if($register_as=='host'){
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___HOST__WELCOME_EMAIL')->first();
        } else {
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___USER__WELCOME_EMAIL')->first();
        }
       
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        Mail::to($this->email)->send(new EmailUserWelcome($this, $EmailSubject, $EmailTemplate));
    }

    public function sendEmailRegisteredNotification($register_as)
    {
        $actionUrl = $this->verificationUrl($this);
        if($register_as=='host'){
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE__HOST__SIGNUP')->first();
        } else {
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE__USER__SIGNUP')->first();
        }
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        Mail::to($this->email)->send(new EmailUserRegistered($this, $EmailSubject, $EmailTemplate, $actionUrl));
    }

    public function sendEmailRegisteredAdminNotification($register_as)
    {
        if($register_as=='host'){
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___ADMIN__HOST_SIGNUP')->first();
        } else {
            $EmailSubject = EmailSubject::where('token', 'MYOFFICE___ADMIN__USER_SIGNUP')->first();
        }
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        $AdminUsers =  User::whereHas('roles', function($q){$q->whereIn('name', ['administrator']);})->get();

        foreach($AdminUsers as $admin)
        {

           Mail::to($admin->email)->send(new EmailNewUserAdmin($admin, $EmailSubject, $EmailTemplate));
            
        }
    }


    public function verificationUrl($notifiable)
    {
       
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function creditPaymentUpdate($payment)
    {
        
        if ($payment->status == 'completed') {
            $userTransaction = UserTransaction::where('payment_id',$payment->id)->where('confirmed',0)->first();
            if ($userTransaction) {
                $userTransaction->confirmed=1;
                $userTransaction->meta=$payment->getMeta();
                $userTransaction->update();
            }
            $payment->updateUserCredit(intval($userTransaction->amount));
           
        }
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getUnseenMessageCountAttribute()
    {
        return Message::query()->where('to_id', $this->id)->where('from_id', '!=', $this->id)->where('seen', 0)->count(['id']);
    }

    public function stats()
    {
        $isVendor = $this->isVendor();
        $user_id = $this->id;

        if ($isVendor) {

            $totalRevenue = Booking::query()->whereIn('payment_status', [Constants::PAYMENT_PAID, Constants::PAYMENT_PARTIALLY_PAID])
                ->where('vendor_id', $user_id)->sum('host_amount');

            $totalBookings = Booking::query()->where('status', '!=', Constants::BOOKING_STATUS_DRAFT)
                ->where('vendor_id', $user_id)->count();

            $totalReviews = Review::query()->where('vendor_id', $user_id)->count();

        } else {

            $totalRevenue = Booking::query()->whereIn('payment_status', [Constants::PAYMENT_PAID, Constants::PAYMENT_PARTIALLY_PAID])
                ->where('customer_id', $user_id)->sum('payable_amount');

            $totalBookings = Booking::query()->where('status', '!=', Constants::BOOKING_STATUS_DRAFT)
                ->where('customer_id', $user_id)->count(); 

            $totalReviews = Review::query()->where('create_user', $user_id)->count();

        }

        return [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'totalReviews' => $totalReviews
        ];
    }

    public function isVendor()
    {
        if ($this->hasPermissionTo('dashboard_vendor_access')) {
            return true;
        }
        return false;
    }

    public function getBadgeInfo()
    {
        $isVendor = $this->isVendor();

        $stats = $this->stats();
        $totalRevenue = $stats['totalRevenue'];
        $totalBookings = $stats['totalBookings'];
        $totalReviews = $stats['totalReviews'];

        // $totalBookings = 302;
        // $totalReviews = 104;
        // $totalRevenue = 10001;

        $badge = UserBadge::where('min_of_bookings', '<=', $totalBookings)
            ->where('min_of_reviews', '<=', $totalReviews)
            ->where('min_of_revenue', '<=', $totalRevenue)
            ->where('type', $isVendor ? 'host' : 'guest')
            ->orderBy('min_of_bookings', 'DESC')
            ->first();

        if ($badge == null) {
            $badge = UserBadge::orderBy('min_of_bookings', 'ASC')->first();
        }

        // dd([
        //     'totalRevenue' => $totalRevenue,
        //     'totalBookings' => $totalBookings,
        //     'totalReviews' => $totalReviews,
        //     'badge' => $badge->badge_name
        // ]);

        return $badge;
    }

}
