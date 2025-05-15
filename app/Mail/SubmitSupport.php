<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmitSupport extends Mailable
{
    use Queueable, SerializesModels;


    public $data;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data , $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Help & Support')->view('pwa.email_template.support_mail' );
    }
}
