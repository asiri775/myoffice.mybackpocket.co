<?php
/**
 * Created by PhpStorm.
 * User: dunglinh
 * Date: 6/4/19
 * Time: 20:49
 */

namespace Modules\Contact\Emails;

use App\Models\EmailSubject;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Contact\Models\Contact;

class NotificationToAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $contact;  
    public $subject;
    public $template;

    public function __construct(Contact $contact)
    {
        $EmailSubject = EmailSubject::where('token', 'mycon001')->first();
        $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
        $this->contact = $contact;
        $this->subject = $EmailSubject['subject'];
        $this->template = $EmailTemplate;
    }

    public function build()
    {
        return $this->subject($this->subject)->view('Contact::emails.notification')->with([
            'contact' => $this->contact,
            'template' => $this->template
        ]);
    }

}
