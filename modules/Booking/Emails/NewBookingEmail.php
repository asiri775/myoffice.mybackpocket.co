<?php
namespace Modules\Booking\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Booking\Models\Booking;

class NewBookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking; 
    protected $email_type;
    public $subject;
    public $template;
    public $name;
    public $service;

    public function __construct(Booking $booking, $to = 'admin', $subject = null, $template)
    {
        if ($subject == null) {
            $subject = "Booking #" . $booking->id;
        }
        $this->booking = $booking;
        $this->email_type = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->service = $booking->service;
        if ($to == 'vendor') {
            $this->name = $booking->vendor->nameOrEmail ?? '';
        } else if ($to == 'customer') {
            $this->name = $booking->first_name;
        } else {
            $this->name = $to;
        }
    }

    public function build()
    {
        if ($this->email_type == 'customer') {
            return $this->subject($this->subject)->view('Booking::emails.new-booking-customer');
        } else {
            return $this->subject($this->subject)->view('Booking::emails.new-booking');
        }
    }

}
