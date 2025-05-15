<?php
namespace Modules\Booking\Models;
namespace Modules\Space\Models;
namespace App\Console\Commands;

use App\Helpers\Constants;
use Illuminate\Console\Command;
use Modules\Booking\Models\Booking;
use Modules\Space\Models\Space;
use Modules\Space\Models\PostalCodesAndTimeZone;
use Modules\Space\Models\Timezones_Reference;
use Modules\User\Models\User;
use Modules\Core\Models\Settings;
use Twilio\Rest\Client;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

class smscron extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sms:cron';

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

	public function getGuestCheckInUrl($id)
	{
		return url('user/booking/guestcheckin') . "/" . $id;
	}

	public function getGuestCheckOutUrl($id)
	{
		return url('user/booking/guestcheckout') . "/" . $id;
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{

		$bookings = Booking::whereIn('status', [
			Constants::BOOKING_STATUS_BOOKED,
			Constants::BOOKING_STATUS_CHECKED_IN,
			Constants::BOOKING_STATUS_CHECKED_OUT,
			Constants::BOOKING_STATUS_COMPLETED
		])->where('start_date', '>', date(Constants::PHP_DATE_FORMAT, strtotime("-7 days")))->get();
		foreach ($bookings as $booking) {
			if (
				($booking->status != 'completed') and
				($booking->status != 'processing') and
				($booking->status != 'draft') and
				($booking->status != 'unpaid') and
				($booking->status != 'completed') and
				($booking->status != 'partial_payment') and
				($booking->status != 'complete') and
				($booking->status != 'cancelled') and
				($booking->status != 'paid') and
				($booking->status != 'confirmed')
			) {
				// remove this condition //
				//remove this condition so that it will send sms to all satisfying condition.

				//	if ($booking->id==179)
				//	{

				$id = $booking->id;
				$userid = $booking->vendor_id;
				$user = User::where('id', $userid)->first();
				echo "Host City :" . $user->city;
				echo "Host Country :" . $user->country;
				echo "Booking Id :" . $booking->id . "<br/>";
				echo "Booking Status :" . $booking->status . "<br/>";
				$space = Space::where('id', $booking->object_id)->first();
				$settings = Settings::where('group', 'sms')->get();
				for ($i = 0; $i < sizeof($settings); $i++) {
					if ($settings[$i]['name'] == "sms_twilio_api_from") {
						$smstwiliofromno = $settings[$i]['val'];
					}
					if ($settings[$i]['name'] == "sms_twilio_account_sid") {
						$smstwilioaccountsid = $settings[$i]['val'];
					}
					if ($settings[$i]['name'] == "sms_twilio_account_token") {
						$smstwilioaccounttoken = $settings[$i]['val'];
					}
				}
				$zipcode = $space->zip;
				$city = $space->city;
				$state = $space->state;
				$country = $space->country;
				$postalcodedata = PostalCodesAndTimeZone::Where('province_abbr', $state)->orWhere('postalcode', $zipcode)->orWhere('city', strtoupper($city))->first();
				if (!empty($postalcodedata)) {
					$timezonecode = $postalcodedata->timezone;
					$timezonedata = Timezones_Reference::where('id', $timezonecode)->first();
					$timezone = $timezonedata->php_time_zones;
				} else {
					$timezone = "Canada/Eastern";
				}
				$systemdate = \Carbon\Carbon::now()->tz($timezone)->toDateTimeString();
				$systemdate = substr($systemdate, 0, -3);
				echo "system date:" . $systemdate . "<br/>";
				echo "booking_start date:" . date("m-d-Y H:i", strtotime($booking->start_date)) . "<br/>";
				echo "booking_end date:" . date("m-d-Y H:i", strtotime($booking->end_date)) . "<br/>";

				$receiverNumber = $booking->phone;

				if ($space->checkin_reminder_time != "") {
					echo "--------------------------------------------------" . "<br/>";
					$remindertimecheckin = $space->checkin_reminder_time;
					echo "checkinreminder time: " . $remindertimecheckin . "<br/>";

					if ($remindertimecheckin == '30 Minutes') {
						$new_booking_date = strtotime($booking->start_date . ' -30 Minutes ');
					}
					if ($remindertimecheckin == '1 Hour') {
						$new_booking_date = strtotime($booking->start_date . ' -1 Hour ');
					}
					if ($remindertimecheckin == '90 Minutes') {
						$new_booking_date = strtotime($booking->start_date . ' -90 Minutes ');
					}
					if ($remindertimecheckin == '2 Hours') {
						$new_booking_date = strtotime($booking->start_date . ' -2 Hours ');
					}

					echo "calculated_reminder_time_for_checking : " . $new_booking_date . "<br/>";

					//pre arrival check message.		
					echo PHP_EOL;
					echo "strtotime systemdate " . strtotime($systemdate).PHP_EOL;
					echo "strtotime new_booking_date " . $new_booking_date.PHP_EOL;

					if (strtotime($systemdate) == $new_booking_date) {
						echo "systemdate and checkin date matches." . "<br/>";
						$message1 = $space->prearrival_checkin_text;
						//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
						// $checkinurl =  url('user/booking-details/')."/".$id;
						$checkinurl = $this->getGuestCheckInUrl($id);

						$message1 = str_replace("{firstname}", $booking->first_name, $message1);
						$message1 = str_replace("{spacename}", $space->title, $message1);
						$message1 = str_replace("{checkintime}", $space->checkin_reminder_time, $message1);
						$message1 = str_replace("{bookinglink}", $checkinurl, $message1);
						try {
							$account_sid = $smstwilioaccountsid;
							$auth_token = $smstwilioaccounttoken;
							$twilio_number = $smstwiliofromno;
							$client = new Client($account_sid, $auth_token);
							$message = $client->messages->create(
								$receiverNumber,
								[
									'from' => $twilio_number,
									'body' => $message1
								]
							);
							if ($message->sid) {
								echo "success" . "<br/>";
							}
						} catch (Exception $e) {
							$error = "Error in Sending SMS" . "<br/>";
						}
					} else {
						echo "systemdate and new bookin date do not match" . "<br/>";

					}
					// pre arrival checkin message.

				}
				echo "--------------------------------------------------" . "<br/>";


				if ($space->checkout_reminder_time != "") {

					echo "--------------------------------------------------" . "<br/>";
					$remindertimecheckout = $space->checkout_reminder_time;
					echo "checkout reminder time :" . $remindertimecheckout . "<br/>";

					if ($remindertimecheckout == '15 Minutes') {
						$new_checkout_date = strtotime($booking->end_date . ' -15 Minutes ');
					}
					if ($remindertimecheckout == '30 Minutes') {
						$new_checkout_date = strtotime($booking->end_date . ' -30 Minutes ');
					}
					echo "systemdate :" . strtotime($systemdate);
					echo "calculated_reminder_time_for_checkout :" . $new_checkout_date . "<br/>";
					//reminder pre-departure prior booking end time 	

					if (strtotime($systemdate) == $new_checkout_date) {
						echo "<br/> systemdate and new checkout date matches";
						// $checkouturl =  url('user/booking-details/')."/".$id;
						$checkouturl = $this->getGuestCheckOutUrl($id);

						$message1 = $space->departure_reminder_text;
						$message1 = str_replace("{bookingno}", $id, $message1);
						$message1 = str_replace("{spacename}", $space->title, $message1);
						$message1 = str_replace("{checkouttime}", $space->checkout_reminder_time, $message1);
						$message1 = str_replace("{checkouturl}", $checkouturl, $message1);
						try {
							$account_sid = $smstwilioaccountsid;
							$auth_token = $smstwilioaccounttoken;
							$twilio_number = $smstwiliofromno;
							$client = new Client($account_sid, $auth_token);
							$message = $client->messages->create($receiverNumber, [
								'from' => $twilio_number,
								'body' => $message1
							]);
							if ($message->sid) {
								echo "success";
							}
						} catch (Exception $e) {
							$error = "Error in Sending SMS";
						}

					} else {
						echo "systemdate and new checkout date do not match" . "<br/>";

					}
					//reminder pre-departure prior booking end time 	
				}
				echo "--------------------------------------------------" . "<br/>";

				if ($space->host_checkin_reminder != "") {
					echo "--------------------------------------------------" . "<br/>";
					$reminderlatecheckin = $space->host_checkin_reminder;
					echo "reminder late checkin time :" . $reminderlatecheckin . "<br/>";
					if ($reminderlatecheckin == '5 Minutes') {
						$new_late_checkin_date = strtotime($booking->start_date . ' +5 Minutes ');
					}
					if ($reminderlatecheckin == '15 Minutes') {
						$new_late_checkin_date = strtotime($booking->start_date . ' +15 Minutes ');
					}
					if ($reminderlatecheckin == '30 Minutes') {
						$new_late_checkin_date = strtotime($booking->start_date . ' +30 Minutes ');
					}

					echo "calcualted reminder_time_for_late_checkin:" . $new_late_checkin_date . "<br/>";
					//email to host reminder late checkin  	
					$message1 = $space->host_reminder_text;
					//$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
					
					// $checkinurl = url('user/booking-details/') . "/" . $id;
					$checkinurl = $this->getGuestCheckInUrl($id);
					
					$message1 = str_replace("{FirstName}", $booking->first_name, $message1);
					$message1 = str_replace("{bookingno}", $booking->id, $message1);
					$message1 = str_replace("{listingname}", $space->title, $message1);
					$message1 = str_replace("{arrivaltime}", $booking->start_date, $message1);
					$message1 = str_replace("{departuretime}", $booking->end_date, $message1);
					$message1 = str_replace("{bookinglink}", $checkinurl, $message1);
					$message1 = str_replace("{cancellationfee}", '', $message1);
					/*
													   Dear {FirstName},
													   
													   Your Guest {guestname} has not yet Checked IN for Booking #{bookingno}. 
													   
													   Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
													   
													   Booking Details :
													   
													   Listing Name            : {listingname}
													   Arrival Time            : {arrivaltime}
													   Departure Date and Time : {departuretime}
													   Cancellation Fee        :

													   <>Contact Guest | Manual Check IN | Edit Booking    				
											   */

					if (strtotime($systemdate) == $new_late_checkin_date) {
						$spacecreateuser = $space->create_user;
						$spaceuser = User::where('id', $spacecreateuser)->first();
						$receiveremail = $spaceuser->email;
						$mail = new PHPMailer(true);     // Passing `true` enables exceptions
						try {

							// Email server settings
							$mail->SMTPDebug = 0;
							$mail->isSMTP();
							$mail->Host = 'sandbox.smtp.mailtrap.io';             //  smtp host
							$mail->SMTPAuth = true;
							$mail->Username = '5d5e8cf9024d6e';   //  sender username
							$mail->Password = 'b9a97557e831e5';       // sender password
							$mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
							$mail->SMTPAutoTLS = true;
							$mail->Port = 587;                          // port - 587/465

							$mail->setFrom('info@mybackpocket.co', 'MyOffice');
							$mail->addAddress($receiveremail);

							$mail->addReplyTo('info@mybackpocket.co', 'MyOffice');

							$mail->isHTML(true);                // Set email content format to HTML
							$mail->Subject = "Reminder Email for Guest Not Checked In";
							$body = $message1;
							$mail->Body = $body;
							$mail->AltBody = $body;

							if ($mail->send()) {
								echo "success" . "<br/>";
							} else {
								echo "error" . "<br/>";
							}

						} catch (Exception $e) {
							echo "error in sending late checkin host reminder" . "<br/>";
						}
						//host reminder email  for late checkin
					} else {
						echo "systemdate and latecheckin date do not match" . "<br/>";
					}
				}
				echo "--------------------------------------------------" . "<br/>";
				echo "--------------------------------------------------" . "<br/>";

				if ($space->latecheckout_reminder_time != "") {
					$latecheckout = $space->latecheckout_reminder_time;
					echo "late checkout reminder " . $latecheckout . "<br/>";

					if ($latecheckout == '5 Minutes') {
						$late_checkout_date = strtotime($booking->end_date . ' +5 Minutes ');
					}
					if ($latecheckout == '15 Minutes') {
						$late_checkout_date = strtotime($booking->end_date . ' +15 Minutes ');
					}
					if ($latecheckout == '30 Minutes') {
						$late_checkout_date = strtotime($booking->end_date . ' +30 Minutes ');
					}
					echo "system date : " . strtotime($systemdate);
					echo "calculated_reminder_time_for_late_checkout :" . $late_checkout_date . "<br/>";
					if (strtotime($systemdate) == $late_checkout_date) {
						echo "systemdateforlatecheckout and new checkout date matches" . "<br/>";
						$receiverNumber = $booking->phone;
						
						// $checkouturl = url('user/booking-details/') . "/" . $id;
						$checkouturl = $this->getGuestCheckOutUrl($id);

						$message1 = $space->latecheckout_reminder_text;
						$message1 = str_replace("{bookingno}", $booking->id, $message1);
						$message1 = str_replace("{spacename}", $space->title, $message1);
						$message1 = str_replace("{checkouturl}", $checkouturl, $message1);

						try {
							$account_sid = $smstwilioaccountsid;
							$auth_token = $smstwilioaccounttoken;
							$twilio_number = $smstwiliofromno;
							$client = new Client($account_sid, $auth_token);
							$message = $client->messages->create($receiverNumber, [
								'from' => $twilio_number,
								'body' => $message1
							]);
							if ($message->sid) {
								echo "success" . "<br/>";
							}
						} catch (Exception $e) {
							$error = "Error in Sending SMS" . "<br/>";
						}

					} else {
						echo "systemdate and new checkout date do not match" . "<br/>";

					}
					//reminder late checkout  	
				}
				echo "--------------------------------------------------" . "<br/>";
				echo "--------------------------------------------------" . "<br/>";

				$exact_checkin_date = strtotime($booking->start_date);
				echo "system date :" . strtotime($systemdate) . "<br/>";
				echo "exact checkin date :" . $exact_checkin_date . "<br/>";
				if (strtotime($systemdate) == $exact_checkin_date) {
					echo "systemdate and exact checkin date matches" . "<br/>";
					$receiverNumber = $booking->phone;

					// $checkinurl = url('user/booking-details/') . "/" . $id;
					$checkinurl = $this->getGuestCheckInUrl($id);

					$message1 = $space->arrival_checkin_text;
					$message1 = str_replace("{bookingno}", $id, $message1);
					$message1 = str_replace("{spacename}", $space->title, $message1);
					$message1 = str_replace("{checkinurl}", $checkinurl, $message1);

					try {
						$account_sid = $smstwilioaccountsid;
						$auth_token = $smstwilioaccounttoken;
						$twilio_number = $smstwiliofromno;
						$client = new Client($account_sid, $auth_token);
						$message = $client->messages->create($receiverNumber, [
							'from' => $twilio_number,
							'body' => $message1
						]);
						if ($message->sid) {
							echo "success" . "<br/>";
						}
					} catch (Exception $e) {
						$error = "Error in Sending SMS" . "<br/>";
					}

				} else {
					echo "systemdate and exact booking date do not match" . "<br/>";

				}
				//exact time checkin message.
				echo "--------------------------------------------------" . "<br/>";
				echo "--------------------------------------------------" . "<br/>";
				//	}
				//sending prearrival reminder sms


			}
		} // all records checking for scheduled booking


	}
}