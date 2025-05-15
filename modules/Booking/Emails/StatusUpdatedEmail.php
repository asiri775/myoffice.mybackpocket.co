<?php
namespace Modules\Booking\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Booking\Models\Booking;

class StatusUpdatedEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $oldStatus;
    public $subject;
    public $template;
    public $name;
    public $service;
    protected $email_type;
    

    public function __construct(Booking $booking,$to = 'admin',$subject, $template)
    {
        $this->booking = $booking;
        $this->email_type = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->service=$booking->service;
        if($to=='vendor'){
            $this->name=$booking->vendor->nameOrEmail ?? '';
        }
        else if($to=='customer'){
            $this->name=$booking->first_name;
        }
        else {
            $this->name=$to;
        }
    }

    public function build()
    {

        return $this->subject($this->subject)->view('Booking::emails.status-updated-booking');
    }
}
