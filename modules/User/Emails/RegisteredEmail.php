<?php

    namespace Modules\User\Emails;

    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class RegisteredEmail extends Mailable
    {
        use Queueable, SerializesModels;  
        public $subject;
        public $template;
        public $to_address;
        public $first_name;
        public $last_name; 
 
        public function __construct(User $user, $subject, $template, $to_address)
        {
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->subject = $subject;
            $this->template = $template;
            $this->to_address = $to_address;
        }

        public function build()
        {
            //$subject = $this->user->getDisplayName().' has registered.';
            return $this->subject($this->subject)->view('User::emails.registered');
        }
    }
