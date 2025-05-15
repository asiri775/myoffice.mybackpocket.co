<?php

    namespace Modules\User\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\User\Emails\RegisteredEmail;
    use App\Models\EmailSubject;
    use App\Models\EmailTemplate;
    use Modules\User\Events\SendMailUserRegistered;
    use App\Helpers\EmailTemplateConstants;
    use Modules\User\Models\User;

    class SendMailUserRegisteredListen
    {
        /**
         * Create the event listener.
         *
         * @return void
         */
        public $user;

        const CODE = [
            'first_name' => '[first_name]',
            'last_name'  => '[last_name]',
            'name'       => '[name]',
            'email'      => '[email]',
            'button_verify' => '[button_verify]',

        ];

        public function __construct(User $user)
        {
            $this->user = $user;
        }

        /**
         * Handle the event.
         *
         * @param Event $event
         * @return void
         */
        public function handle(SendMailUserRegistered $event)
        {
            
            if($event->user->locale){
                $old = app()->getLocale();
                app()->setLocale($event->user->locale);
            }
 
            if (!empty(setting_item('enable_mail_user_registered'))) {
                $EmailSubject = EmailSubject::where('token', USER__WELCOME_EMAIL)->first();
                $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
                Mail::to($event->user->email)->send(new RegisteredEmail($event->user,  $EmailSubject['subject'],  $EmailTemplate, 'customer'));
            }

            if(!empty($old)){
                app()->setLocale($old);
            }

            // if (!empty(setting_item('admin_email') and !empty(setting_item_with_lang('admin_enable_mail_user_registered',app()->getLocale())))) {
            //     $EmailSubject = EmailSubject::where('token', 'l25jk427')->first();
            //     $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
            //     Mail::to(setting_item('admin_email'))->send(new RegisteredEmail($event->user, $EmailTemplate['content'], 'admin'));
            // }


        }

    }
