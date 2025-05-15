<?php

namespace Modules\User\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Events\VendorApproved;

class VendorApprovedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $subject;
    public $template;
    public $link;
    public $first_name;


    public function __construct($user, $subject, $template,$url)
    {
        $this->first_name = $user->first_name;
        $this->subject = $subject;
        $this->template = $template;
        $this->link= $url;
    }

    public function build()
    {
        return  $this->subject($this->subject)->view('User::emails.vendor-approved');
    }


}
