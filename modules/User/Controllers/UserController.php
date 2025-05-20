<?php

namespace Modules\User\Controllers;

use App\BaseModel;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Helpers\CRMHelper;
use App\Helpers\EmailHelper;
use App\Helpers\EmailTemplateConstants;
use App\Models\AddToFavourite;
use App\Models\LoginRequests;
use App\Notifications\AdminChannelServices;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Matrix\Exception;
use Modules\Booking\Models\Payment;
use Modules\Contact\Emails\NotificationEmail;
use Modules\Core\Models\Attributes;
use Modules\FrontendController;
use Modules\Location\Models\Location;
use Modules\Review\Models\Review;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceTerm;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Events\UserSubscriberSubmit;
use Modules\User\Models\Subscriber;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Modules\Vendor\Models\VendorRequest;
use Validator;
use Modules\Booking\Models\Booking;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Booking\Models\Enquiry;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Input;
use Modules\User\Events\UserReferredEvent;

class UserController extends FrontendController
{
    use AuthenticatesUsers;

    protected $enquiryClass;

    public function __construct()
    {
        $this->enquiryClass = Enquiry::class;
        parent::__construct();
    }

    public function dashboard(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user_id = Auth::id();

        $data = [
            'cards_report' => Booking::getTopCardsReportForVendor($user_id),
            'earning_chart_data' => Booking::getEarningChartDataForVendor(strtotime('monday this week'), time(), $user_id),
            'page_title' => __("Vendor Dashboard"),
            'breadcrumbs' => [
                [
                    'name' => __('Dashboard'),
                    'class' => 'active'
                ]
            ],
        ];
        return view('User::frontend.dashboard', $data);
    }

    public function earningStats(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user_id = Auth::id();
        $earningStats = Booking::getEarningStats($user_id, $request->get('durationType'));
        return response()->json($earningStats);
    }

    public function saleOverview(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user_id = Auth::id();
        $earningStats = Booking::getEarningStats($user_id, $request->get('durationType'));
        return response()->json($earningStats);
    }

    public function saleBySpaceTable(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $duration = $request->input('durationType');
        if ($duration == null) {
            $duration = 'week';
        }

        $startDate = date('Y-m-d', strtotime("monday this week")) . " 00:00:00";
        $endDate = date('Y-m-d') . " 23:59:59";

        switch ($duration) {
            case 'month':
                $startDate = date('Y-m-01') . " 00:00:00";
                break;
            case 'year':
                $startDate = date('Y-01-01') . " 00:00:00";
                break;
        }

        $user_id = Auth::id();
        $html = '<tr><td colspan="3">Not Results Found</td></tr>';
        $spaces = Space::where('bravo_spaces.create_user', $user_id)
            ->selectRaw('SUM(bravo_bookings.host_amount) AS total_host_amount, COUNT(*) AS totalBookings, bravo_spaces.*')
            ->join('bravo_bookings', 'bravo_bookings.object_id', '=', 'bravo_spaces.id')
            ->whereBetween('bravo_bookings.start_date', [$startDate, $endDate])
            ->groupBy(DB::raw('bravo_spaces.id'))
            ->limit(5)
            ->get();
        if ($spaces != null) {
            $html = '<tr>';
            foreach ($spaces as $space) {
                $html .= '<tr>
                    <td>' . $space->title . '</td>
                    <td>' . $space->totalBookings . '</td>
                    <td>' . CodeHelper::formatPrice($space->total_host_amount) . '</td>
                </tr>';
            }
            $html .= '</tr>';
        }
        return $html;
    }

