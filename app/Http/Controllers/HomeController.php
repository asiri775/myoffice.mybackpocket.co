<?php

namespace App\Http\Controllers;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Helpers\CRMHelper;
use App\Helpers\EmailHelper;
use App\Helpers\EmailTemplateConstants;
use App\Models\TaskData;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Booking\Models\Booking;
use Modules\Contact\Emails\NotificationToHost;
use Modules\Core\Models\Attributes;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\LocationCategory;
use Modules\Media\Models\MediaFile;
use Modules\Page\Models\Page;
use Modules\News\Models\NewsCategory;
use Modules\News\Models\Tag;
use Modules\News\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Contact\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\Emails\NotificationToAdmin;
use Modules\Space\Models\Space;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;
use Modules\Space\Models\SpaceTerm;
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $home_page_id = setting_item('home_page_id');
        $home_hotel_id = setting_item('home_hotel_id');

        if ($home_hotel_id && $row = Hotel::where("id", $home_hotel_id)->where("status", "publish")->first()) {
            $translation = $row->translateOrOrigin(app()->getLocale());
            $hotel_related = [];
            $location_id = $row->location_id;
            if (!empty($location_id)) {
                $hotel_related = Hotel::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
            }
            $review_list = $row->getReviewList();
            $data = [
                'row' => $row,
                'translation' => $translation,
                'hotel_related' => $hotel_related,
                'location_category' => LocationCategory::where("status", "publish")->with('location_category_translations')->get(),
                'booking_data' => $row->getBookingData(),
                'review_list' => $review_list,
                'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
                'body_class' => 'is_single'
            ];
            $this->setActiveMenu($row);
            return view('Hotel::frontend.detail', $data);
        }
        if ($home_page_id && $page = Page::where("id", $home_page_id)->where("status", "publish")->first()) {

            $this->setActiveMenu($page);

            $translation = $page->translateOrOrigin(app()->getLocale());
            $seo_meta = $page->getSeoMetaWithTranslation(app()->getLocale(), $translation);
            $seo_meta['full_url'] = url("/");
            $seo_meta['is_homepage'] = true;
            $data = [
                'row' => $page,
                "seo_meta" => $seo_meta,
                'translation' => $translation
            ];
            return view('Page::frontend.detail', $data);
        }
        $model_News = News::where("status", "publish");
        $data = [
            'rows' => $model_News->paginate(5),
            'model_category' => NewsCategory::where("status", "publish"),
            'model_tag' => Tag::query(),
            'model_news' => News::where("status", "publish"),
            'breadcrumbs' => [
                ['name' => __('News'), 'url' => url("/news"), 'class' => 'active'],
            ],
            "seo_meta" => News::getSeoMetaForPageList()
        ];
        return view('News::frontend.index', $data);
    }

    public function checkConnectDatabase(Request $request)
    {
        $connection = $request->input('database_connection');
        config([
            'database' => [
                'default' => $connection . "_check",
                'connections' => [
                    $connection . "_check" => [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ],
                ],
            ],
        ]);

        try {
            DB::connection()->getPdo();
            $check = DB::table('information_schema.tables')->where("table_schema", "performance_schema")->get();
            if (empty($check) and $check->count() == 0) {
                return $this->sendSuccess(false, __("Access denied for user!. Please check your configuration."));
            }
            if (DB::connection()->getDatabaseName()) {
                return $this->sendSuccess(false, __("Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName()));
            } else {
                return $this->sendSuccess(false, __("Could not find the database. Please check your configuration."));
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function paymentError()
    {
        return view('errors.payment_error');
    }

    public function contactSubmit(Request $request)
    {
        $row = new Contact($request->input());
        $row->message = $request->input('notes');
        $row->status = 'sent';
        if ($row->save()) {
            $this->sendEmail($row);
            return redirect()->back()->with('success', 'Contact request has been sent');
        } else {
            die("not saved");
        }
    }

    public function contactHost(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'max:255'
            ],
            'subject' => [
                'required',
                'string',
                'max:255'
            ],
            'message' => [
                'required',
                'string'
            ],
            'space' => ['required'],
        ];
        $messages = [
            'name.required' => __('Name is required field'),
            'subject.required' => __('Subject is required field'),
            'phone.required' => __('Phone is required field'),
            'message.required' => __('Message is required field'),
            'email.required' => __('Email is required field'),
            'space.required' => __('Space is required field'),
            'email.email' => __('Email invalidate'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        } else {
            $name = $request->get('name');
            $phone = $request->get('phone');
            $email = $request->get('email');
            $message = $request->get('message');
            $subject = $request->get('subject');
            $space = $request->get('space');

            $space = Space::where('id', $space)->first();
            $user = User::where('id', $space->create_user)->first();

            try {

                CRMHelper::createLead(
                    $user->id,
                    $subject,
                    $message,
                    $name,
                    $email,
                    $phone,
                    [
                        'spaceId' => $space->id
                    ]
                );

                EmailHelper::sendPostOfficeEmail(
                    EmailTemplateConstants::SIMPLE_NOTIFICATION,
                    $user->email,
                    [
                        'emailBoxContent' => "<p>Name: $name</p>
                        <p>Email: $email</p>
                        <p>Phone No: $phone</p>
                        <p>$message</p>"
                    ]
                );

                // $EmailSubject = EmailSubject::where('token', 'sds616hk')->first();
                // $template = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();

                // Mail::to($user->email)->send(new NotificationToHost($name, $email, $phone, $message, $space, $template));

            } catch (Exception $exception) {
                dd($exception);
                Log::warning("Contact Send Mail: " . $exception->getMessage());
            }

            return $this->sendSuccess(__('Contact request sent successfully'));
        }
    }

    protected function sendEmail($contact)
    {
        if ($admin_email = setting_item('admin_email')) {
            try {
                Mail::to($admin_email)->send(new NotificationToAdmin($contact));
            } catch (Exception $exception) {
                Log::warning("Contact Send Mail: " . $exception->getMessage());
            }
        }
    }

    public function howWorksHost()
    {
        return view('site.how-works-host');
    }

    public function howWorksGuest()
    {
        return view('site.how-works-guest');
    }

    public function makeItCount()
    {
        return view('site.make-count');
    }

    public function contactThanks()
    {
        return view('site.contact-thanks');
    }

    public function downloadInvoice($code)
    {
        $booking = Booking::where('code', $code)->first();
        if ($booking == null) {
            return redirect()->back()->with('error', __('Booking not found.'));
        }

        $service = $booking->service;
        $page_title = __("Invoice");
        $download = true;

        $invoiceFileName = "MyOffice_InvoiceNo_" . $booking->id . ".pdf";

        $pdf = PDF::loadView('User::frontend.bookingInvoice', compact('booking', 'service', 'download'));
        if (isset($_GET['preview'])) {
            return $pdf->stream();
        }
        return $pdf->download($invoiceFileName);
    }

    public function cancelPendingBooking($code)
    {
        $booking = Booking::where('code', $code)->first();
        if ($booking != null) {
            $booking->delete();
        }
        return redirect(app_get_locale(false, '/'))->with('success', 'Booking has been cancelled');
    }

    public function checkin(Request $request)
    {
        $id = $request->id;
        $booking = Booking::where('id', $id)->first();
        $data = [
            'booking' => $booking,
            'page_title' => 'User Booking Checkin',
            'sms_prearrival_message' => '',
            'sms_arrival_message' => '',
            'sms_latecheckin_message' => '',
            'failed' => '',
            'success' => '',
            'error' => ''
        ];
        return view('User::frontend.bookingCheckIn', $data);
    }

    public function guestcheckin(Request $request)
    {
        $id = $request->id;
        $booking = Booking::where('id', $id)->firstOrFail();
        $space = Space::where('id', $booking->object_id)->firstOrFail();
        $data = [
            'booking' => $booking,
            'space' => $space,
            'page_title' => 'User Booking Checkin',
            'sms_prearrival_message' => '',
            'sms_arrival_message' => '',
            'sms_latecheckin_message' => '',
            'failed' => '',
            'success' => '',
            'error' => ''
        ];
        if ($booking->status == Constants::BOOKING_STATUS_CHECKED_IN) {
            return view('User::frontend.guestbookingCheckInResponse', $data);
        } elseif ($booking->status == Constants::BOOKING_STATUS_CHECKED_OUT) {
            return redirect()->to('/user/booking/guestcheckout/' . $booking->id);
        }
        return view('User::frontend.guestbookingCheckIn', $data);
    }

    public function guestcheckinpost(Request $request)
    {
        $id = $request->id;
        $booking = Booking::find($id);
        $space = Space::where('id', $booking->object_id)->firstOrFail();
        $booking->status = Constants::BOOKING_STATUS_CHECKED_IN;
        $booking->save();
        return redirect()->back();
        $data = [
            'booking' => $booking,
            'space' => $space,
            'page_title' => 'User Booking Checkin Response',
            'failed' => '',
            'success' => 'success',
            'error' => ''
        ];
        return view('User::frontend.guestbookingCheckInResponse', $data);
    }

    public function checkout(Request $request)
    {
        $id = $request->id;
        $booking = Booking::where('id', $id)->firstOrFail();
        $data = [
            'booking' => $booking,
            'page_title' => 'User Booking CheckOut',
            'sms_predeparture_message' => '',
            'sms_latecheckout_message' => '',
        ];
        return view('User::frontend.bookingCheckOut', $data);
    }

    public function guestcheckout(Request $request)
    {
        $id = $request->id;
        $booking = Booking::where('id', $id)->firstOrFail();
        $space = Space::where('id', $booking->object_id)->firstOrFail();
        $data = [
            'booking' => $booking,
            'space' => $space,
            'page_title' => 'User Booking CheckOut',
            'sms_predeparture_message' => '',
            'sms_latecheckout_message' => '',
        ];
        if ($booking->status == Constants::BOOKING_STATUS_CHECKED_OUT) {
            return view('User::frontend.guestbookingCheckOutResponse', $data);
        }
        return view('User::frontend.guestbookingCheckOut', $data);
    }

    public function guestcheckoutpost(Request $request)
    {
        $id = $request->id;
        $booking = Booking::find($id);
        $space = Space::where('id', $booking->object_id)->first();
        $booking->status = Constants::BOOKING_STATUS_CHECKED_OUT;
        $booking->save();
        return redirect()->back();
        $data = [
            'booking' => $booking,
            'space' => $space,
            'page_title' => 'User Booking CheckOut Response',
            'failed' => '',
            'success' => 'success',
            'error' => ''
        ];
        return view('User::frontend.guestbookingCheckOutResponse', $data);
    }

    public function bookingSmartView($code)
    {
        $booking = Booking::where('code', $code)->firstOrFail();
        $space = Space::where('id', $booking->object_id)->first();
        $data = [
            'booking' => $booking,
            'space' => $space,
            'page_title' => 'User Booking CheckOut Response',
            'failed' => '',
            'success' => 'success',
            'error' => ''
        ];
        return view('User::frontend.bookingSmartView', $data);
    }

    public function meProfile()
    {
        $user = auth()->user();
        if ($user != null) {
            return response()->json(['status' => 'loggedIn', 'data' => null, 'emailVerifiedAt' => $user->email_verified_at]);
        } else {
            return response()->json(['status' => 'loggedOut', 'data' => null, 'emailVerifiedAt' => null]);
        }
    }

    public function loadImages()
    {
        \Artisan::call('stock:load');
        return redirect()->back()->with('success', 'Executed successfully!');
    }

    public function reGenerateAllImages()
    {
        $task = TaskData::getOrCreateTask('regenerate_all_space_images');
        $task->markAsPending(0);
        return redirect()->to('/admin/module/space')->with('success', 'Images regenerated request send, it will be completed soon!');
    }

    public function nextAvailableBookingDate()
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $startHour = "10:00";
        $endHour = "11:00";

        $spaceId = isset($_GET['spaceId']) ? $_GET['spaceId'] : null;
        if ($spaceId != null) {
            $space = Space::find($spaceId);
            if ($space != null) {
                if ($space->available_from != null) {
                    $startHour = $space->available_from;
                }
                if ($space->available_to != null) {
                    $endHour = $space->available_to;
                }

            }
        }

        return response()->json([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startHour' => $startHour,
            'endHour' => $endHour
        ]);
    }

}
