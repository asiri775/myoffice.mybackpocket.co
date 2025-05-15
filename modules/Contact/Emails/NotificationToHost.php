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

class NotificationToHost extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email;
    public $phone;
    public $messageText;
    public $space;
    public $template;

    public function __construct($name, $email, $phone, $messageText, $space, $template)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->messageText = $messageText;
        $this->space = $space;
        $this->template = $template;
    }

    public function build()
    {
        return $this->subject(__('#' . $this->space->id . ' - ' . $this->space->title . ': Inquiry', ['site_name' => setting_item('site_title')]))
            ->view('Contact::emails.notification-to-host');
    }
}
