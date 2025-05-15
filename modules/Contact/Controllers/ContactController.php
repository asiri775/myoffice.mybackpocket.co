<?php
namespace Modules\Contact\Controllers;

use App\Helpers\Constants;
use App\Helpers\CRMHelper;
use App\Helpers\EmailHelper;
use App\Helpers\EmailTemplateConstants;
use App\Helpers\ReCaptchaEngine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Matrix\Exception;
use Modules\Contact\Emails\NotificationToAdmin;
use Modules\Contact\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $data = [
            'page_title' => __("Contact Page")
        ];
        return view('Contact::index', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'email' => [
                'required',
                'max:255',
                'email'
            ],
            'name' => ['required'],
            'subject' => ['required'],
            'message' => ['required']
        ]);
        /**
         * Google ReCapcha
         */
        if (ReCaptchaEngine::isEnable()) {
            $codeCapcha = $request->input('g-recaptcha-response');
            if (!$codeCapcha or !ReCaptchaEngine::verify($codeCapcha)) {
                $data = [
                    'status' => 0,
                    'message' => __('Please verify the captcha'),
                ];
                return response()->json($data, 200);
            }
        }
        $row = new Contact($request->input());
        $row->status = 'sent';
        if ($row->save()) {

            $title = $request->input('subject');
            if ($title == null) {
                $title = $request->input('title');
                if ($title == null) {
                    $title = "Contact request";
                }
            }

            CRMHelper::createLead(
                Constants::ADMIN_USER_ID,
                $title,
                $request->input('message'),
                $request->input('name'),
                $request->input('email')
            );

            $this->sendEmail($row);
            $data = [
                'status' => 1,
                'message' => __('Thank you for contacting us! We will get back to you soon'),
            ];

            if ($request->ajax()) {
                return response()->json($data, 200);
            } else {
                return redirect()->back()->with('success', 'Your request has been submitted');
            }
        }
    }

    protected function sendEmail($contact)
    {
        if ($admin_email = setting_item('admin_email')) {
            try {

                EmailHelper::sendPostOfficeEmail(
                    EmailTemplateConstants::SIMPLE_NOTIFICATION,
                    $admin_email,
                    [
                        'emailBoxContent' => "<p>Name: $contact->name</p>
                        <p>Email: $contact->email</p>
                        <p>Topic: $contact->topic</p>
                        <p>Subject: $contact->subject</p>
                        <p>$contact->message</p>"
                    ]
                );

                // Mail::to($admin_email)->send(new NotificationToAdmin($contact));
            } catch (Exception $exception) {
                Log::warning("Contact Send Mail: " . $exception->getMessage());
            }
        }
    }

    public function t()
    {
        return new NotificationToAdmin(Contact::find(1));
    }
}
