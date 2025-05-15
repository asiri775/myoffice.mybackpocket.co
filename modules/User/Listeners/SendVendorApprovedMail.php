<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\RegisteredEmail;
use Modules\User\Emails\VendorApprovedEmail;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Events\VendorApproved;
use Modules\User\Models\User;
use Modules\Vendor\Models\VendorRequest;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;
class SendVendorApprovedMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $user;
    public $vendorRequest;

    const CODE = [
        'first_name' => '[first_name]',
        'last_name'  => '[last_name]',
        'name'       => '[name]',
        'email'      => '[email]',
    ];

    public function __construct(User $user, VendorRequest $vendorRequest)
    {
        $this->user = $user;
        $this->vendorRequest = $vendorRequest;
        //
    }

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(VendorApproved $event)
    {
        if($event->user->locale){
            $old = app()->getLocale();
            app()->setLocale($event->user->locale);
        }
        $url=route('vendor.dashboard');
        $EmailSubject = EmailSubject::where('token', 'm25bl14')->first();
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        Mail::to($event->user->email)->send(new VendorApprovedEmail($event->user,$EmailSubject['subject'],  $EmailTemplate, $url));

        if(!empty($old)){
            app()->setLocale($old);
        }

    }

}