<?php
namespace Modules\Booking\Models;
namespace Modules\Space\Models;
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Booking\Models\Booking;
use Modules\Space\Models\Space;
use Modules\Space\Models\PostalCodesAndTimeZone;
use Modules\Space\Models\Timezones_Reference;
use Modules\User\Models\User;
use Modules\Core\Models\Settings;
use Twilio\Rest\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
// use PHPMailer\PHPMailer\PHPMailer;
use Modules\User\Emails\LateChackinReminderToHost;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;

class lateCheckInNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'late:checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
	
		 $bookings = Booking::all();
         $timezone="Canada/Eastern";        
         $systemdate=\Carbon\Carbon::now()->tz($timezone)->toDateTimeString();
         $systemdate=substr($systemdate, 0, -3);
        foreach($bookings as $booking) 
            {
                if (($booking->status !='completed') and 
                    ($booking->status !='processing') and 
                    ($booking->status !='draft') and 
                    ($booking->status !='unpaid') and 
                    ($booking->status !='completed') and 
                    ($booking->status !='partial_payment') and 
                    ($booking->status !='complete') and 
                    ($booking->status !='cancelled' ) and
                    ($booking->status !='paid' ) and
                    ($booking->status !='confirmed' )and (strtotime($systemdate)<=strtotime(date("Y-m-d H:i",strtotime($booking->end_date)))))
                {
                    // remove this condition //
                    //remove this condition so that it will send email to all satisfying condition.
                    
               // if ($booking->id==150)
                //{
                        $id=$booking->id;
                        $userid = $booking->vendor_id;
                        $user = User::where('id', $userid)->first();
                        echo "Host City :".$user->city;
                        echo "Host Country :".$user->country;
                        echo "Booking Id :".$booking->id."\n";
                        echo "Booking Status :".$booking->status."\n";
                        $space = Space::where('id', $booking->object_id)->first();

                        $zipcode= $space->zip;
                        $city=$space->city;
                        $state=$space->state;
                        $country=$space->country;
                        $postalcodedata=PostalCodesAndTimeZone::Where('province_abbr',$state)->orWhere('postalcode',$zipcode)->orWhere('city',strtoupper($city))->first();
                        if (!empty($postalcodedata))
                        {
                        $timezonecode=$postalcodedata->timezone;
                        $timezonedata=Timezones_Reference::where('id',$timezonecode)->first();
                        $timezone=$timezonedata->php_time_zones;
                        }
                        else
                        {
                        $timezone="Canada/Eastern";         
                        }
                        $systemdate=\Carbon\Carbon::now()->tz($timezone)->toDateTimeString();
                        $systemdate=substr($systemdate, 0, -3);
                        echo "system date:". $systemdate."\n";
                        echo "booking_start date:". date("Y-m-d H:i", strtotime($booking->start_date))."\n";
                        echo "booking_end date:". date("Y-m-d H:i",strtotime($booking->end_date))."\n";
                        

                        if ($space->host_checkin_reminder!="")
                        {
                                echo "--------------------------------------------------"."\n";
                                $reminderlatecheckin = $space->host_checkin_reminder;
                                echo "reminder late checkin time :".$reminderlatecheckin."\n"; 
                                if ($reminderlatecheckin == '5 Minutes')
                                {
                                    $new_late_checkin_date = date('Y-m-d H:i',strtotime($booking->start_date.' +5 Minutes '));
                                }
                                if ($reminderlatecheckin == '15 Minutes')
                                {
                                    $new_late_checkin_date = date('Y-m-d H:i',strtotime($booking->start_date.' +15 Minutes '));
                                }
                                if ($reminderlatecheckin == '30 Minutes')
                                {
                                    $new_late_checkin_date = date('Y-m-d H:i',strtotime($booking->start_date.' +30 Minutes '));
                                }
                        
                                echo "calcualted reminder_time_for_late_checkin:". $new_late_checkin_date."\n";
                                //email to host reminder late checkin   
                                    $message=$space->host_reminder_text;
                                    //$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
                                    $checkinurl =  url('user/booking-details/')."/".$id;
                                    $message=str_replace("{FirstName}",$booking->first_name,$message);
                                    $message=str_replace("{bookingno}",$booking->id,$message);
                                    $message=str_replace("{listingname}",$space->title,$message);
                                    $message=str_replace("{arrivaltime}",$booking->start_date,$message);
                                    $message=str_replace("{departuretime}",$booking->end_date,$message);
                                    $message=str_replace("{bookinglink}",$checkinurl,$message);
                                    $message=str_replace("{cancellationfee}",'',$message);
                                    /*
                                            Dear {FirstName},
                                            
                                            Your Guest {guestname} has not yet Checked IN for Booking #{bookingno}. 
                                            
                                            Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
                                            
                                            Booking Details :
                                            
                                            Listing Name            : {listingname}
                                            Arrival Time            : {arrivaltime}
                                            Departure Date and Time : {departuretime}
                                            Cancellation Fee        :

                                            <>Contact Guest | Manual Check IN | Edit Booking   2023-12-16 18:45                 
                                    */

                                    echo "System date".$systemdate."  Late date".$new_late_checkin_date."\n"; 
                                       
                                       
                                  
                                if (strtotime($systemdate)==strtotime($new_late_checkin_date)) 
                                {
                                        $spacecreateuser=$space->create_user;
                                        $spaceuser=User::where('id',$spacecreateuser)->first();
                                        $receiveremail=$spaceuser->email;

                                        $EmailSubject_customer = EmailSubject::where('token', 'h2er5kla')->first();
                                        $EmailTemplate_customer = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject_customer['id'])->first();
                                        
                                        try {
                                            Mail::to($receiveremail)->send(new LateChackinReminderToHost($message,$EmailTemplate_customer));
                                        } catch (Exception $e)  {
                                            echo "error in sending late checkin host reminder"."\n";
                                        }
                                        
                                //host reminder email  for late checkin
                                }
                                else
                                {
                                    echo "systemdate and latecheckin date do not match"."\n";
                                }       
                        }       


                        
            
                }
                    //sending prearrival reminder sms
                    
                    
                }   
            //}// all records checking for scheduled booking
	
		
	}
}
