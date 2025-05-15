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


class TestEmail extends Mailable
{
    use Queueable;
    public $subject;

    public function __construct()
    {
        $this->subject = "Test Email";
    }

    public function build() 
    {
        return $this->subject($this->subject)->view('User::emails.test-email');

    }
  }