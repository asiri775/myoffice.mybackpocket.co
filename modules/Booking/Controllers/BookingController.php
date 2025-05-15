<?php

namespace Modules\Booking\Controllers;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Notifications\AdminChannelServices;
use App\User;
use DebugBar\DebugBar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

//use Modules\Booking\Events\VendorLogPayment;
use Modules\Booking\Events\BookingCreatedEvent;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Events\EnquirySendEvent;
use Modules\Booking\Events\SetPaidAmountEvent;
use Modules\Core\Models\Settings;
use Modules\Tour\Models\TourDate;

use Modules\Space\Models\Space;
use Modules\User\Events\UserReferredEvent;
use PHPMailer\PHPMailer\PHPMailer;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Enquiry;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\User\Events\SendMailUserRegistered;

class BookingController extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests;

    protected $booking;
    protected $enquiryClass;
    protected $bookingInst;


    public function __construct()
    {
        $this->booking = Booking::class;
        $this->enquiryClass = Enquiry::class;
    }

    protected function validateCheckout($code)
    {
        $enableGuest = is_enable_guest_checkout();
        $enableGuest = true;

        if (!$enableGuest and !Auth::check()) {
            $currenturl = url()->current();
            return redirect()->route('auth.redirectLogin', ['redirect' => $currenturl]);
            // return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }

        $booking = $this->booking::where('code', $code)->first();

        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }

        if ($booking->customer_id == null) {
            $booking->customer_id = Auth::id();
            $booking->save();
        }

        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            abort(404);
        }
        return true;
    }

    public function checkout($code)
    {
        $res = $this->validateCheckout($code);
        if ($res !== true)
            return $res;

        $fullUrl = CodeHelper::getCurrentUrl();

        if (!Auth::check()) {
            Session::put('afterLoginRedirect', $fullUrl);
        }

        $booking = $this->bookingInst;

        if (!in_array($booking->status, ['draft', 'unpaid'])) {
            return redirect('/');
        }

        $is_api = request()->segment(1) == 'api';

        $bookingPriceInfo = CodeHelper::getBookingPriceInfo($booking);
        $booking = CodeHelper::assignSpacePricingToBooking($booking, $bookingPriceInfo);
        $booking->save();

        $data = [
            'page_title' => __('Checkout'),
            'booking' => $booking,
            'service' => $booking->service,
            'gateways' => $this->getGateways(),
            'user' => Auth::user(),
            'is_api' => $is_api
        ];

        $platform = request()->platform;
        Session::put('platform', $platform);

        if ($platform == 'mobile') {
            return view('Booking::frontend/checkout_mobile', $data);
        } else {
            return view('Booking::frontend/checkout', $data);
        }
    }

    public function checkStatusCheckout($code)
    {
        $booking = $this->booking::where('code', $code)->first();
        $data = [
            'error' => false,
            'message' => '',
            'redirect' => ''
        ];
        if (empty($booking)) {
            $data = [
                'error' => true,
                'redirect' => url('/')
            ];
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            $data = [
                'error' => true,
                'redirect' => url('/')
            ];
        }
        if (!in_array($booking->status, ['draft', 'unpaid'])) {
            $data = [
                'error' => true,
                'redirect' => url('/')
            ];
        }
        return response()->json($data, 200);
    }

    protected function validateDoCheckout()
    {

        $request = \request();

        // if (!is_enable_guest_checkout() and !Auth::check()) {
        //     return $this->sendError(__("You have to login in to do this"), [
        //         'errorCode' => 'loginRequired'
        //     ])->setStatusCode(401);
        // }

        // if (Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user') == 1) {
        //     return $this->sendError(__("You have to verify email first"), ['url' => url('/email/verify')]);
        // }
        /**
         * @param Booking $booking
         */
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $code = $request->input('code');

        $booking = $this->booking::where('code', $code)->first();
        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            abort(404);
        }
        return true;
    }

    public function doCheckout(Request $request)
    {
        /**
         * @var $booking Booking
         * @var $user User
         */
        $res = $this->validateDoCheckout();
        if ($res !== true)
            return $res;
        $user = auth()->user();

        $booking = $this->bookingInst;

        if (!in_array($booking->status, ['draft', 'unpaid'])) {
            return $this->sendError('', [
                'url' => $booking->getDetailUrl()
            ]);
        }
        $service = $booking->service;
        if (empty($service)) {
            return $this->sendError(__("Service not found"));
        }

        $is_api = request()->segment(1) == 'api';

        /**
         * Google ReCapcha
         */
        if (!$is_api and ReCaptchaEngine::isEnable() and setting_item("booking_enable_recaptcha")) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                return $this->sendError(__("Please verify the captcha"));
            }
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required',
            'term_conditions' => 'required',
        ];
        $how_to_pay = $request->input('how_to_pay', '');
        $credit = $request->input('credit', 0);
        $payment_gateway = $request->input('payment_gateway');

        // require payment gateway except pay full
        if (empty(floatval($booking->deposit)) || $how_to_pay == 'deposit' || !auth()->check()) {
            $rules['payment_gateway'] = 'required';
        }

        if (auth()->check()) {
            if ($credit > $user->balance) {
                return $this->sendError(__("Your credit balance is :amount", ['amount' => $user->balance]));
            }
        } else {
            // force credit to 0 if not login
            $credit = 0;
        }

        $rules = $service->filterCheckoutValidate($request, $rules);
        if (!empty($rules)) {
            $messages = [
                'term_conditions.required' => __('Please Accept the Terms and Conditions'),
                'payment_gateway.required' => __('Payment gateway is required field'),
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return $this->sendError('', ['errors' => $validator->errors()]);
            }
        }

        $userModel = null;

        $email = $request->input('email');

        if (!Auth::check()) {
            if ($request->input('password') == null || $request->input('confirm_password') == null) {
                return $this->sendError(__("Please fill password fields"), [
                    'errorCode' => 'loginRequired'
                ])->setStatusCode(401);
            } else {

                if ($request->input('password') != $request->input('confirm_password')) {
                    return $this->sendError(__("Confirm password should be same as New Password"), [
                        'errorCode' => 'loginRequired'
                    ])->setStatusCode(401);
                }


                $userModel = User::where('email', $email)->first();
                if ($userModel !== null) {
                    return $this->sendError(__("You already have an account, please login first to continue"), [
                        'errorCode' => 'loginRequired'
                    ])->setStatusCode(401);
                } else {

                    //
                    $referralCode = null;
                    if ($request->input('referral_code') && $request->input('referral_code') != null) {
                        $referralCode = $request->input('referral_code');
                    }

                    //create account
                    $userModel = User::create([
                        'name' => $request->input('first_name') . " " . $request->input('last_name'),
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password'), ),
                        'status' => 'publish'
                    ]);
                    $userModel->assignRole('customer');
                    $userModel->save();

                    try {
                        if ($referralCode != null) {
                            event(new UserReferredEvent($referralCode, $userModel));
                        }
                        event(new Registered($user));
                        // event(new SendMailUserRegistered($userModel));
                    } catch (Exception $exception) {
                        Log::warning("SendMailUserRegistered: " . $exception->getMessage());
                    }

                    $userModel = User::where('email', $email)->first();
                }
            }

            if ($userModel != null) {
                Auth::login($userModel, true);
                // return $this->sendSuccess([
                //     'errorCode' => 'failedssss'
                // ], __("You payment has been processed successfully"));
            }
        } else {
            $userModel = User::where('email', $email)->first();
        }

        if ($userModel == null) {
            return $this->sendError(__("Failed to process, please contact support"), [
                'errorCode' => 'failed'
            ])->setStatusCode(401);
        }

        // if (Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user') == 1) {
        //     return $this->sendError(__("You have to verify email first"), ['url' => url('/email/verify')]);
        // }

        $wallet_total_used = credit_to_money($credit);
        if ($wallet_total_used > $booking->total) {
            $credit = money_to_credit($booking->total, true);
            $wallet_total_used = $booking->total;
        }

        if ($res = $service->beforeCheckout($request, $booking)) {
            return $res;
        }

        $phoneNo = $request->input('phone');

        $otherUserWithMobileNo = User::where('phone', $phoneNo)->where('id', '!=', $userModel->id)->first();
        if ($otherUserWithMobileNo != null) {
            return $this->sendError(__("The phone has already been taken."), [
                'errorCode' => 'failed'
            ])->setStatusCode(401);
        }

        // Normal Checkout
        $booking->customer_id = $userModel->id;

        $booking->first_name = $request->input('first_name');
        $booking->last_name = $request->input('last_name');
        $booking->email = $request->input('email');
        $booking->phone = $phoneNo;
        $booking->address = $request->input('address_line_1');
        $booking->address2 = $request->input('address_line_2');
        $booking->city = $request->input('city');
        $booking->state = $request->input('state');
        $booking->zip_code = $request->input('zip_code');
        $booking->country = $request->input('country');
        $booking->customer_notes = $request->input('customer_notes');
        $booking->gateway = $payment_gateway;
        $booking->wallet_credit_used = floatval($credit);
        $booking->wallet_total_used = floatval($wallet_total_used);
        $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);

        // If using credit
        if ($booking->wallet_total_used > 0) {
            if ($how_to_pay == 'full') {
                $booking->deposit = 0;
                $booking->pay_now = $booking->total;
            } elseif ($how_to_pay == 'deposit') {
                // case guest input credit more than "pay deposit" need to pay
                // Ex : pay deposit 10$ but guest input 20$ -> minus credit balance = 10$
                if ($wallet_total_used > $booking->deposit) {
                    $wallet_total_used = $booking->deposit;
                    $booking->wallet_total_used = floatval($wallet_total_used);
                    $booking->wallet_credit_used = money_to_credit($wallet_total_used, true);
                }
            }

            $booking->pay_now = max(0, $booking->pay_now - $wallet_total_used);
            $booking->paid = $booking->wallet_total_used;
        } else {
            if ($how_to_pay == 'full') {
                $booking->deposit = 0;
                $booking->pay_now = $booking->total;
            }
        }

        $gateways = get_payment_gateways();
        if ($booking->pay_now > 0) {
            $gatewayObj = new $gateways[$payment_gateway]($payment_gateway);
            if (!empty($rules['payment_gateway'])) {
                if (empty($gateways[$payment_gateway]) or !class_exists($gateways[$payment_gateway])) {
                    return $this->sendError(__("Payment gateway not found"));
                }
                if (!$gatewayObj->isAvailable()) {
                    return $this->sendError(__("Payment gateway is not available"));
                }
            }
        }

        if ($booking->wallet_credit_used && auth()->check()) {
            try {
                $transaction = $user->withdraw($booking->wallet_credit_used, [
                    'wallet_total_used' => $booking->wallet_total_used
                ]);
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }

            $transaction->booking_id = $booking->id;
            $transaction->save();
            $booking->wallet_transaction_id = $transaction->id;
        }
        $booking->save();



        //        event(new VendorLogPayment($booking));

        if (Auth::check()) {
            $user = Auth::user();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address_line_1');
            $user->address2 = $request->input('address_line_2');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->zip_code = $request->input('zip_code');
            $user->country = $request->input('country');
            $user->save();
        }

        $booking->addMeta('locale', app()->getLocale());
        $booking->addMeta('how_to_pay', $how_to_pay);

        if ($res = $service->afterCheckout($request, $booking)) {
            return $res;
        }

        if ($booking->pay_now > 0) {
            try {
                $url = $gatewayObj->process($request, $booking, $service, true);
                return $this->sendSuccess([
                    'url' => $url
                ], __("You are being redirected to the payment gateway, please wait.."));
            } catch (Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        } else {
            // if ($booking->paid < $booking->total) {
            //     $booking->payment_status = Constants::PAYMENT_PARTIALLY_PAID;
            // } else {
            //     $booking->payment_status = Constants::PAYMENT_PAID;
            // }

            // if (!empty($booking->coupon_amount) and $booking->coupon_amount > 0 and $booking->total <= 0) {
            //     $booking->payment_status = Constants::PAYMENT_PAID;
            // }

            if ($booking->payable_amount <= 0) {
                $booking->markAsPaid();
            }

            //booking thank you email
            $id = $booking->id;
            $booking = Booking::where('id', $id)->first();
            $receiveremail = $booking->email;
            $starttime = $booking->start_time;
            $endtime = $booking->end_time;
            $bookingtotal = $booking->total;
            $bookinginvoicelink = url('user.booking.invoice') . "/" . $id;
            $spaceid = $booking->object_id;
            $space = Space::where('id', $spaceid)->first();
            $spacename = $space->title;
            $emailtext = "Dear " . $booking->total . "</br>";
            $emailtext = $emailtext . "Thank you for booking the Space with MyOffice.ca.  The details are given below:";
            $emailtext = $emailtext . "<table border='1'>";
            $emailtext = $emailtext . "<tr><td>Space Name :</td><td>" . $spacename . "</td></tr>";
            $emailtext = $emailtext . "<tr><td>Booking Start Time :</td><td>" . $starttime . "</td></tr>";
            $emailtext = $emailtext . "<tr><td>Booking End Time :</td><td>" . $endtime . "</td></tr>";
            $emailtext = $emailtext . "<tr><td>Booking Total :</td><td>$" . $bookingtotal . "</td></tr>";
            $emailtext = $emailtext . "<tr><td>Booking Invoice :</td><td>$" . $bookinginvoicelink . "</td></tr>";
            $emailtext = $emailtext . "</table><br/><br/>";

            $settings = Settings::where('group', 'email')->get();
            for ($i = 0; $i < sizeof($settings); $i++) {
                if ($settings[$i]['name'] == "email_driver") {
                    $emaildriver = $settings[$i]['val'];
                }
                if ($settings[$i]['name'] == "email_host") {
                    $emailhost = $settings[$i]['val'];
                }
                if ($settings[$i]['name'] == "email_port") {
                    $emailport = $settings[$i]['val'];
                }
                if ($settings[$i]['name'] == "email_encryption") {
                    $emailencryption = $settings[$i]['val'];
                }
                if ($settings[$i]['name'] == "email_username") {
                    $emailusername = $settings[$i]['val'];
                }
                if ($settings[$i]['name'] == "email_password") {
                    $emailuserpassword = $settings[$i]['val'];
                }
            }


            // try {
            //     $mail = new PHPMailer(true);     // Passing `true` enables exceptions
            //     // Email server settings
            //     $mail->SMTPDebug = 0;
            //     $mail->isSMTP();
            //     $mail->Host =$emailhost;             //  smtp host

            //     $mail->SMTPAuth = true;
            //     $mail->Username = $emailusername;
            //     $mail->Password = $emailuserpassword;       // sender password
            //     $mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
            //     $mail->Port = $emailport;                          // port - 587/465
            //     $mail->setFrom('admin@myoffice.ca','MyOffice');
            //     $mail->addAddress($receiveremail);
            //     $mail->addReplyTo('admin@myoffice.ca','MyOffice');

            //     $mail->isHTML(true);                // Set email content format to HTML
            //     //	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
            //     $checkinurl =  url('user/booking-details/') . "/" . $id;
            //     $mail->Subject  = "Reminder Email for Guest Not Checked In";
            //     $body      = "Dear" . $booking->first_name . ", <br/><br/>";
            //     $body      = $body . $emailtext;
            //     $body    = $body . "Thank you :" . "<br/><br/>";
            //     $body    = $body . "Management MyOfice Team";
            //     $mail->Body = $body;
            //     $mail->AltBody = $body;

            //     if (!$mail->send()) {
            //         //		return redirect()->back()->with('error', __('Error in Sending Thank you Email.'));
            //     } else {
            //         //		return redirect()->back()->with('success', __('Thank you Email Sent.'));
            //     }
            // } catch (\Exception $e) {
            //     //	    return redirect()->back()->with('error', __('Error in Sending Thank you Email.'));
            // }
            //booking thank you email

            event(new BookingCreatedEvent($booking));
            return $this->sendSuccess([
                'url' => $booking->getDetailUrl()
            ], __("You payment has been processed"));
        }
    }

    public function confirmPayment(Request $request, $gateway)
    {
        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->confirmPayment($request);
    }

    public function cancelPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        return $gatewayObj->cancelPayment($request);
    }

    /**
     * @param Request $request
     * @return string json
     * @todo Handle Add To Cart Validate
     *
     */
    public function addToCart(Request $request)
    {
        // if (!is_enable_guest_checkout() and !Auth::check()) {
        //     return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        // }
        // if (Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user') == 1) {
        //     return $this->sendError(__("You have to verify email first"), ['url' => url('/email/verify')]);
        // }

        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'service_type' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $service_type = $request->input('service_type');
        $service_id = $request->input('service_id');
        $allServices = get_bookable_services();
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];

        $service = $module::find($service_id);

        if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
            return $this->sendError(__('Service not found'));
        }
        if (!$service->isBookable()) {
            return $this->sendError(__('Service is not bookable'));
        }

        if (Auth::id() == $service->create_user) {
            return $this->sendError(__('You cannot book your own service'));
        }

        return $service->addToCart($request);
    }

    public function getGateways()
    {

        $all = get_payment_gateways();
        $res = [];
        foreach ($all as $k => $item) {
            if (class_exists($item)) {
                $obj = new $item($k);
                if ($obj->isAvailable()) {
                    $res[$k] = $obj;
                }
            }
        }
        return $res;
    }

    public function detail(Request $request, $code)
    {
        if (!is_enable_guest_checkout() and !Auth::check()) {
            return $this->sendError(__("You have to login in to do this"))->setStatusCode(401);
        }

        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            abort(404);
        }

        if ($booking->status == 'draft') {
            return redirect($booking->getCheckoutUrl());
        }
        if (!is_enable_guest_checkout() and $booking->customer_id != Auth::id()) {
            abort(404);
        }
        $data = [
            'page_title' => __('Booking Details'),
            'booking' => $booking,
            'service' => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }


        // $booking->status = 'scheduled';
        // $booking->paid = $booking->total;
        // $booking->is_paid = 1;
        // $booking->save();

        // if ($booking->payment != null) {
        //     $booking->payment->status = 'paid';
        //     $booking->payment->amount = $booking->total;
        //     $booking->payment->currency = "CAD";
        //     $booking->payment->save();
        // }

        return view('Booking::frontend/detail', $data);
    }

    public function exportIcal($service_type, $id)
    {
        if (empty($service_type) or empty($id)) {
            return $this->sendError(__('Service not found'));
        }
        \Debugbar::disable();
        $allServices = get_bookable_services();
        $allServices['room'] = 'Modules\Hotel\Models\HotelRoom';
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];

        $path = '/ical/';
        $fileName = 'booking_' . $service_type . '_' . $id . '.ics';
        $fullPath = $path . $fileName;

        $content = $this->booking::getContentCalendarIcal($service_type, $id, $module);
        Storage::disk('uploads')->put($fullPath, $content);
        $file = Storage::disk('uploads')->get($fullPath);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        echo $file;
    }

    public function addEnquiry(Request $request)
    {
        $rules = [
            'service_id' => 'required|integer',
            'service_type' => 'required',
            'enquiry_name' => 'required',
            'enquiry_email' => [
                'required',
                'email',
                'max:255',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }

        if (setting_item('booking_enquiry_enable_recaptcha')) {
            $codeCapcha = trim($request->input('g-recaptcha-response'));
            if (empty($codeCapcha) or !ReCaptchaEngine::verify($codeCapcha)) {
                return $this->sendError(__("Please verify the captcha"));
            }
        }

        $service_type = $request->input('service_type');
        $service_id = $request->input('service_id');
        $allServices = get_bookable_services();
        if (empty($allServices[$service_type])) {
            return $this->sendError(__('Service type not found'));
        }
        $module = $allServices[$service_type];
        $service = $module::find($service_id);
        if (empty($service) or !is_subclass_of($service, '\\Modules\\Booking\\Models\\Bookable')) {
            return $this->sendError(__('Service not found'));
        }
        $row = new $this->enquiryClass();
        $row->fill([
            'name' => $request->input('enquiry_name'),
            'email' => $request->input('enquiry_email'),
            'phone' => $request->input('enquiry_phone'),
            'note' => $request->input('enquiry_note'),
        ]);
        $row->object_id = $request->input("service_id");
        $row->object_model = $request->input("service_type");
        $row->status = "pending";
        $row->vendor_id = $service->create_user;
        $row->save();
        event(new EnquirySendEvent($row));
        return $this->sendSuccess([
            'message' => __("Thank you for contacting us! We will be in contact shortly.")
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPaidAmount(Request $request)
    {
        $rules = [
            'remain' => 'required|integer',
            'id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }


        $id = $request->input('id');
        $remain = floatval($request->input('remain'));

        if ($remain < 0) {
            return $this->sendError(__('Remain can not smaller than 0'));
        }

        $booking = Booking::where('id', $id)->first();
        if (empty($booking)) {
            return $this->sendError(__('Booking not found'));
        }

        $booking->pay_now = $remain;
        $booking->paid = floatval($booking->total) - $remain;
        event(new SetPaidAmountEvent($booking));
        if ($remain == 0) {
            $booking = $booking->markAsCompleted();
            //            $booking->sendStatusUpdatedEmails();
            event(new BookingUpdatedEvent($booking));
        }

        $booking->save();

        return $this->sendSuccess([
            'message' => __("You booking has been changed successfully")
        ]);
    }

    public function updateMassBooking(Request $request)
    {
        $user = auth()->user();
        $ids = explode(',', $request->input('ids'));
        $ids = CodeHelper::cleanArray($ids);
        $status = $request->input('status');
        if (count($ids) > 0) {
            Booking::where('vendor_id', $user->id)->whereIn('id', $ids)->update(['status' => $status]);
            return redirect()->back()->with('success', 'Booking(s) has been updated');
        } else {
            return redirect()->back()->with('error', 'Select atleast one booking');
        }
    }


    public function updateSingleBooking(Request $request)
    {
        $user = auth()->user();
        $postData = $_POST;
        $bookingId = $postData['id'];
        if ($bookingId != null) {
            $booking = Booking::where('id', $bookingId)->first();
            if ($booking != null) {
                $space = Space::where('id', $booking->object_id)->first();

                if (array_key_exists('from', $postData)) {
                    $start_date = $postData['from'];
                    $startHour = $postData['from_time'];

                    $end_date = $postData['to'];
                    $endHour = $postData['to_time'];

                    $startDate = $start_date . " " . $startHour . ":01 ";
                    $startDate = date('Y-m-d H:i:s', strtotime($startDate));

                    $toDate = $end_date . " " . $endHour . ":00 ";
                    $toDate = date('Y-m-d H:i:s', (strtotime($toDate) - 0));

                    $bookingPriceInfo = CodeHelper::getSpacePrice($space, $startDate, $toDate);

                    $booking->start_date = $startDate;
                    $booking->end_date = $toDate;

                    $booking = CodeHelper::assignSpacePricingToBooking($booking, $bookingPriceInfo);
                    // dd($booking);
                }

                if (array_key_exists('status', $postData)) {
                    $booking->status = $postData['status'];
                }
                if (array_key_exists('guest', $postData)) {
                    $booking->total_guests = $postData['guest'];
                }

                $booking->save();
                return redirect()->back()->with('success', 'Booking has been updated');
            } else {
                return redirect()->back()->with('error', 'Booking not found');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Request');
        }
    }
}