    public function saleByClientTable(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user_id = Auth::id();
        $duration = $request->input('durationType');
        if ($duration == null) {
            $duration = 'week';
        }

        $startDate = date('Y-m-d', strtotime("monday this week")) . " 00:00:00";
        $endDate = date('Y-m-d') . " 23:59:59";

        switch ($duration) {
            case 'month':
                $startDate = date('Y-m-01') . " 00:00:00";
                break;
            case 'year':
                $startDate = date('Y-01-01') . " 00:00:00";
                break;
        }

        $user_id = Auth::id();
        $html = '<tr><td colspan="3">Not Results Found</td></tr>';
        $spaces = Space::where('bravo_spaces.create_user', $user_id)
            ->selectRaw('CONCAT(users.first_name, " ", users.last_name) as customerName,  SUM(bravo_bookings.host_amount) AS total_host_amount, COUNT(*) AS totalBookings, bravo_spaces.*')
            ->join('bravo_bookings', 'bravo_bookings.object_id', '=', 'bravo_spaces.id')
            ->join('users', 'bravo_bookings.customer_id', '=', 'users.id')
            ->whereBetween('bravo_bookings.start_date', [$startDate, $endDate])
            ->groupBy(DB::raw('users.id'))
            ->limit(5)
            ->get();
        if ($spaces != null) {
            $html = '<tr>';
            foreach ($spaces as $space) {
                $html .= '<tr>
                    <td>' . $space->customerName . '</td>
                    <td>' . $space->totalBookings . '</td>
                    <td>' . CodeHelper::formatPrice($space->total_host_amount) . '</td>
                </tr>';
            }
            $html .= '</tr>';
        }
        return $html;
    }

