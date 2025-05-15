<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordToken extends Mailable
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

    public $subject;
    public $template;
    public $link;

    public function __construct($token, $user, $subject, $template)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->template = $template;
        $this->link = route('password.reset', ['token' => $token]);
    }

    public function build()
    {

        return $this->subject($this->subject)->view('User::emails.forgotPassword');
    }
}
