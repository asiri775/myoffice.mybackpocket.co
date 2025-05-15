<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostOfficeMail extends Mailable
{
    use Queueable, SerializesModels;


    public $subject;
    public $html;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $html)
    {
        $this->subject = $subject;
        $this->html = $html;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('mail.post-office', [
            'content' => $this->html
        ]);
    }
}
