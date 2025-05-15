<?php

namespace Modules\Space\Models;

use App\Currency;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Booking\Traits\CapturesService;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\SEO;
use Modules\Core\Models\Terms;
use Modules\Media\Helpers\FileHelper;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Space\Models\SpaceTranslation;
use Modules\User\Models\UserWishList;
use Modules\Location\Models\Location;
use App\Models\AddToFavourite;

use Illuminate\Support\Facades\DB;

use App\User;
use Illuminate\Support\Facades\Session;

class Space extends Bookable
{
    use Notifiable;
    use SoftDeletes;
    use CapturesService;

    protected $table = 'bravo_spaces';
    public $type = 'space';
    public $checkout_booking_detail_file = 'Space::frontend/booking/detail';
    public $checkout_booking_detail_modal_file = 'Space::frontend/booking/detail-modal';
    public $set_paid_modal_file = 'Space::frontend/booking/set-paid-modal';
    public $email_new_booking_file = 'Space::emails.new_booking_detail';
    public $availabilityClass = SpaceDate::class;

    public $callUpdateStats = true;

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->checkIfNeedToUpdate();
        });

        self::updated(function ($model) {
            $model->checkIfNeedToUpdate();
        });

        static::retrieved(function ($model) {
            // Overwrite the default attribute values
            if($model->map_lat == null){
                $model->map_lat = 0;
            }
            if($model->map_lng == null){
                $model->map_lng = 0;
            }
        });
    }

    public function checkIfNeedToUpdate()
    {
        if ($this->callUpdateStats) {
            $this->callUpdateStats = false;
            $this->updateStats();
        } else {
            $this->callUpdateStats = true;
        }
    }

    protected $fillable = [
        'total_bookings',
        'title',
        'alias',
        'house_rules',
        'tos',
        'content',
        'status',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'map_lat',
        'map_lng',
        'desk',
        'seat',
        'max_guests',
        'faqs',
        'free_cancellation',
        'accessible_workspace',

        'available_from',
        'available_to',
        'first_working_day',
        'last_working_day'
        
    ];
    protected $slugField = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type = 'space';

    protected $casts = [
        'faqs' => 'array',
        'extra_price' => 'array',
        'service_fee' => 'array',
        'surrounding' => 'array',
    ];
    /**
     * @var Booking
     */
    protected $bookingClass;
    /**
     * @var Review
     */
    protected $reviewClass;

    /**
     * @var SpaceDate
     */
    protected $spaceDateClass;

    /**
     * @var spaceTerm
     */
    protected $spaceTermClass;

    /**
     * @var spaceTerm
     */
    protected $spaceTranslationClass;
    protected $userWishListClass;

    protected $tmp_dates = [];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->reviewClass = Review::class;
        $this->spaceDateClass = SpaceDate::class;
        $this->spaceTermClass = SpaceTerm::class;
        $this->spaceTranslationClass = SpaceTranslation::class;
        $this->userWishListClass = UserWishList::class;
    }

    public static function getModelName()
    {
        return __("Space");
    }

    public static function getTableName()
    {
        return with(new static)->table;
    }


    /**
     * Get SEO fop page list
     *
     * @return mixed
     */
    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = __("Search for Spaces");
        if (!empty($title = setting_item_with_lang("space_page_list_seo_title", false))) {
            $meta['seo_title'] = $title;
        } else if (!empty($title = setting_item_with_lang("space_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        $meta['seo_image'] = null;
        if (!empty($title = setting_item("space_page_list_seo_image"))) {
            $meta['seo_image'] = $title;
        } else if (!empty($title = setting_item("space_page_search_banner"))) {
            $meta['seo_image'] = $title;
        }
        $meta['seo_desc'] = setting_item_with_lang("space_page_list_seo_desc");
        $meta['seo_share'] = setting_item_with_lang("space_page_list_seo_share");
        $meta['full_url'] = url(config('space.space_route_prefix'));
        return $meta;
    }


    public function terms()
    {
        return $this->hasMany($this->spaceTermClass, "target_id");
    }


    public function getDetailUrl($include_param = true)
    {
        $param = [];
        if ($include_param) {
            if (!empty($date = request()->input('date'))) {
                $dates = explode(" - ", $date);
                if (!empty($dates)) {
                    $param['start'] = $dates[0] ?? "";
                    $param['end'] = $dates[1] ?? "";
                }
            }

            if (!empty($adults = request()->input('adults'))) {
                $param['adults'] = $adults;
            }

            if (!empty($children = request()->input('children'))) {
                $param['children'] = $children;
            }

            if (!empty($fromHour = request()->input('from_hour'))) {
                $param['start_hour'] = $fromHour;
            }

            if (!empty($toHour = request()->input('to_hour'))) {
                $param['to_hour'] = $toHour;
            }

            if (!empty($start = request()->input('start'))) {
                $param['start'] = $start;
            }

            if (!empty($end = request()->input('end'))) {
                $param['end'] = $end;
            }
        }
        $urlDetail = app_get_locale(false, false, '/') . config('space.space_route_prefix') . "/" . $this->slug;
        if (!empty($param)) {
            $urlDetail .= "?" . http_build_query($param);
        }
        return url($urlDetail);
    }

    public static function getLinkForPageSearch($locale = false, $param = [])
    {

        return url(app_get_locale(false, false, '/') . config('space.space_route_prefix') . "?" . http_build_query($param));
    }

    public function getGallery($featuredIncluded = false)
    {
        if($this->gallery==null){
            return [];
        }
        if (empty($this->gallery))
            return $this->gallery;
        $list_item = [];
        if ($featuredIncluded and $this->image_id) {
            $list_item[] = [
                'large' => FileHelper::url($this->image_id, 'full'),
                'thumb' => FileHelper::url($this->image_id, 'thumb')
            ];
        }
        $items = explode(",", $this->gallery);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url($item, 'thumb');
            $list_item[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $list_item;
    }

    public function getEditUrl()
    {
        return url(route('space.admin.edit', ['id' => $this->id]));
    }

    public function getDiscountPercentAttribute()
    {
        if (
            !empty($this->price) and $this->price > 0
            and !empty($this->sale_price) and $this->sale_price > 0
            and $this->price > $this->sale_price
        ) {
            $percent = 100 - ceil($this->sale_price / ($this->price / 100));
            return $percent . "%";
        }
    }

    public function fill(array $attributes)
    {
        if (!empty($attributes)) {
            foreach ($this->fillable as $item) {
                $attributes[$item] = $attributes[$item] ?? null;
            }
        }
        return parent::fill($attributes); // TODO: Change the autogenerated stub
    }

    public function isBookable()
    {
        if ($this->status != 'publish')
            return false;
        return parent::isBookable();
    }

    public function addToCart(Request $request)
    {
        $res = $this->addToCartValidate($request);
        if ($res !== true)
            return $res;

        // Add Booking
        $total_guests = $request->input('adults') + $request->input('children');
        $discount = 0;
        $startHour = isset($_POST['start_hour']) ? trim($_POST['start_hour']) : null;
        $endHour = isset($_POST['end_hour']) ? trim($_POST['end_hour']) : null;
        $start_date = explode('/', $request->input('start_date'));
        $start_date = $start_date[2] . "-" . $start_date[0] . "-" . $start_date[1];
        $end_date = explode('/', $request->input('end_date'));
        $end_date = $end_date[2] . "-" . $end_date[0] . "-" . $end_date[1];
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);

        //        $start_ampm = isset($_POST['start_ampm']) ? trim($_POST['start_ampm']) : null;
        //        $end_ampm = isset($_POST['end_ampm']) ? trim($_POST['end_ampm']) : null;
        //        if ($start_ampm == 'PM') {
        //            $startHour = $startHour . " " . $start_ampm;
        //            $startHour = date("H:i", strtotime($startHour));
        //        }
        //        $endHour = isset($_POST['end_hour']) ? trim($_POST['end_hour']) : null;
        //        if ($end_ampm == 'PM') {
        //            $endHour = $endHour . " " . $end_ampm;
        //            $endHour = date("H:i", strtotime($endHour));
        //        }

        if ($startHour != null && $endHour != null) {

            $start_date = new \DateTime(date('Y-m-d', $start_date->getTimestamp()) . " " . $startHour . ":00");
            $end_date = new \DateTime(date('Y-m-d', $end_date->getTimestamp()) . " " . $endHour . ":00");

            //check availability

            $extra_price_input = $request->input('extra_price');

            $extra_price = [];

            $space = Space::where('id', $request->input('service_id'))->first();

            //$total = $this->getPriceInRanges($request->input('start_date'), $request->input('end_date'));
            $totalInfo = CodeHelper::getSpacePrice($space, $start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d H:i:s'));
            $total = $totalInfo['price'];

            // $duration_in_hour = max(1, ceil(($end_date->getTimestamp() - $start_date->getTimestamp()) / HOUR_IN_SECONDS) + 24);

            // if ($this->enable_extra_price and !empty($this->extra_price)) {
            //     if (!empty($this->extra_price)) {
            //         foreach ($this->extra_price as $k => $type) {
            //             $type_total = 0;
            //             if (isset($extra_price_input[$k])) {
            //                 switch ($type['type']) {
            //                     case "one_time":
            //                         $type_total = $type['price'];
            //                         break;
            //                     case "per_hour":
            //                         $type_total = $type['price'] * $duration_in_hour;
            //                         break;
            //                     case "per_day":
            //                         $type_total = $type['price'] * ceil($duration_in_hour / 24);
            //                         break;
            //                 }
            //                 if (!empty($type['per_person'])) {
            //                     $type_total *= $total_guests;
            //                 }
            //                 $type['total'] = $type_total;
            //                 $total += $type_total;
            //                 $extra_price[] = $type;
            //             }
            //         }
            //     }
            // }


            // // dd($totalInfo);

            // //Buyer Fees for Admin
            // $total_before_fees = $total;
            // $total_buyer_fee = 0;
            // if (!empty($list_buyer_fees = setting_item('space_booking_buyer_fees'))) {
            //     $list_fees = json_decode($list_buyer_fees, true);
            //     $total_buyer_fee = $this->calculateServiceFees($list_fees, $total_before_fees, $total_guests);
            //     $total += $total_buyer_fee;
            // }

            // //Service Fees for Vendor
            // $total_service_fee = 0;
            // if (!empty($this->enable_service_fee) and !empty($list_service_fee = $this->service_fee)) {
            //     $total_service_fee = $this->calculateServiceFees($list_service_fee, $total_before_fees, $total_guests);
            //     $total += $total_service_fee;
            // }

            if (empty($start_date) or empty($end_date)) {
                return $this->sendError(__("Your selected dates are not valid"));
            }

            $booking = new $this->bookingClass();
            $booking->status = 'draft';
            $booking->object_id = $request->input('service_id');
            $booking->object_model = $request->input('service_type');
            $booking->vendor_id = $this->create_user;
            $booking->customer_id = Auth::id();
            $booking->total_guests = $total_guests;
            $booking->start_date = $start_date->format('Y-m-d H:i:s');
            $booking->end_date = $end_date->format('Y-m-d H:i:s');

            // $booking->vendor_service_fee_amount = $total_service_fee ?? '';
            // $booking->vendor_service_fee = $list_service_fee ?? '';
            // $booking->buyer_fees = $list_buyer_fees ?? '';
            // $booking->total_before_fees = $total_before_fees;
            // $booking->total_before_discount = $total_before_fees;

            // $booking->buyer_fees = json_encode($totalInfo['guestFeeList']);
            // $booking->buyer_fees_amount = ($totalInfo['guestFee']);

            // $booking->vendor_service_fee = json_encode($totalInfo['hostFeeList']);
            // $booking->vendor_service_fee_amount = ($totalInfo['hostFee']);

            // $booking->total_before_fees = $totalInfo['price'];  
            // $booking->total_before_tax = $totalInfo['subTotal'];
            // $booking->total_before_discount = $totalInfo['grandTotal'];

            // $booking->tax = $totalInfo['tax'];
            // $booking->total = $totalInfo['payableAmount'];

            $booking = CodeHelper::assignSpacePricingToBooking($booking, $totalInfo);

            // $booking->calculateCommission();

            // if ($this->isDepositEnable()) {
            //     $booking_deposit_fomular = $this->getDepositFomular();
            //     $tmp_price_total = $booking->total;
            //     if ($booking_deposit_fomular == "deposit_and_fee") {
            //         $tmp_price_total = $booking->total_before_fees;
            //     }

            //     switch ($this->getDepositType()) {
            //         case "percent":
            //             $booking->deposit = $tmp_price_total * $this->getDepositAmount() / 100;
            //             break;
            //         default:
            //             $booking->deposit = $this->getDepositAmount();
            //             break;
            //     }
            //     if ($booking_deposit_fomular == "deposit_and_fee") {
            //         $booking->deposit = $booking->deposit + $total_buyer_fee + $total_service_fee;
            //     }
            // }

            $check = $booking->save();
            if ($check) {

                $this->bookingClass::clearDraftBookings();

                $booking->addMeta('duration', $this->duration);
                $booking->addMeta('base_price', $this->price);
                $booking->addMeta('sale_price', $this->sale_price);
                $booking->addMeta('guests', $total_guests);
                $booking->addMeta('adults', $request->input('adults'));
                $booking->addMeta('children', $request->input('children'));
                $booking->addMeta('extra_price', $extra_price);
                $booking->addMeta('tmp_dates', $this->tmp_dates);
                $booking->addMeta('booking_type', $this->getBookingType());

                if ($this->isDepositEnable()) {
                    $booking->addMeta('deposit_info', [
                        'type' => $this->getDepositType(),
                        'amount' => $this->getDepositAmount(),
                        'fomular' => $this->getDepositFomular(),
                    ]);
                }

                $platform = $request->input('platform');

                return $this->sendSuccess([
                    'url' => $booking->getCheckoutUrl($platform),
                    'booking_code' => $booking->code,
                ]);
            }
        } else {
            return $this->sendError(__("Invalid request, please check if date and time selected properly"));
        }
        return $this->sendError(__("Can not check availability"));
    }

    public function getPriceInRanges($start_date, $end_date)
    {
        $totalPrice = 0;
        $price = ($this->sale_price and $this->sale_price > 0 and $this->sale_price < $this->price) ? $this->sale_price : $this->price;

        $datesRaw = $this->spaceDateClass::getDatesInRanges($start_date, $end_date, $this->id);
        $dates = [];
        if (!empty($datesRaw)) {
            foreach ($datesRaw as $date) {
                $dates[date('Y-m-d', strtotime($date['start_date']))] = $date;
            }
        }

        if (strtotime($start_date) == strtotime($end_date)) {
            if (empty($dates[date('Y-m-d', strtotime($start_date))])) {
                $totalPrice += $price;
            } else {
                $totalPrice += $dates[date('Y-m-d', strtotime($start_date))]->price;
            }
            return $totalPrice;
        }
        if ($this->getBookingType() == 'by_day') {
            $period = periodDate($start_date, $end_date);
        }
        if ($this->getBookingType() == 'by_night') {
            $period = periodDate($start_date, $end_date, false);
        }
        foreach ($period as $dt) {
            $date = $dt->format('Y-m-d');
            if (empty($dates[$date])) {
                $totalPrice += $price;
            } else {
                $totalPrice += $dates[$date]->price;
            }
        }
        $this->tmp_dates = $dates;
        return $totalPrice;
    }

    public function addToCartValidate(Request $request)
    {
        $rules = [
            // 'adults' => 'required|integer|min:1',
            // 'children' => 'required|integer|min:0',
            // 'start_date' => 'required|date_format:Y-m-d',
            // 'end_date' => 'required|date_format:Y-m-d'
        ];

        // Validation
        if (!empty($rules)) {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->sendError('', ['errors' => $validator->errors()]);
            }
        }

        $total_guests = $request->input('adults') + $request->input('children');
        if ($total_guests > $this->max_guests) {
            return $this->sendError(__("Maximum guests is :count ", ['count' => $this->max_guests]));
        }
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (strtotime($start_date) < strtotime(date('Y-m-d 00:00:00')) or strtotime($start_date) > strtotime($end_date)) {
            return $this->sendError(__("Your selected dates are not valid"));
        }

        if ($this->getBookingType() == 'by_night' and strtotime($start_date) == strtotime($end_date)) {
            return $this->sendError(__("Your selected dates are not valid"));
        }

        // Validate Date and Booking
        if (!$this->isAvailableInRanges($start_date, $end_date)) {
            return $this->sendError(__("This space is not available at selected dates"));
        }

        if (!$this->checkBusyDate($start_date, $end_date)) {
            return $this->sendError(__("This space is not available at selected dates"));
        }

        $numberDays = (abs(strtotime($end_date) - strtotime($start_date)) / 86400) + 1;
        if (!empty($this->min_day_stays) and $numberDays < $this->min_day_stays) {
            return $this->sendError(__("You must to book a minimum of :number days", ['number' => $this->min_day_stays]));
        }

        if (!empty($this->min_day_before_booking)) {
            $minday_before = strtotime("today +" . $this->min_day_before_booking . " days");
            if (strtotime($start_date) < $minday_before) {
                return $this->sendError(__("You must book the service for :number days in advance", ["number" => $this->min_day_before_booking]));
            }
        }

        return true;
    }

    public function isAvailableInRanges($start_date, $end_date)
    {

        $days = max(1, floor((strtotime($end_date) - strtotime($start_date)) / DAY_IN_SECONDS));

        if ($this->default_state) {
            $notAvailableDates = $this->spaceDateClass::query()->where([
                ['start_date', '>=', $start_date],
                ['end_date', '<=', $end_date],
                ['active', '0'],
                ['target_id', '=', $this->id],
            ])->count('id');

            if ($notAvailableDates)
                return false;
        } else {
            $availableDates = $this->spaceDateClass::query()->where([
                ['start_date', '>=', $start_date],
                ['end_date', '<=', $end_date],
                ['active', '=', 1],
                ['target_id', '=', $this->id],
            ])->count('id');

            if ($availableDates <= $days)
                return true;
        }

        // Check Order
        $bookingInRanges = $this->bookingClass::getAcceptedBookingQuery($this->id, $this->type)->where([
            ['end_date', '>=', $start_date],
            ['start_date', '<=', $end_date],
        ])->count('id');

        if ($bookingInRanges) {
            return false;
        }
        return true;
    }

    public function getBookingData()
    {
        if (!empty($start = request()->input('start'))) {
            $start_html = display_date($start);
            $end_html = request()->input('end') ? display_date(request()->input('end')) : "";
            $date_html = $start_html . '<i class="fa fa-long-arrow-right" style="font-size: inherit"></i>' . $end_html;
        }
        $booking_data = [
            'id' => $this->id,
            'person_types' => [],
            'max' => 0,
            'open_hours' => [],
            'extra_price' => [],
            'minDate' => date('m/d/Y'),
            'max_guests' => $this->max_guests ?? 1,
            'buyer_fees' => [],
            'start_date' => request()->input('start') ?? "",
            'start_date_html' => $date_html ?? __('Please select'),
            'end_date' => request()->input('end') ?? "",
            'deposit' => $this->isDepositEnable(),
            'deposit_type' => $this->getDepositType(),
            'deposit_amount' => $this->getDepositAmount(),
            'deposit_fomular' => $this->getDepositFomular(),
            'is_form_enquiry_and_book' => $this->isFormEnquiryAndBook(),
            'enquiry_type' => $this->getBookingEnquiryType(),
            'booking_type' => $this->getBookingType(),
        ];
        if (!empty($adults = request()->input('adults'))) {
            $booking_data['adults'] = $adults;
        }
        if (!empty($children = request()->input('children'))) {
            $booking_data['children'] = $children;
        }
        $lang = app()->getLocale();
        if ($this->enable_extra_price) {
            $booking_data['extra_price'] = $this->extra_price;
            if (!empty($booking_data['extra_price'])) {
                foreach ($booking_data['extra_price'] as $k => &$type) {
                    if (!empty($lang) and !empty($type['name_' . $lang])) {
                        $type['name'] = $type['name_' . $lang];
                    }
                    $type['number'] = 0;
                    $type['enable'] = 0;
                    $type['price_html'] = format_money($type['price']);
                    $type['price_type'] = '';
                    switch ($type['type']) {
                        case "per_day":
                            $type['price_type'] .= '/' . __('day');
                            break;
                        case "per_hour":
                            $type['price_type'] .= '/' . __('hour');
                            break;
                    }
                    if (!empty($type['per_person'])) {
                        $type['price_type'] .= '/' . __('guest');
                    }
                }
            }

            $booking_data['extra_price'] = array_values((array) $booking_data['extra_price']);
        }

        $list_fees = setting_item_array('space_booking_buyer_fees');
        if (!empty($list_fees)) {
            foreach ($list_fees as $item) {
                $item['type_name'] = $item['name_' . app()->getLocale()] ?? $item['name'] ?? '';
                $item['type_desc'] = $item['desc_' . app()->getLocale()] ?? $item['desc'] ?? '';
                $item['price_type'] = '';
                if (!empty($item['per_person']) and $item['per_person'] == 'on') {
                    $item['price_type'] .= '/' . __('guest');
                }
                $booking_data['buyer_fees'][] = $item;
            }
        }
        if (!empty($this->enable_service_fee) and !empty($service_fee = $this->service_fee)) {
            foreach ($service_fee as $item) {
                $item['type_name'] = $item['name_' . app()->getLocale()] ?? $item['name'] ?? '';
                $item['type_desc'] = $item['desc_' . app()->getLocale()] ?? $item['desc'] ?? '';
                $item['price_type'] = '';
                if (!empty($item['per_person']) and $item['per_person'] == 'on') {
                    $item['price_type'] .= '/' . __('guest');
                }
                $booking_data['buyer_fees'][] = $item;
            }
        }
        return $booking_data;
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'title as name');
        if (strlen($q)) {

            $query->where('title', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }

    public static function getMinMaxPrice()
    {
        $model = parent::selectRaw('MIN( CASE WHEN sale_price > 0 THEN sale_price ELSE ( price ) END ) AS min_price ,
                                    MAX( CASE WHEN sale_price > 0 THEN sale_price ELSE ( price ) END ) AS max_price ')->where("status", "publish")->first();
        if (empty($model->min_price) and empty($model->max_price)) {
            return [
                0,
                100
            ];
        }
        return [
            $model->min_price,
            $model->max_price
        ];
    }

    public function getReviewEnable()
    {
        return setting_item("space_enable_review", 0);
    }

    public function getReviewApproved()
    {
        return setting_item("space_review_approved", 0);
    }

    public function check_enable_review_after_booking()
    {
        $option = setting_item("space_enable_review_after_booking", 0);
        if ($option) {
            $number_review = $this->reviewClass::countReviewByServiceID($this->id, Auth::id()) ?? 0;
            $number_booking = $this->bookingClass::countBookingByServiceID($this->id, Auth::id()) ?? 0;
            if ($number_review >= $number_booking) {
                return false;
            }
        }
        return true;
    }

    public function check_allow_review_after_making_completed_booking()
    {
        $options = setting_item("space_allow_review_after_making_completed_booking", false);
        if (!empty($options)) {
            $status = json_decode($options);
            $booking = $this->bookingClass::select("status")
                ->where("object_id", $this->id)
                ->where("object_model", $this->type)
                ->where("customer_id", Auth::id())
                ->orderBy("id", "desc")
                ->first();
            $booking_status = $booking->status ?? false;
            if (!in_array($booking_status, $status)) {
                return false;
            }
        }
        return true;
    }

    public static function getReviewStats()
    {
        $reviewStats = [];
        if (!empty($list = setting_item("space_review_stats", []))) {
            $list = json_decode($list, true);
            foreach ($list as $item) {
                $reviewStats[] = $item['title'];
            }
        }
        return $reviewStats;
    }

    public function getReviewDataAttribute()
    {
        $list_score = [
            'score_total' => 0,
            'score_text' => __("Not rated"),
            'total_review' => 0,
            'rate_score' => [],
        ];
        $dataTotalReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $this->id)->where('object_model', $this->type)
            ->whereColumn('create_user', '!=', 'vendor_id')
            ->where("status", "approved")->first();
        if (!empty($dataTotalReview->score_total)) {
            $list_score['score_total'] = number_format($dataTotalReview->score_total, 1);
            $list_score['score_text'] = Review::getDisplayTextScoreByLever(round($list_score['score_total']));
        }
        if (!empty($dataTotalReview->total_review)) {
            $list_score['total_review'] = $dataTotalReview->total_review;
        }
        $list_data_rate = $this->reviewClass::selectRaw('COUNT( CASE WHEN rate_number = 5 THEN rate_number ELSE NULL END ) AS rate_5,
                                                            COUNT( CASE WHEN rate_number = 4 THEN rate_number ELSE NULL END ) AS rate_4,
                                                            COUNT( CASE WHEN rate_number = 3 THEN rate_number ELSE NULL END ) AS rate_3,
                                                            COUNT( CASE WHEN rate_number = 2 THEN rate_number ELSE NULL END ) AS rate_2,
                                                            COUNT( CASE WHEN rate_number = 1 THEN rate_number ELSE NULL END ) AS rate_1 ')->where('object_id', $this->id)->where('object_model', $this->type)
            ->whereColumn('create_user', '!=', 'vendor_id')
            ->where("status", "approved")->first()->toArray();
        for ($rate = 5; $rate >= 1; $rate--) {
            if (!empty($number = $list_data_rate['rate_' . $rate])) {
                $percent = ($number / $list_score['total_review']) * 100;
            } else {
                $percent = 0;
            }
            $list_score['rate_score'][$rate] = [
                'title' => $this->reviewClass::getDisplayTextScoreByLever($rate),
                'total' => $number,
                'percent' => round($percent),
            ];
        }

        return $list_score;
    }

    /**
     * Get Score Review
     *
     * Using for loop space
     */
    public function getScoreReview()
    {
        $space_id = $this->id;
        $list_score = Cache::rememberForever('review_' . $this->type . '_' . $space_id, function () use ($space_id) {
            $dataReview = $this->reviewClass::selectRaw(" AVG(rate_number) as score_total , COUNT(id) as total_review ")->where('object_id', $space_id)->where('object_model', "space")->where("status", "approved")->first();
            $score_total = !empty($dataReview->score_total) ? number_format($dataReview->score_total, 1) : 0;
            return [
                'score_total' => $score_total,
                'total_review' => !empty($dataReview->total_review) ? $dataReview->total_review : 0,
            ];
        });
        $list_score['review_text'] = $list_score['score_total'] ? Review::getDisplayTextScoreByLever(round($list_score['score_total'])) : __("Not rated");
        return $list_score;
    }

    public function getNumberReviewsInService($status = false)
    {
        return $this->reviewClass::countReviewByServiceID($this->id, false, $status, $this->type) ?? 0;
    }

    public function getReviewList()
    {
        return $this->reviewClass::select(['id', 'title', 'content', 'rate_number', 'author_ip', 'status', 'created_at', 'vendor_id', 'create_user'])->where('object_id', $this->id)->where('object_model', 'space')
            ->whereColumn('create_user', '!=', 'vendor_id')
            ->where("status", "approved")
            ->orderBy("id", "desc")
            ->with('author')
            ->paginate(setting_item('space_review_number_per_page', 5));
    }

    public function getNumberServiceInLocation($location)
    {
        $number = 0;
        if (!empty($location)) {
            $number = parent::join('bravo_locations', function ($join) use ($location) {
                $join->on('bravo_locations.id', '=', $this->table . '.location_id')->where('bravo_locations._lft', '>=', $location->_lft)->where('bravo_locations._rgt', '<=', $location->_rgt);
            })->where($this->table . ".status", "publish")->with(['translations'])->count($this->table . ".id");
        }
        if (empty($number))
            return false;
        if ($number > 1) {
            return __(":number Spaces", ['number' => $number]);
        }
        return __(":number Space", ['number' => $number]);
    }

    /**
     * @param $from
     * @param $to
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBookingsInRange($from, $to)
    {

        $query = $this->bookingClass::query();
        $query->whereNotIn('status', ['draft']);
        $query->where('start_date', '<=', $to)->where('end_date', '>=', $from)->take(50);

        $query->where('object_id', $this->id);
        $query->where('object_model', $this->type);

        return $query->orderBy('id', 'asc')->get();
    }

    public function saveCloneByID($clone_id)
    {
        $old = parent::find($clone_id);
        if (empty($old))
            return false;
        $selected_terms = $old->terms->pluck('term_id');
        $old->title = $old->title . " - Copy";
        $new = $old->replicate();
        $new->save();
        //Terms
        foreach ($selected_terms as $term_id) {
            $this->spaceTermClass::firstOrCreate([
                'term_id' => $term_id,
                'target_id' => $new->id
            ]);
        }
        //Language
        $langs = $this->spaceTranslationClass::where("origin_id", $old->id)->get();
        if (!empty($langs)) {
            foreach ($langs as $lang) {
                $langNew = $lang->replicate();
                $langNew->origin_id = $new->id;
                $langNew->save();
                $langSeo = SEO::where('object_id', $lang->id)->where('object_model', $lang->getSeoType() . "_" . $lang->locale)->first();
                if (!empty($langSeo)) {
                    $langSeoNew = $langSeo->replicate();
                    $langSeoNew->object_id = $langNew->id;
                    $langSeoNew->save();
                }
            }
        }
        //SEO
        $metaSeo = SEO::where('object_id', $old->id)->where('object_model', $this->seo_type)->first();
        if (!empty($metaSeo)) {
            $metaSeoNew = $metaSeo->replicate();
            $metaSeoNew->object_id = $new->id;
            $metaSeoNew->save();
        }
    }

    public function hasWishList()
    {
        return $this->hasOne($this->userWishListClass, 'object_id', 'id')->where('object_model', $this->type)->where('user_id', Auth::id() ?? 0);
    }

    public function isWishList()
    {
        if (Auth::id()) {
            if (!empty($this->hasWishList) and !empty($this->hasWishList->id)) {
                return 'active';
            }
        }
        return '';
    }

    public function wishListStatus()
    {
        if (Auth::id()) {
            if (!empty($this->hasWishList) and !empty($this->hasWishList->id)) {
                return 'active';
            }
        }
        return '';
    }


    public static function getServiceIconFeatured()
    {
        return "icofont-building-alt";
    }


    public static function isEnable()
    {
        return setting_item('space_disable') == false;
    }

    public function isDepositEnable()
    {
        return (setting_item('space_deposit_enable') and setting_item('space_deposit_amount'));
    }

    public function getDepositAmount()
    {
        return setting_item('space_deposit_amount');
    }

    public function getDepositType()
    {
        return setting_item('space_deposit_type');
    }

    public function getDepositFomular()
    {
        return setting_item('space_deposit_fomular', 'default');
    }

    public function getTimesNotAvailable()
    {
        $dates = [];

        $blockedTimings = SpaceBlockTime::where('bravo_space_id', $this->id)->get();
        if ($blockedTimings != null) {
            foreach ($blockedTimings as $blockedTiming) {
                $data = json_decode($blockedTiming->data, true);
                $timeTitle = date('H:i', strtotime($data['start_php_time'])) . " - " . date('H:i', strtotime($data['end_php_time']));
                if ($timeTitle == "00:00 - 00:00") {
                    $timeTitle = "All Day";
                }
                $data['title'] = "Not Available (" . $timeTitle . ")";
                unset($data['start_php_time']);
                unset($data['end_php_time']);
                $dates[] = $data;
            }
        }

        $sno = count($dates);
        $bookings = Booking::where('object_model', 'space')->where('object_id', $this->id)->get();
        if ($bookings != null) {
            foreach ($bookings as $booking) {
                $timeTitle = date('H:i', strtotime($booking->start_date)) . " - " . date('H:i', strtotime($booking->end_date));
                if ($timeTitle == "00:00 - 00:00") {
                    $timeTitle = "All Day";
                }
                $dates[] = [
                    'id' => $sno,
                    'title' => "Not Available (" . $timeTitle . ")",
                    'start' => $booking->start_date,
                    'end' => $booking->end_date,
                ];
                $sno++;
            }
        }

        // print_r($dates);die;

        return $dates;
    }

    public function detailBookingEachDate($booking)
    {
        $startDate = $booking->start_date;
        $endDate = $booking->end_date;
        $rowDates = json_decode($booking->getMeta('tmp_dates'));
        $allDates = [];
        $service = $booking->service;

        if ($this->getBookingType() == 'by_day') {
            $period = periodDate($startDate, $endDate);
        }
        if ($this->getBookingType() == 'by_night') {
            $period = periodDate($startDate, $endDate, false);
        }

        foreach ($period as $dt) {
            $price = (!empty($service->sale_price) and $service->sale_price > 0 and $service->sale_price < $service->price) ? $service->sale_price : $service->price;

            $startDate = clone $dt;

            $endDate = $dt->modify('+1 day');

            $date['price'] = $price;
            $date['price_html'] = format_money($price);

            $date['from'] = $startDate->getTimestamp();
            $date['from_html'] = $startDate->format('d/m/Y');

            $date['to'] = $endDate->getTimestamp();
            $date['to_html'] = $endDate->format('d/m/Y');

            $allDates[$startDate->format('Y-m-d')] = $date;
        }

        if (!empty($rowDates)) {
            foreach ($rowDates as $item => $row) {
                $startDate = strtotime($item);
                $endDate = strtotime($item . " +1 day");
                $price = $row->price;
                $date['price'] = $price;
                $date['price_html'] = format_money($price);
                $date['from'] = $startDate;
                $date['from_html'] = date('d/m/Y', $startDate);
                $date['to'] = $endDate;
                $date['to_html'] = date('d/m/Y', ($endDate));
                $allDates[date('Y-m-d', $startDate)] = $date;
            }
        }
        return $allDates;
    }

    public static function isEnableEnquiry()
    {
        if (!empty(setting_item('booking_enquiry_for_space'))) {
            return true;
        }
        return false;
    }

    public static function isFormEnquiryAndBook()
    {
        $check = setting_item('booking_enquiry_for_space');
        if (!empty($check) and setting_item('booking_enquiry_type') == "booking_and_enquiry") {
            return true;
        }
        return false;
    }

    public static function getBookingEnquiryType()
    {
        $check = setting_item('booking_enquiry_for_space');
        if (!empty($check)) {
            if (setting_item('booking_enquiry_type') == "only_enquiry") {
                return "enquiry";
            }
        }
        return "book";
    }

    public static function search(Request $request, $returnQuery = false)
    {
        $searchMethod = trim($request->query('searchMethod'));

        $searchQuery = trim($request->query('q'));
        $search_type = $request->query('search_type');

        $priceType = $request->query('price_type');

        $model_space = parent::query()->select("bravo_spaces.*");
        $model_space->where("bravo_spaces.status", "publish");

        $isQuerySearched = false;
        if (CodeHelper::isNotEmpty($searchQuery)) {
            $isQuerySearched = true;
        }

        // if (!empty($location_id = $request->query('location_id'))) {
        //     $location = Location::query()->where('id', $location_id)->where("status", "publish")->first();
        //     if (!empty($location)) {
        //         $model_space->join('bravo_locations', function ($join) use ($location) {
        //             $join->on('bravo_locations.id', '=', 'bravo_spaces.location_id')
        //                 ->where('bravo_locations._lft', '>=', $location->_lft)
        //                 ->where('bravo_locations._rgt', '<=', $location->_rgt);
        //         });
        //     }
        // }

        

        if ($request->query('price_range') !=null && !empty($price_range = $request->query('price_range'))) {
            $pri_from = explode(";", $price_range)[0];
            $pri_to = explode(";", $price_range)[1];

            $raw_sql_min_max = null;  

            switch ($priceType) {
                case "hourly":
                    // $raw_sql_min_max = "IFNULL(bravo_spaces.discounted_hourly, bravo_spaces.hourly) >= " . $pri_from . " and IFNULL(bravo_spaces.discounted_hourly, bravo_spaces.hourly) <= " . $pri_to;
                    $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_hourly IS NOT NULL AND bravo_spaces.discounted_hourly > 0 THEN bravo_spaces.discounted_hourly
            ELSE bravo_spaces.hourly
        END) BETWEEN $pri_from AND $pri_to";
                    break;
                case "daily":
                    // $raw_sql_min_max = "IFNULL(bravo_spaces.discounted_daily, bravo_spaces.daily) >= " . $pri_from . " and IFNULL(bravo_spaces.discounted_daily, bravo_spaces.daily) <= " . $pri_to;
                    $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_daily IS NOT NULL AND bravo_spaces.discounted_daily > 0 THEN bravo_spaces.discounted_daily
            ELSE bravo_spaces.daily
        END) BETWEEN $pri_from AND $pri_to";
                    break;
                case "weekly":
                    // $raw_sql_min_max = "IFNULL(bravo_spaces.discounted_weekly, bravo_spaces.weekly) >= " . $pri_from . " and IFNULL(bravo_spaces.discounted_weekly, bravo_spaces.weekly) <= " . $pri_to;
                    $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_weekly IS NOT NULL AND bravo_spaces.discounted_weekly > 0 THEN bravo_spaces.discounted_weekly
            ELSE bravo_spaces.weekly
        END) BETWEEN $pri_from AND $pri_to";
                    break;
                case "monthly":
                    // $raw_sql_min_max = "IFNULL(bravo_spaces.discounted_monthly, bravo_spaces.monthly) >= " . $pri_from . " and IFNULL(bravo_spaces.discounted_monthly, bravo_spaces.monthly) <= " . $pri_to;
                    $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_monthly IS NOT NULL AND bravo_spaces.discounted_monthly > 0 THEN bravo_spaces.discounted_monthly
            ELSE bravo_spaces.monthly
        END) BETWEEN $pri_from AND $pri_to";
                    break;
            }

            // switch ($priceType) {
            //     case "hourly":
            //         $raw_sql_min_max = "(CASE WHEN bravo_spaces.discounted_hourly IS NOT NULL AND bravo_spaces.discounted_hourly > 0 THEN bravo_spaces.discounted_hourly ELSE bravo_spaces.hourly END) >= " . $pri_from . " AND (CASE WHEN bravo_spaces.discounted_hourly IS NOT NULL AND bravo_spaces.discounted_hourly > 0 THEN bravo_spaces.discounted_hourly ELSE bravo_spaces.hourly END) <= " . $pri_to;
            //         break;
            //     case "daily":
            //         $raw_sql_min_max = "(CASE WHEN bravo_spaces.discounted_daily IS NOT NULL AND bravo_spaces.discounted_daily > 0 THEN bravo_spaces.discounted_daily ELSE bravo_spaces.daily END) >= " . $pri_from . " AND (CASE WHEN bravo_spaces.discounted_daily IS NOT NULL AND bravo_spaces.discounted_daily > 0 THEN bravo_spaces.discounted_daily ELSE bravo_spaces.daily END) <= " . $pri_to;
            //         break;
            //     case "weekly":
            //         $raw_sql_min_max = "(CASE WHEN bravo_spaces.discounted_weekly IS NOT NULL AND bravo_spaces.discounted_weekly > 0 THEN bravo_spaces.discounted_weekly ELSE bravo_spaces.weekly END) >= " . $pri_from . " AND (CASE WHEN bravo_spaces.discounted_weekly IS NOT NULL AND bravo_spaces.discounted_weekly > 0 THEN bravo_spaces.discounted_weekly ELSE bravo_spaces.weekly END) <= " . $pri_to;
            //         break;
            //     case "monthly":
            //         $raw_sql_min_max = "(CASE WHEN bravo_spaces.discounted_monthly IS NOT NULL AND bravo_spaces.discounted_monthly > 0 THEN bravo_spaces.discounted_monthly ELSE bravo_spaces.monthly END) >= " . $pri_from . " AND (CASE WHEN bravo_spaces.discounted_monthly IS NOT NULL AND bravo_spaces.discounted_monthly > 0 THEN bravo_spaces.discounted_monthly ELSE bravo_spaces.monthly END) <= " . $pri_to;
            //         break;
            // }            

            if ($raw_sql_min_max != null) {
                $model_space->whereRaw($raw_sql_min_max);
            }
        }


        if ($priceType === "hourly" && !empty($price_range = $request->query('price_range_hourly'))) {
            $pri_from = explode(";", $price_range)[0];
            $pri_to = explode(";", $price_range)[1];
            $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_hourly IS NOT NULL AND bravo_spaces.discounted_hourly > 0 THEN bravo_spaces.discounted_hourly
            ELSE bravo_spaces.hourly
        END) BETWEEN $pri_from AND $pri_to";
            $model_space->whereRaw($raw_sql_min_max);
        }

        if ($priceType === "daily" && !empty($price_range = $request->query('price_range_daily'))) {
            $pri_from = explode(";", $price_range)[0];
            $pri_to = explode(";", $price_range)[1];
            $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_daily IS NOT NULL AND bravo_spaces.discounted_daily > 0 THEN bravo_spaces.discounted_daily
            ELSE bravo_spaces.daily
        END) BETWEEN $pri_from AND $pri_to";
            $model_space->whereRaw($raw_sql_min_max);
        }

        if ($priceType === "weekly" && !empty($price_range = $request->query('price_range_weekly'))) {
            $pri_from = explode(";", $price_range)[0];
            $pri_to = explode(";", $price_range)[1];
            $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_weekly IS NOT NULL AND bravo_spaces.discounted_weekly > 0 THEN bravo_spaces.discounted_weekly
            ELSE bravo_spaces.weekly
        END) BETWEEN $pri_from AND $pri_to";
            $model_space->whereRaw($raw_sql_min_max);
        }

        if ($priceType === "monthly" && !empty($price_range = $request->query('price_range_monthly'))) {
            $pri_from = explode(";", $price_range)[0];
            $pri_to = explode(";", $price_range)[1];
            $raw_sql_min_max = "(CASE
            WHEN bravo_spaces.discounted_monthly IS NOT NULL AND bravo_spaces.discounted_monthly > 0 THEN bravo_spaces.discounted_monthly
            ELSE bravo_spaces.monthly
        END) BETWEEN $pri_from AND $pri_to";
            $model_space->whereRaw($raw_sql_min_max);
        }



        $terms = $request->query('terms');
        //        if ($term_id = $request->query('term_id')) {
        //            $terms[] = $term_id;
        //        }

        if (CodeHelper::isNotEmpty($searchQuery)) {
            $model_space->where('title', 'LIKE', '%' . $searchQuery . '%');
        }

        if (is_array($terms) && !empty($terms)) {
            $terms = Arr::where($terms, function ($value, $key) {
                return !is_null($value);
            });
            if (!empty($terms)) {
                $model_space->join('bravo_space_term as tt', 'tt.target_id', "bravo_spaces.id")->whereIn('tt.term_id', $terms);
            }
        }

        $review_scores = $request->query('review_score');
        if (is_array($review_scores) && !empty($review_scores)) {
            $where_review_score = [];
            $params = [];
            foreach ($review_scores as $number) {
                $where_review_score[] = " ( bravo_spaces.review_score >= ? AND bravo_spaces.review_score <= ? ) ";
                $params[] = $number;
                $params[] = $number . '.9';
            }
            $sql_where_review_score = " ( " . implode("OR", $where_review_score) . " )  ";
            $model_space->WhereRaw($sql_where_review_score, $params);
        }

        if (!empty($service_name = $request->query("service_name"))) {
            if (setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale()) {
                $model_space->leftJoin('bravo_space_translations', function ($join) {
                    $join->on('bravo_spaces.id', '=', 'bravo_space_translations.origin_id');
                });
                $model_space->where('bravo_space_translations.title', 'LIKE', '%' . $service_name . '%');
            } else {
                $model_space->where('bravo_spaces.title', 'LIKE', '%' . $service_name . '%');
            }
        }

        $distanceOrderByStr = null;

        $lat = $lgn = null;

        if (CodeHelper::isNotEmpty($request->query('map_lat'))) {
            $lat = $request->query('map_lat');
            $lgn = $request->query('map_lgn');
        } elseif (CodeHelper::isNotEmpty($request->query('userLat'))) {
            $lat = $request->query('userLat');
            $lgn = $request->query('userLng');
        }

        if ($lat != null) {
            $distanceOrderByStr = ["POW((bravo_spaces.map_lng-?),2) + POW((bravo_spaces.map_lat-?),2)", [$lgn, $lat]];
            if (!$isQuerySearched) {
                $model_space->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $lgn . "," . $lat . "))) <= (50 / 0.001)");
            }
        }

        // if (!empty($lat = $request->query('map_lat')) and !empty($lgn = $request->query('map_lgn'))) {
        //     if ($lat != null && $lgn != null) {
        //         if (!$isQuerySearched) {
        //             $model_space->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $lgn . "," . $lat . "))) <= (50 / 0.001)");
        //         }
        //     }
        //     $distanceOrderByStr = ["POW((bravo_spaces.map_lng-?),2) + POW((bravo_spaces.map_lat-?),2)", [$lgn, $lat]];
        // } else {
        //     $userLat = $request->query('userLat');
        //     $userLng = $request->query('userLng');
        //     if (!CodeHelper::isNotEmpty($searchQuery)) {
        //         if (CodeHelper::isNotEmpty($userLat) && CodeHelper::isNotEmpty($userLng)) {
        //             if (!$isQuerySearched) {
        //                 $model_space->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $userLng . "," . $userLat . "))) <= (50 / 0.001)");
        //             }
        //             $distanceOrderByStr = ["POW((bravo_spaces.map_lng-?),2) + POW((bravo_spaces.map_lat-?),2)", [$userLng, $userLat]];
        //         }
        //     }
        // }

        $orderby = $request->input("orderby");
        switch ($orderby) {
            case "best_rated":
                $model_space->where("review_score", '>', 4)->orderBy("review_score", "desc");
                break;
            case "on-sale":
                switch ($priceType) {
                    case "hourly":
                        $model_space->where('discounted_hourly', '>', 0)
                            ->where('discounted_hourly', '<', DB::raw('hourly'));
                        break;
                    case "daily":
                        $model_space->where('discounted_daily', '>', 0)
                            ->where('discounted_daily', '<', DB::raw('daily'));
                        break;
                    case "weekly":
                        $model_space->where('discounted_weekly', '>', 0)
                            ->where('discounted_weekly', '<', DB::raw('weekly'));
                        break;
                    case "monthly":
                        $model_space->where('discounted_monthly', '>', 0)
                            ->where('discounted_monthly', '<', DB::raw('monthly'));
                        break;
                }
                break;
            case "price_low_high":
                switch ($priceType) {
                    case "hourly":
                        $model_space->orderBy("hourly", "asc");
                        break;
                    case "daily":
                        $model_space->orderBy("daily", "asc");
                        break;
                    case "weekly":
                        $model_space->orderBy("weekly", "asc");
                        break;
                    case "monthly":
                        $model_space->orderBy("monthly", "asc");
                        break;
                }
                break;
            case "price_high_low":
                switch ($priceType) {
                    case "hourly":
                        $model_space->orderBy("hourly", "desc");
                        break;
                    case "daily":
                        $model_space->orderBy("daily", "desc");
                        break;
                    case "weekly":
                        $model_space->orderBy("weekly", "desc");
                        break;
                    case "monthly":
                        $model_space->orderBy("monthly", "desc");
                        break;
                }
                break;
            case "rate_high_low":
                $model_space->orderBy("review_score", "desc");
                break;
            default:
                // $model_space->orderBy("is_featured", "desc");
                if ($distanceOrderByStr !== null) {
                    $model_space->orderByRaw($distanceOrderByStr[0], $distanceOrderByStr[1]);
                } else {
                    $model_space->orderBy("id", "desc");
                }
        }

        $model_space->groupBy("bravo_spaces.id");

        $max_guests = (int) ($request->query('adults') + $request->query('children'));
        if ($max_guests) {
            $model_space->where('max_guests', '>=', $max_guests);
        }

        if (!empty($request->query('limit'))) {
            $limit = $request->query('limit');
        } else {
            $limit = !empty(setting_item("space_page_limit_item")) ? setting_item("space_page_limit_item") : 9;
        }

        $startDate = isset($_GET['start']) ? trim($_GET['start']) : null;
        $endDate = isset($_GET['end']) ? trim($_GET['end']) : null;

        if ($search_type == 2) {
            $startHour = "12:00";
            $endHour = "12:00";
        } else {
            $startHour = isset($_GET['from_hour']) ? trim($_GET['from_hour']) : null;
            $endHour = isset($_GET['to_hour']) ? trim($_GET['to_hour']) : null;
        }

        if ($search_type == 3) {
            $model_space->leftJoin('bravo_space_term', function ($join) {
                $join->on('bravo_spaces.id', '=', 'bravo_space_term.target_id');
            });
            $model_space->where('bravo_space_term.term_id', '=', 91);
        }

        if ($search_type == 4) {
            $model_space->leftJoin('bravo_space_term', function ($join) {
                $join->on('bravo_spaces.id', '=', 'bravo_space_term.target_id');
            });
            $model_space->where('bravo_space_term.term_id', '=', 17);
        }

        if ($startDate != null && $endDate != null) {
            if ($startHour != null && $endHour != null) {
                $startDate = date('Y-m-d', strtotime($startDate)) . " " . $startHour . ":01";
                $toDate = date('Y-m-d', strtotime($endDate)) . " " . $endHour . ":00";
                $toDate = date('Y-m-d H:i:s', (strtotime($toDate) - 1));

                // echo $startDate." -> ".$toDate;die;

                //find booked listings in this date
                $bookedListings = Booking::whereRaw("`status` != 'draft' and `object_model` = 'space' and ( (`start_date` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR (`end_date` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR ('$startDate' BETWEEN `start_date` and `end_date`) OR ('$toDate' BETWEEN `start_date` and `end_date`) )")->pluck('object_id')->toArray();
                $bookedListings = array_unique($bookedListings);
                if (is_array($bookedListings) && count($bookedListings) > 0) {
                    $model_space->whereRaw('`bravo_spaces`.`id` NOT IN (' . implode(',', $bookedListings) . ')');
                }

                //find blocked listings in this date
                $blockedListings = SpaceBlockTime::whereRaw("( (`from` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR (`to` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR ('$startDate' BETWEEN `from` and `to`) OR ('$toDate' BETWEEN `from` and `to`) )")->pluck('bravo_space_id')->toArray();
                $blockedListings = array_unique($blockedListings);
                if (is_array($blockedListings) && count($blockedListings) > 0) {
                    $model_space->whereRaw('`bravo_spaces`.`id` NOT IN (' . implode(',', $blockedListings) . ')');
                }
            }
        }

        // if (!empty($request->query('adults'))) {
        //     $guest_count = $request->query('adults');
        //     $model_space->where('max_guests', '>=', $guest_count);
        // }

        if (!empty($request->query('rapidbook'))) {
            $model_space->where('rapidbook', 1);
        }

        if (!empty($request->query('top_rated'))) {
            //top rates
            $model_space->where('review_score', '>=', Constants::TOP_RATED_AVERAGE_RATING);
            $model_space->where('total_bookings', '>=', Constants::TOP_RATED_TOTAL_BOOKINGS);
        }

        if (!empty($request->query('accessibility'))) {
            $id = (Terms::where('slug', 'accessibility')->first()->id) ?? '';
            $model_space->leftJoin('bravo_space_term', function ($join) {
                $join->on('bravo_spaces.id', '=', 'bravo_space_term.target_id');
            });
            $model_space->where('bravo_space_term.term_id', $id);
        }

        if (!empty($request->query('free_cancellation'))) {
            $model_space->where('free_cancellation', 1);
        }

        if (!empty($request->query('super_host')) ) {
            $ids = (User::where('super_host', 1)->pluck('id')->toArray()) ?? '';
            $model_space->whereIn('bravo_spaces.create_user', $ids);
        }

        if (!empty($request->query('desk'))) {
            $desk_count = $request->query('desk');
            $model_space->where('desk', '>=', $desk_count);
        }

        if (!empty($request->query('seat'))) {
            $seat_count = $request->query('seat');
            $model_space->where('seat', '>=', $seat_count);
        }

        // if (!empty($request->query('adults'))) {
        //     $guest_count = $request->query('adults');
        //     $model_space->where('max_guests', '>=', $guest_count);
        // }

        if ($request->query('start') && $request->query('end')) {
            $start = dateFormat($request->query('start'));
            $end = dateFormat($request->query('end'));
            $space_ids = Booking::whereBetween('start_date', [$start, $end])->orWhereBetween('end_date', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_date', '<', $start)->where('end_date', '>', $end);
                })
                ->groupBy('object_id')->pluck('object_id')->toArray();

            $model_space->whereNotIn('bravo_spaces.id', $space_ids);
        }

        $longTermRental = isset($_GET['long_term_rental']) ? trim($_GET['long_term_rental']) : null;
        if ($longTermRental !== null) {
            if ($longTermRental == 1) {
                $model_space->whereRaw('`bravo_spaces`.`long_term_rental` = 1');
            } else {
                //$model_space->whereRaw('`bravo_spaces`.`long_term_rental` = 0');
            }
        }

        $have = isset($_GET['have']) ? trim($_GET['have']) : null;
        if ($have !== null) {
            $termModel = Terms::where('slug', $have)->first();
            if (!empty($termModel)) {
                $spacesWithTerm = SpaceTerm::where('term_id', $termModel->id)->pluck('target_id')->toArray();
                if (is_array($spacesWithTerm) && count($spacesWithTerm) > 0) {
                    $spacesWithTerm = array_unique($spacesWithTerm);
                } else {
                    $spacesWithTerm = [-1];
                }
                $model_space->whereRaw('`bravo_spaces`.`id` IN (' . implode(',', $spacesWithTerm) . ')');
            }
        }

        // CodeHelper::debugQuery($model_space);
        // $model_space->with(['location', 'hasWishList', 'translations']);

        if ($returnQuery) {
            $query2 = clone $model_space;
            $results = null;
            if ($searchMethod == "map") {
                $results = $model_space->get();
            } else {
                $results = $model_space->paginate($limit);
            }
            return [
                'query' => $query2,
                'results' => $results
            ];
        }

        return $model_space->paginate($limit);
    }

    public function dataForApi($forSingle = false)
    {
        $data = parent::dataForApi($forSingle);
        $data['max_guests'] = $this->max_guests;
        $data['desk'] = $this->desk;
        $data['seat'] = $this->seat;
        $data['square'] = $this->square;
        if ($forSingle) {
            $data['review_score'] = $this->getReviewDataAttribute();
            $data['review_stats'] = $this->getReviewStats();
            $data['review_lists'] = $this->getReviewList();
            $data['faqs'] = $this->faqs;
            $data['is_instant'] = $this->is_instant;
            $data['allow_children'] = $this->allow_children;
            $data['allow_infant'] = $this->allow_infant;
            $data['discount_by_days'] = $this->discount_by_days;
            $data['default_state'] = $this->default_state;
            $data['booking_fee'] = setting_item_array('space_booking_buyer_fees');
            if (!empty($location_id = $this->location_id)) {
                $related = parent::query()->where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$this->id])->with(['location', 'translations', 'hasWishList'])->get();
                $data['related'] = $related->map(function ($related) {
                    return $related->dataForApi();
                }) ?? null;
            }
            $data['terms'] = Terms::getTermsByIdForAPI($this->terms->pluck('term_id'));
        } else {
            $data['review_score'] = $this->getScoreReview();
        }
        return $data;
    }

    static public function getClassAvailability()
    {
        return "\Modules\Space\Controllers\AvailabilityController";
    }

    static public function getFiltersSearch()
    {
        $min_max_price = self::getMinMaxPrice();
        return [
            [
                "title" => __("Filter Price"),
                "field" => "price_range",
                "position" => "1",
                "min_price" => floor(Currency::convertPrice($min_max_price[0])),
                "max_price" => ceil(Currency::convertPrice($min_max_price[1])),
            ],
            [
                "title" => __("Review Score"),
                "field" => "review_score",
                "position" => "2",
                "min" => "1",
                "max" => "5",
            ],
            [
                "title" => __("Attributes"),
                "field" => "terms",
                "position" => "3",
                "data" => Attributes::getAllAttributesForApi("space")
            ]
        ];
    }

    public static function getBookingType()
    {
        return setting_item('space_booking_type', 'by_day');
    }

    static public function getFormSearch()
    {
        $search_fields = setting_item_array('space_search_fields');
        $search_fields = array_values(\Illuminate\Support\Arr::sort($search_fields, function ($value) {
            return $value['position'] ?? 0;
        }));
        foreach ($search_fields as &$item) {
            if ($item['field'] == 'attr' and !empty($item['attr'])) {
                $attr = Attributes::find($item['attr']);
                $item['attr_title'] = $attr->translateOrOrigin(app()->getLocale())->name;
                foreach ($attr->terms as $term) {
                    $translate = $term->translateOrOrigin(app()->getLocale());
                    $item['terms'][] = [
                        'id' => $term->id,
                        'title' => $translate->name,
                    ];
                }
            }
            if ($item['field'] == 'guests') {
                $item['field_guests'] = [
                    [
                        'id' => 'adults',
                        'title' => __('Adults')
                    ],
                    [
                        'id' => 'children',
                        'title' => __('Children')
                    ]
                ];
            }
        }
        return $search_fields;
    }

    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        return $user;
    }

    public function getDefaultPrice($id)
    {
        $space = Space::where('id', $id)->first();
        $price = ($space->price) ? $space->price : '';
        $discountPrice = ($space->sale_price) ? $space->sale_price : '';
        if ($space->hourly_price_set_default) {
            $price = $space->hourly;
            $discountPrice = $space->hourly - ($space->hourly * ($space->discount / 100));
        }
        if ($space->daily_price_set_default) {
            $price = $space->daily;
            $discountPrice = $space->daily - ($space->daily * ($space->discount / 100));
        }
        if ($space->weekly_price_set_default) {
            $price = $space->weekly;
            $discountPrice = $space->weekly - ($space->weekly * ($space->discount / 100));
        }
        if ($space->monthly_price_set_default) {
            $price = $space->monthly;
            $discountPrice = $space->monthly - ($space->monthly * ($space->discount / 100));
        }



        return ['price' => $price, 'discountPrice' => $discountPrice];
    }


    public function lastBooking()
    {
        return Booking::where([
            'object_id' => $this->id,
            'object_model' => 'space'
        ])->where('status', '!=', Constants::BOOKING_STATUS_DRAFT)->orderBy('id', 'DESC')->first();
    }

    public function totalBookings()
    {
        $totalBookings = Booking::where([
            'object_id' => $this->id,
            'object_model' => 'space'
        ])->where('status', '!=', Constants::BOOKING_STATUS_DRAFT)->count();
        if ($totalBookings == null) {
            $totalBookings = 0;
        }
        return $totalBookings;
    }

    public function totalEarnings()
    {
        $totalBookings = Booking::where([
            'object_id' => $this->id,
            'object_model' => 'space'
        ])->where('status', '!=', Constants::BOOKING_STATUS_DRAFT)->sum('host_amount');
        if ($totalBookings == null) {
            $totalBookings = 0;
        }
        return $totalBookings;
    }


    public function isfavourite()
    {
        $user_id = Auth::id();
        // return $this->id;
        if ($user_id) {
            $data = AddToFavourite::where('user_id', $user_id)->where('object_id', $this->id)->first();
            if ($data) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isTopRated()
    {
        if ($this->total_bookings >= Constants::TOP_RATED_TOTAL_BOOKINGS) {
            $reviewScore = $this->review_score;
            if ($reviewScore == null) {
                $reviewScore = 0;
            }
            if ($reviewScore >= Constants::TOP_RATED_AVERAGE_RATING) {
                return true;
            }
        }
        return false;
    }

    public function updateStats()
    {
        $totalBookings = Booking::where('object_id', $this->id)
            ->where('object_model', 'space')->whereNotIn('status', [
                    Constants::BOOKING_STATUS_DRAFT,
                    Constants::BOOKING_STATUS_FAILED
                ])->count();
        if ($totalBookings == null) {
            $totalBookings = 0;
        }
        $this->total_bookings = $totalBookings;
        $this->save();
    }


    public function calculateDistance($lat2 = null, $lon2 = null)
    {
        // $unit = K/N/M   
        $lat1 = $lon1 = "";
        try {
            $lat1 = $this->map_lat;
            $lon1 = $this->map_lng;
            if ($lat1 != null && $lon1 != null) {
                $lat1 = $lat1 * 1;
                $lon1 = $lon1 * 1;

                if ($lat2 == null) {
                    if (Session::has('lat') && Session::has('lng')) {
                        $lat2 = Session::get('lat');
                        $lon2 = Session::get('lng');
                    }
                }

                if ($lat2 != null && $lon2 != null) {

                    // dd([
                    //     'lat1' => $lat1,
                    //     'lon1' => $lon1,
                    //     'lat2' => $lat2,
                    //     'lon2' => $lon2,
                    // ]);

                    if (is_array($lat2) && count($lat2) > 0) {
                        $lat2 = $lat2[0];
                    }

                    if (is_array($lon2) && count($lon2) > 0) {
                        $lon2 = $lon2[0];
                    }

                    if ($lat2 != null && $lon2 != null) {
                        $lat2 = $lat2 * 1;
                        $lon2 = $lon2 * 1;
                        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                            return 0;
                        } else {

                            $distance = CodeHelper::haversineGreatCircleDistance(
                                $lat1,
                                $lon1,
                                $lat2,
                                $lon2
                            );

                            return $distance;
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
        }
        return null;
    }

    public function addressWithDistance($showWithAddress = true, $explodedCity = false, $lat = null, $lng = null, $showLatLng = false)
    {
        $addressMain = trim($this->address);
        if (CodeHelper::isNotEmpty(trim($this->city))) {
            $addressMain = trim($this->address) . ", " . trim($this->city) . ", " . trim($this->state) . " " . trim($this->zip) . ", " . trim($this->country);
        }

        if (CodeHelper::isNotEmpty(trim($this->address_unit))) {
            // $addressMain = $this->address_unit . ' ' . $addressMain;
        }

        $distance = $this->calculateDistance($lat, $lng);
        if ($distance > 0) {
            if ($distance > 100) {
                $distance = "100+";
            } else {
                $distance = round($distance, 2);
            }
        }

        $addressD = $addressMain;

        if ($explodedCity) {
            $explodedCityData = explode(',', $addressD);
            if (count($explodedCityData) == 4) {
                $explodedCityData2 = $explodedCityData;
                unset($explodedCityData2[0]);
                $addressD = $explodedCityData[0] . ",</br>" . trim(implode(',', $explodedCityData2));
            }
        }

        $title = $addressD;

        if ($distance != null) {
            if ($showWithAddress) {
                $title = $distance . "km </br>" . $addressD;
            } else {
                $title = $distance . "km";
            }
            // $title = $distance . "km, " . $addressD;
        }

        if ($showLatLng) {
            $title .= "</br>Search Location: " . $lat . ", " . $lng;
            $title .= "</br>Space Location: " . $this->map_lat . ", " . $this->map_lng;
        }

        $mapLink = 'https://www.google.com/maps/dir/?api=1&origin=Current+Location&destination=' . trim(urlencode($addressMain));
        if (Session::has('lat') && Session::has('lng')) {
            $lat = Session::get('lat');
            $lon = Session::get('lng');
            $mapLink = 'https://www.google.com/maps/dir/?api=1&origin=' . $lat . ',' . $lon . '&destination=' . trim(urlencode($addressMain));
        }

        // $addressMain .= '  '.$this->map_lat . ", " . $this->map_lng;
  
        return [
            'title' => $title,
            'address' => $addressMain,
            'link' => $mapLink
        ];
    }

    public function mapViewImage()
    {
        return "https://maps.google.com/maps/api/staticmap?center=25.3176452,82.97391440000001,&zoom=15&markers=icon:https://i.imgur.com/x1z7C2s.png|" . $this->map_lat . "," . $this->map_lng . "&path=color:0x0000FF80|weight:5|" . $this->map_lat . "," . $this->map_lng . "&size=100x100&key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc";
    }

    public function getImageLink() 
    {
        $link = "https://placehold.co/600x400/EEEEEE/CCCCCC/png?text=" . urlencode($this->title);
        if ($this->image_url){
            $link = $this->image_url;
        }   
        return $link;
    }

}
