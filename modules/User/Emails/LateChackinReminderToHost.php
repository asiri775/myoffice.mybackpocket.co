<?php
/**
 * Created by PhpStorm.
 * User: dunglinh
 * Date: 6/4/19
 * Time: 20:49
 */

 namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class LateChackinReminderToHost extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $messageContent;
    public $template;

    public function __construct($message, $template)
    {
        $this->messageContent = $message;
        $this->subject = "Reminder Email for Guest Not Checked In";
        $this->template = $template;
    }

    public function build() 
    {
        return $this->subject($this->subject)->view('User::emails.late-chackin-reminder-to-host');

    }
  }