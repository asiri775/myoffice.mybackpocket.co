<?php

	namespace Modules\User\Emails;

	use App\User;
	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;

	class EmailNewUserAdmin extends Mailable
	{
		use Queueable, SerializesModels;

		const CODE = [
			'first_name'    => '[first_name]',
			'last_name'     => '[last_name]',
		];

		public $user;
        public $subject;
        public $template;

		public function __construct(User $user, $subject, $template) 
		{
			$this->user = $user;
			$this->subject = $subject->subject;
            $this->template = $template;  
		}


		public function build()
		{
			return $this->subject($this->subject)->view('User::emails.new-user-admin');
		}

		
	}
