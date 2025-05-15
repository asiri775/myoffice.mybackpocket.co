<?php
/**
 * Created by PhpStorm.
 * User: dunglinh
 * Date: 6/4/19
 * Time: 20:49
 */

namespace Modules\Contact\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Contact\Models\Contact;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $message;
    public $attachmentData;

    public function __construct($subject, $message, $attachmentData = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->attachmentData = $attachmentData;
    }

    public function build()
    {
        foreach ($this->attachmentData as $attachmentItem) {
            $this->attach($attachmentItem['path'], $attachmentItem['options']);
        }
        return $this->subject($this->subject)->view('Contact::emails.notification-email')->with([
            'subject' => $this->subject,
            'messageData' => $this->message
        ]);
    }
}
