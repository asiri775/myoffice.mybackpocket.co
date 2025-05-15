<?php

    namespace Modules\User\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\User\Emails\RegisteredEmail;
    use App\Models\EmailSubject;
    use App\Models\EmailTemplate;
    use App\Helpers\EmailTemplateConstants;
    use App\Notifications\AdminChannelServices;
    use Modules\User\Events\NewVendorRegistered;

    class SendNotifyRegistered
    {

        public function handle(NewVendorRegistered $event)
        {

            if($event->user->locale){
                $old = app()->getLocale();
                app()->setLocale($event->user->locale);
            }
            
            $user = $event->user;
            $data = [
                'id' =>  $user->id,
                'name' =>  $user->display_name,
                'avatar' =>  $user->avatar_url,
                'link' => route('user.admin.upgrade'),
                'type' => 'user_upgrade_request',
                'message' => __(':name has requested to become a vendor', ['name' => $user->display_name])
            ];

            if (!empty(setting_item('enable_mail_user_registered'))) {
                $EmailSubject = EmailSubject::where('token', USER__WELCOME_EMAIL)->first();
                $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
                Mail::to($event->user->email)->send(new RegisteredEmail($event->user,  $EmailSubject['subject'],  $EmailTemplate, 'customer'));
            }

            $user->notify(new AdminChannelServices($data));
        }
    }