    public function reloadChart(Request $request)
    {
        $chart = $request->input('chart');
        $user_id = Auth::id();
        switch ($chart) {
            case "earning":
                $from = $request->input('from');
                $to = $request->input('to');
                return $this->sendSuccess([
                    'data' => Booking::getEarningChartDataForVendor(strtotime($from), strtotime($to), $user_id)
                ]);
                break;
        }
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        if (substr($user->phone, 0, 1) != "+") {
            $user->phone = "+" . $user->phone;
        }
        $data = [
            'dataUser' => $user,
            'page_title' => __("Profile"),
            'breadcrumbs' => [
                [
                    'name' => __('Setting'),
                    'class' => 'active'
                ]
            ],
            'is_vendor_access' => $this->hasPermission('dashboard_vendor_access')
        ];
        return view('User::frontend.profile', $data);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $messages = [
            'user_name.required' => __('The User name field is required.'),
        ];
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'address' => 'required',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'user_name' => [
                'required',
                'max:255',
                'min:4',
                'string',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($user->id)
            ],
        ], $messages);

        if ($request->input('phone') === "" || $request->input('phone') === "+") {
            return $this->sendError(__("The phone should be required."), [
                'errorCode' => 'failed'
            ])->setStatusCode(401);
        }

        $input = $request->except('bio');
        $user->fill($input);
        $user->bio = clean($request->input('bio'));
        $user->birthday = date("Y-m-d", strtotime($user->birthday));
        $user->user_name = Str::slug($request->input('user_name'), "_");
        $user->save();
        return redirect()->back()->with('success', __('Update successfully'));
    }

    public function changePassword(Request $request)
    {
        $data = [
            'breadcrumbs' => [
                [
                    'name' => __('Setting'),
                    'url' => route("user.profile.index")
                ],
                [
                    'name' => __('Change Password'),
                    'class' => 'active'
                ]
            ],
            'page_title' => __("Change Password"),
        ];
        return view('User::frontend.changePassword', $data);
    }

    public function changePasswordUpdate(Request $request)
    {
        // if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
        //     // The passwords matches
        //     return redirect()->back()->with("error", __("Your current password does not matches with the password you provided. Please try again."));
        // }
        // if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
        //     //Current password and new password are same
        //     return redirect()->back()->with("error", __("New Password cannot be same as your current password. Please choose a different password."));
        // }
        $request->validate([
            // 'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with('success', __('Password changed successfully !'));
    }

    public function bookingsDetails(Request $request)
    {
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            $content_title = $type . ' - Bookings';
            $page_title = $type . ' - Bookings';
            switch ($type) {
                case 'archived':
                    $content_title = 'Booking Archived';
                    $page_title = 'Booking Archived';
                    break;
                case 'scheduled':
                    $content_title = 'Booking Scheduled';
                    $page_title = 'Booking Scheduled';
                    break;
                case 'pending':
                    $content_title = 'Pending Bookings';
                    $page_title = 'Pending Bookings';
                    break;
                case 'history':
                    $content_title = 'Booking History';
                    $page_title = 'Booking History';
                    break;
                case 'all':
                    $content_title = 'All Bookings';
                    $page_title = 'All Bookings';
                    break;
                default:
                    break;
            }
        } else {
            $type = 'all';
            $content_title = 'All Bookings';
            $page_title = 'All Bookings';
        }


        $space_categories = Attributes::where('service', 'space')->get();
        $cities = Location::select('id', 'name')->get();
        $ids = Booking::select('id')->where('customer_id', Auth::id())->where('is_archive', '!=', 1)->get();

        return view('User::frontend.bookingHistory', compact('page_title', 'content_title', 'space_categories', 'cities', 'ids', 'type'));
    }

    public function bookingcheckinoutsettings(Request $request)
    {
        $page_title = 'Booking CheckIn CheckOut Settings';
        return view('User::frontend.bookingCheckInoutsettings', compact('page_title'));
    }

    public function mainSearch(Request $request)
    {
        $title = request()->title;
        $ids = Space::where('title', 'like', "%{$title}%")->pluck('id')->toArray();
        $bookings = Booking::whereIn('object_id', $ids)->get();

        $html = '';
        if ($bookings != '') {
            foreach ($bookings as $booking) {
                $html .= '  <p class="bold">Pages Search Results: <span class="overlay-suggestions"></span></p>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="d-flex m-t-15">';
                $html .= '<div class="thumbnail-wrapper d48 circular bg-success text-white ">
                                <img width="36" height="36" src="' . $booking->service->image_url . '"
                                     data-src="' . $booking->service->image_url . '"
                                     data-src-retina="' . $booking->service->image_url . '" alt="">
                            </div>
                         ';
                $html .= ' <div class="p-l-10">
                                <h5 class="no-margin ">
                                <span class="semi-bold result-name">' . $booking->service->title . '</span>
                                </h5>
                               <p class="small-text hint-text">via john smith</p>
                            </div>
                            </div>
                        </div>';
            }
        }
        return response()->json($html);
    }

    public function favourites()
    {
        $page_title = 'User Favourites';
        return view('User::frontend.favourites', compact('page_title'));
    }

    public function favourites_datatable(Request $request)
    {
        $user_id = Auth::id();

        $searchFilters = request()->input('search_query');
        $searchFilters = CodeHelper::cleanArray($searchFilters);

        $spaceIds = AddToFavourite::where('user_id', $user_id)->pluck('object_id')->toArray();
        if ($spaceIds == null) {
            $spaceIds = [-1];
        }

        $query = Space::whereIn('id', $spaceIds);

        if (array_key_exists('city', $searchFilters) && $searchFilters['city'] && $searchFilters['city'] != "") {
            $query->where('city', 'LIKE', '%' . $searchFilters['city'] . '%');
        }

        if (array_key_exists('category', $searchFilters) && $searchFilters['category'] != '') {
            $category_id = $searchFilters['category'];
            $space_ids = SpaceTerm::where('term_id', $category_id)->whereIn('target_id', $spaceIds)->pluck('target_id')->toArray();
            $query->whereIn('id', $space_ids);
        }

        $space_categories = Attributes::where('service', 'space')->get();

        $catIds = [];
        foreach ($space_categories[0]->terms as $category) {
            $catIds[] = $category->id;
        }

        BaseModel::buildFilterQuery($query, [
            'q' => ['id', 'title', 'alias'],
            'city',
            'review_score'
        ]);

        // CodeHelper::debugQuery($query);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('idLink', function ($model) {
                return '<a href="' . route('space.detail', $model->slug) . '">' . $model->id . '</a>';
            })
            ->addColumn('title', function ($model) {
                return '<a target="_blank" href="' . route('space.detail', $model->slug) . '">' . $model->title . '</a>';
            })
            ->addColumn('category', function ($model) use ($catIds) {
                $spaceTerms = SpaceTerm::where('target_id', $model->id)->whereIn('term_id', $catIds)->get();
                if (count($spaceTerms) > 3) {
                    return 'Multi';
                } elseif (count($spaceTerms) > 1) {
                    $category = [];
                    foreach ($spaceTerms as $spaceTerm) {
                        $category[] = $spaceTerm->term->name;
                    }
                    return implode(", ", $category);
                } else {
                    return "-";
                }
            })
            ->addColumn('actions', function ($row) {
                $buttons = [
                    'remove' => ['url' => route('user.favourite.remove', [$row->id])],
                ];
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['actions', 'idLink', 'title'])
            ->make(true);
    }

    public function removeFavourite(Request $request)
    {
        AddToFavourite::where('user_id', Auth::id())->where('object_id', $request->id)->delete();
        return redirect()->back()->with('success', 'Space has been removed from Favourite');
    }

    public function redirectLogin(Request $request)
    {
        $redirectUrl = $request->query('redirect');
        $redirectUrl = trim($redirectUrl);
        // dd($redirectUrl);
        if ($redirectUrl != null) {
            Session::put('afterLoginRedirect', $redirectUrl);
        }
        return redirect()->to('login');
    }

    public function userLoginAs($id)
    {
        Auth::loginUsingId($id, TRUE);
        return redirect()->to('login');
    }

    public function userLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $messages = [
            'email.required' => __('Email is required field'),
            'email.email' => __('Email invalidate'),
            'password.required' => __('Password is required field'),
        ];
        if (ReCaptchaEngine::isEnable() and setting_item("user_enable_login_recaptcha")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $errors = new MessageBag(['message_error' => __('Please verify the captcha')]);
                return response()->json([
                    'error' => true,
                    'messages' => $errors
                ], 200);
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ], 200);
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            if (
                Auth::attempt([
                    'email' => $email,
                    'password' => $password
                ], $request->has('remember'))
            ) {
                if (in_array(Auth::user()->status, ['blocked'])) {
                    Auth::logout();
                    $errors = new MessageBag(['message_error' => __('Your account has been blocked')]);
                    return response()->json([
                        'error' => true,
                        'messages' => $errors,
                        'redirect' => false
                    ], 200);
                }

                $platform = $request->input('platform') ?? 'web';
                Session::put('platform', $platform);

                if ($platform && $platform == "mobile") {
                    return response()->json([
                        'error' => false,
                        'messages' => false,
                        'redirect' => url('m/home')
                    ], 200);
                } else {
                    return response()->json([
                        'error' => false,
                        'messages' => false,
                        'redirect' => CodeHelper::getAfterLoginRedirectUrl($request)
                    ], 200);
                }
            } else {
                $errors = new MessageBag(['message_error' => __('Username or password incorrect')]);
                return response()->json([
                    'error' => true,
                    'messages' => $errors,
                    'redirect' => false
                ], 200);
            }
        }
    }

    public function userRegister(Request $request)
    {
        $rules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'string'
            ],
        ];
        $messages = [
            'email.required' => __('Email is required field'),
            'email.email' => __('Email invalidate'),
            'password.required' => __('Password is required field'),
            'first_name.required' => __('The first name is required field'),
            'last_name.required' => __('The last name is required field'),
        ];
        if (ReCaptchaEngine::isEnable() and setting_item("user_enable_register_recaptcha")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $errors = new MessageBag(['message_error' => __('Please verify the captcha')]);
                return response()->json([
                    'error' => true,
                    'messages' => $errors
                ], 200);
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ], 200);
        } else {

            $ref = $request->input('ref');

            $user = \App\User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => $request->input('publish', 'publish'),
                'phone' => $request->input('phone'),
            ]);

            // event(new UserReferredEvent($ref, $user));

            //  try {
            //event(new Registered($user));
            //     event(new SendMailUserRegistered($user));
            //     } catch (Exception $exception) {

            //        Log::warning("SendMailUserRegistered: " . $exception->getMessage());
            //    }

            Auth::loginUsingId($user->id);

            $register_as = $request->input('register_as');
            if ($register_as == 'host') {
                $user->assignRole('vendor');
            } else {
                $user->assignRole('customer');
            }

            $platform = $request->input('platform') ?? 'web';
            Session::put('platform', $platform);

            $mustVerify = setting_item('enable_verify_email_register_user');
            if ($mustVerify == 1) {
                $user->sendEmailUserVerificationNotification($register_as);
                $user->sendEmailWelcomeNotification($register_as);
                $user->sendEmailRegisteredNotification($register_as);
                $user->sendEmailRegisteredAdminNotification($register_as);
            }

            if ($platform == 'mobile') {
                return response()->json([
                    'error' => false,
                    'messages' => false,
                    'redirect' => url('m/home')
                ], 200);
            } else {
                return response()->json([
                    'error' => false,
                    'messages' => false,
                    'redirect' => CodeHelper::getAfterLoginRedirectUrl($request)
                ], 200);
            }
        }
    }

    public function cancelBooking(Request $request)
    {
        Booking::where('id', $request->booking_id)->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', __('Order successfully canceled.'));
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255'
        ]);
        $check = Subscriber::withTrashed()->where('email', $request->input('email'))->first();
        if ($check) {
            if ($check->trashed()) {
                $check->restore();
                return $this->sendSuccess([], __('Thank you for subscribing'));
            }
            return $this->sendError(__('You are already subscribed'));
        } else {
            $a = new Subscriber();
            $a->email = $request->input('email');
            $a->first_name = $request->input('first_name');
            $a->last_name = $request->input('last_name');
            $a->save();

            event(new UserSubscriberSubmit($a));

            return $this->sendSuccess([], __('Thank you for subscribing'));
        }
    }

    public function upgradeVendor(Request $request)
    {
        $user = Auth::user();
        $vendorRequest = VendorRequest::query()->where("user_id", $user->id)->where("status", "pending")->first();
        if (!empty($vendorRequest)) {
            return redirect()->back()->with('warning', __('You have just done the become vendor request, please wait for the Admin\'s approved'));
        }
        // check vendor auto approved
        $vendorAutoApproved = setting_item('vendor_auto_approved');
        $dataVendor['role_request'] = setting_item('vendor_role');
        if ($vendorAutoApproved) {
            if ($dataVendor['role_request']) {
                $user->assignRole($dataVendor['role_request']);
            }
            $dataVendor['status'] = 'approved';
            $dataVendor['approved_time'] = now();
        } else {
            $dataVendor['status'] = 'pending';
        }
        $vendorRequestData = $user->vendorRequest()->save(new VendorRequest($dataVendor));
        try {
            event(new NewVendorRegistered($user, $vendorRequestData));
        } catch (Exception $exception) {
            Log::warning("NewVendorRegistered: " . $exception->getMessage());
        }
        return redirect()->back()->with('success', __('Request vendor success!'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect(app_get_locale(false, '/'));
    }

    public function enquiryReport(Request $request)
    {
        $this->checkPermission('enquiry_view');
        $user_id = Auth::id();
        $rows = $this->enquiryClass::where("vendor_id", $user_id)
            ->whereIn('object_model', array_keys(get_bookable_services()))
            ->orderBy('id', 'desc');
        $data = [
            'rows' => $rows->paginate(5),
            'statues' => $this->enquiryClass::$enquiryStatus,
            'has_permission_enquiry_update' => $this->hasPermission('enquiry_update'),
            'breadcrumbs' => [
                [
                    'name' => __('Enquiry Report'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Enquiry Report"),
        ];
        return view('User::frontend.enquiryReport', $data);
    }

    public function enquiryReportBulkEdit($enquiry_id, Request $request)
    {
        $status = $request->input('status');
        if (!empty($this->hasPermission('enquiry_update')) and !empty($status) and !empty($enquiry_id)) {
            $query = $this->enquiryClass::where("id", $enquiry_id);
            $query->where("vendor_id", Auth::id());
            $item = $query->first();
            if (!empty($item)) {
                $item->status = $status;
                $item->save();
                return redirect()->back()->with('success', __('Update success'));
            }
            return redirect()->back()->with('error', __('Enquiry not found!'));
        }
        return redirect()->back()->with('error', __('Update fail!'));
    }

    //getting booking details
    public function singleBookingDetails($id)
    {
        $user = Auth::user();
        $page_title = "Booking Detail";
        //$booking = Booking::where('id', $id)->first();
        $booking = DB::table('bravo_bookings')->where('id', $id)->first();
        $booking = Booking::where('id', $booking->id)->first();
        // echo '<pre>';
        // print_r($booking);
        // die;
        $space = Space::where('id', $booking->object_id)->first();
        $customer = User::where('id', $booking->customer_id)->first();
        $guestReview = Review::where('review_by', 'guest')->where('reference_id', $booking->id)->first();
        $hostReview = Review::where('review_by', 'host')->where('reference_id', $booking->id)->first();
        $payments = CodeHelper::getBookingTransactions($booking->id);


        if ($this->hasPermission('dashboard_vendor_access')) {
            return view('User::frontend.bookingDetail-host', compact('booking', 'page_title', 'space', 'customer', 'guestReview', 'hostReview', 'payments'));
        } else {
            return view('User::frontend.bookingDetail', compact('booking', 'page_title', 'space', 'customer', 'guestReview', 'hostReview', 'payments'));
        }
    }

    public function redirectDashboard()
    {
        if ($this->hasPermission('dashboard_vendor_access')) {
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    //user dashboard for customer
    public function userDashboard()
    {
        if (auth()->user()->isVendor()) {
            return redirect()->to('/user/dashboard');
        }
        $yearStartDate = date('Y-01-01') . " 00:00:00";
        $page_title = "User Dashboard";
        $bookings = Booking::where('customer_id', Auth::id())->orderBy('id', 'DESC')->whereDate('start_date', '>=', Carbon::now())->take(3)->get();
        $planBookings = [];
        return view('User::frontend.user_dashboard', compact('bookings', 'page_title', 'planBookings'));
    }

    public function userCalendar()
    {
        $userID = Auth::id();
        $yearStartDate = date('Y-01-01') . " 00:00:00";
        $page_title = "My Calendar";
        $bookings = [];
        $planBookings = [];


        $id = null;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        $spaceIds = Booking::where('object_model', 'space')->where(function ($query) use ($userID) {
            $query->where('customer_id', $userID)->orWhere('vendor_id', $userID);
        })->whereIn('status', [
                    Constants::BOOKING_STATUS_BOOKED,
                    Constants::BOOKING_STATUS_CHECKED_IN,
                    Constants::BOOKING_STATUS_CHECKED_OUT,
                    Constants::BOOKING_STATUS_COMPLETED
                ])->groupBy('object_id')->pluck('object_id')->toArray();

        if ($spaceIds == null) {
            $spaceIds = [-1];
        }

        $userSpaces = Space::whereIn('id', $spaceIds)->get();

        return view('User::frontend.calendar', compact('bookings', 'page_title', 'planBookings', 'userSpaces', 'id'));
    }

    public function vendorDashboardData()
    {
        $yearStartDate = date('Y-01-01') . " 00:00:00";
        $planBookingsModels = Booking::where('vendor_id', Auth::id())->orderBy('id', 'DESC')->whereDate('start_date', '>', $yearStartDate)->get();
        $planBookings = [];
        if ($planBookingsModels != null) {
            foreach ($planBookingsModels as $planBookingsModel) {
                $service = $planBookingsModel->service;
                $planBookingsModel = json_decode(json_encode($planBookingsModel), true);
                $planBookingsModel['service'] = $service;
                $planBookings[] = $planBookingsModel;
            }
        }
        return response()->json(['bookings' => $planBookings]);
    }

    public function userDashboardData()
    {
        $yearStartDate = date('Y-01-01') . " 00:00:00";
        $planBookingsModels = Booking::where('customer_id', Auth::id())->orderBy('id', 'DESC')->whereDate('start_date', '>', $yearStartDate)->get();
        $planBookings = [];
        if ($planBookingsModels != null) {
            foreach ($planBookingsModels as $planBookingsModel) {
                $service = $planBookingsModel->service;
                $planBookingsModel = json_decode(json_encode($planBookingsModel), true);
                $planBookingsModel['service'] = $service;
                $planBookings[] = $planBookingsModel;
            }
        }
        return response()->json(['bookings' => $planBookings]);
    }

    public function moveBooking(Request $request)
    {
        $postData = $request->all();
        $bookingId = $postData['bookingId'];
        $user = Auth::user();
        $bookingModel = null;
        if ($user->isVendor()) {
            $bookingModel = Booking::where('vendor_id', $user->id)->where('id', $bookingId)->first();
        } else {
            $bookingModel = Booking::where('customer_id', $user->id)->where('id', $bookingId)->first();
        }
        if ($bookingModel != null) {
            $bookingModel->start_date = date('Y-m-d H:i:s', strtotime($postData['startTime']));
            $bookingModel->end_date = date('Y-m-d H:i:s', strtotime($postData['endTime']));
            // print_r($bookingModel);die;
            if ($bookingModel->save()) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to Rescheduled.']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to Reschedule']);
        }
    }

    public function getBooking(Request $request)
    {
        $booking = Booking::where('id', $request->id)->first();
        $book_status = $booking->statusText();
        $book_class = $booking->statusClass();
        $html = '   <div class="view-port clearfix event-side-quick-view" id="eventFormController">
                                    <div class="view bg-white">
                                        <div class="scrollable">
                                            <div class="p-l-15 p-r-20 p-t-20">
                                                <a class="pg-icon text-color link pull-right closeQuickViewBox" href="javascript:;">close</a>
                                                <h4 class="m-b-5 m-t-0" id="event-date">' . date('F d, Y', strtotime($booking->start_date)) . '</h4>
                                                <div class="m-b-20 fs-14 text-center">
                                                    <div class="status-btn ' . $book_class . '">' . $book_status . '</div>
                                                </div>
                                            </div>
                                            <div class="p-t-15">
                                                <div class="book-details">
                                                    <table class="table">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="4" class="td-id text-uppercase" id="b_id">
                                                                Booking
                                                                #' . $booking->id . '
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td  colspan="4" class="w-20">
                                                                <div class="bookingLeftSidebarPImg">
                                                                <span class="thumbnail-wrapper circular inline">
                                                                  <img
                                                                      src="' . $booking->vendor->getAvatarUrl() . '"
                                                                      alt="host"
                                                                      data-src="' . $booking->vendor->getAvatarUrl() . '"
                                                                      data-src-retina="' . $booking->vendor->getAvatarUrl() . '"
                                                                      width="45" height="45">
                                                                </span>
                                                                <span>' . $booking->vendor->name . '</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w-20">
                                                                <span class="material-icons" data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Arrival Date">
                                                                  flight_land
                                                                  </span>
                                                            </td>
                                                            <td class="w-40">' . date('F d, Y', strtotime($booking->start_date)) . '</td>
                                                            <td class="w-20">
                                                                <span class="material-icons" data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Arrival Time">
                                                                  access_time
                                                                  </span>
                                                            </td>
                                                            <td class="w-40">' . date('g:i A', strtotime($booking->start_date)) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w-20">
                                                                <span class="material-icons" data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Departure Date">
                                                                  flight_takeoff
                                                                  </span>
                                                            </td>
                                                            <td class="w-40">' . date('F d, Y', strtotime($booking->end_date)) . '</td>
                                                            <td class="w-20">
                                                                <span class="material-icons" data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="Departure Time">
                                                                  access_time
                                                                  </span>
                                                            </td>
                                                            <td class="w-40">' . date('g:i A', strtotime($booking->end_date)) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w-20">
                                                                <span class="material-icons" data-toggle="tooltip"
                                                                      data-placement="top" title=""
                                                                      data-original-title="No of Guests">
                                                                  person
                                                                  </span>
                                                            </td>
                                                            <td colspan="3" class="w-40 bookingTableOptionsVal">' . $booking->total_guests . ' Guests</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="w-20 fs-16 font-weight-bold text-uppercase bg-complete-lighter text-right">
                                                                Total
                                                            </td>
                                                            <td colspan="3"
                                                                class="w-40 fs-18 bg-complete-lighter text-right"
                                                                style="font-weight: 900;">$' . number_format((float) $booking->total, 2, '.', '') . '
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="p-l-15 p-r-15 p-t-20 text-center">
                                                <a href="' . route('user.single.booking.detail', $booking->id) . '"
                                                   class="text-black">
                                                    <button aria-label="" id="eventSave"
                                                            class="btn btn-complete btn-block">View Full Booking Details
                                                    </button>
                                                </a>
                                                <!--button aria-label="" id="eventDelete" class="btn btn-default m-l-10"><i class="pg-icon">trash</i></button-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="view bg-white">
                                        <div class="navbar navbar-default navbar-sm">
                                            <div class="navbar-inner">
                                                <a href="javascript:;" class="action p-l-10 link text-color"
                                                   data-navigate="view" data-view-port="#eventFormController"
                                                   data-view-animation="push-parrallax">
                                                    <i class="pg-icon">chevron_left</i>
                                                </a>
                                                <div class="view-heading">
                                                    <span class="font-montserrat text-uppercase fs-13">Alerts</span>
                                                </div>
                                                <a href="#" class="action p-r-10 pull-right link text-color">
                                                    <i class="pg-icon">search</i>
                                                </a>
                                            </div>
                                        </div>
                                        <p class="p-l-30 p-r-30 p-t-30"> This is a Demo</p>
                                    </div>
                                </div>';

        return response()->json(['html' => $html]);
    }

    public function bookingsSearch(Request $request)
    {
        $user_id = Auth::id();
        $space_ids = Booking::orderBy('id', 'DESC')->where('customer_id', $user_id)->where('is_archive', '!=', 1)->pluck('object_id')->toArray();
        $bookings = Space::whereIn('id', $space_ids)->where('title', 'like', "%{$request->title}%")->get();

        return response()->json(['bookings' => $bookings]);
    }

    public function clients()
    {
        $data = [
            'page_title' => __("Clients"),
        ];
        return view('User::frontend.clients', $data);
    }

    public function crmRedirect()
    {
        $redirect = "";
        if (isset($_GET['redirect'])) {
            $redirect = trim($_GET['redirect']);
        }
        if ($redirect == null) {
            $redirect = "/admin/mail/inbox";
        }
        $loginRequest = LoginRequests::createLoginToken(Auth::id(), 'crm');
        return redirect(env('CRM_PANEL_URl') . "/auth-login?token=" . $loginRequest->token . "&redirect=" . $redirect);
    }

    public function inbox()
    {
        return redirect()->route('crmRedirect', ['redirect' => '/admin/mail/inbox']);
        $data = [
            'page_title' => __("Inbox"),
        ];
        return view('User::frontend.inbox', $data);
    }

    public function calendar()
    {
        $data = [
            'page_title' => __("Calendar"),
        ];
        return view('User::frontend.calendar', $data);
    }

    public function reports()
    {
        $data = [
            'page_title' => __("Reports"),
        ];
        return view('User::frontend.reports', $data);
    }

    public function topClients()
    {
        $data = [
            'page_title' => __("Top Clients"),
        ];
        return view('User::frontend.top-clients', $data);
    }

    public function couponTransactions()
    {
        $data = [
            'page_title' => __("Coupon Transactions"),
        ];
        return view('User::frontend.coupon-transactions', $data);
    }

    public function transactions()
    {
        $data = [
            'page_title' => __("Transactions"),
        ];
        return view('User::frontend.transactions', $data);
    }

    public function withdraw()
    {
        $data = [
            'page_title' => __("Withdraw"),
        ];
        return view('User::frontend.withdraw', $data);
    }

    public function publicProfile($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        // dd($user);
        return redirect()->to('/profile/' . $user->user_name);
    }

    public function publicProfileContactSubmit(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

        CRMHelper::createLead(
            $user->id,
            $request->input('subject'),
            $request->input('message'),
            $request->input('name'),
            $request->input('email')
        );

        // Mail::to($user->email)->send(
        //     new NotificationEmail(
        //         'Contact Request Received',
        //         'You have received contact request, details are below.</br></br></br></br>
        // 		<b>Subject: </b>' . $request->input('subject') . '.</br>
        // 		<b>Message: </b>' . $request->input('message') . '.</br>',
        //     )
        // );

        EmailHelper::sendPostOfficeEmail(
            EmailTemplateConstants::SIMPLE_NOTIFICATION,
            $user->email,
            [
                'emailBoxContent' => "<p>Name: ".$request->input('name')."</p>
                <p>Email: ".$request->input('email')."</p>
                <p>Subject: ".$request->input('subject')."</p>
                <p>".$request->input('message')."</p>"
            ]
        );

        return redirect()->to('/profile/' . $user->user_name)->with('success', 'Contact request has been sent');
    }

}
