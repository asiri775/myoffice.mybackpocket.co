<?php

	namespace Modules\User\Emails;

	use App\User;
	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;

	class EmailUserVerifyRegister extends Mailable
	{
		use Queueable, SerializesModels;

		const CODE = [
			'first_name'    => '[first_name]',
			'last_name'     => '[last_name]',
			'name'          => '[name]',
			'email'         => '[email]',
			'button_verify' => '[button_verify]',
		];

		public $user;
		public $url;
        public $subject;
        public $template;

		public function __construct(User $user, $subject, $template, $url)
		{
			$this->user = $user;
			$this->url = $url;
			$this->subject = $subject;
            $this->template = $template;
		}


		public function build()
		{
			return $this->subject($this->subject)->view('User::emails.verify-registered');
		}

		
	}
