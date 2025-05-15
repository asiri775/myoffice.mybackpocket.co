<?php

    namespace Modules\User\Emails;

    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class EmailUserRegistered extends Mailable
    {
        use Queueable, SerializesModels;  
        public $subject;
        public $template;
        public $user;
        public $url;
 
        public function __construct(User $user, $subject, $template,$url)
        {
            $this->subject = $subject->subject;
            $this->template = $template;
            $this->user = $user;
            $this->url = $url;
        }

        public function build()
        {
            //$subject = $this->user->getDisplayName().' has registered.';
            return $this->subject($this->subject)->view('User::emails.registered');
        }
    }
