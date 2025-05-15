<?php
namespace Modules\Space\Controllers;
namespace Modules\User\Controllers;

use App\Exports\BookingExport;
use App\Helpers\CRMHelper;
use App\Models\CreditCoupons;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel;
use Matrix\Exception;
use Modules\Contact\Emails\NotificationEmail;
use Modules\FrontendController;
use Modules\Space\Models\Space;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Models\Newsletter;
use Modules\User\Models\Subscriber;
use Modules\User\Models\User;
use Modules\Core\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Modules\Vendor\Models\VendorRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Validator;
use Modules\Booking\Models\Booking;
use App\Helpers\ReCaptchaEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Booking\Models\Enquiry;
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use Modules\Space\Models\SpaceBlockTime;
use Modules\Space\Models\SpaceDate;
use Modules\Location\Models\Location;
use DB;
use Modules\User\Models\Wallet\DepositPayment;
use App\Models\EmailSubject;
use App\Models\EmailTemplate;
use Modules\User\Emails\UserCheckIn;
use Illuminate\Support\Facades\Mail;
use Modules\Space\Models\PostalCodesAndTimeZone;
use Modules\Space\Models\Timezones_Reference;
use PDF;

class BookingController extends FrontendController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function bookingInvoice($code)
	{
		$booking = Booking::where('code', $code)->first();
		$user_id = Auth::id();
		if (empty($booking)) {
			return redirect('user/booking-history');
		}
		if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
			return redirect('user/booking-history');
		}
		$service = $booking->service;
		$page_title = __("Invoice");
		$data = [
			'booking' => $booking,
			'service' => $service,
			'page_title' => $page_title
		];
		// if (isset($_GET['download'])) {
		// 	$pdf = PDF::loadView('User::frontend.bookingInvoice', compact('booking', 'service', 'page_title'));
		// 	return $pdf->download('transactions.pdf');
		// }
		return view('User::frontend.bookingInvoice', $data);
	}

	public function issueCreditOld($code)
	{
		$booking = Booking::where('code', $code)->first();
		$user_id = Auth::id();
		if (empty($booking)) {
			return redirect('user/booking-history');
		}
		if ($booking->vendor_id != $user_id) {
			return redirect('user/booking-history');
		}

		if ($booking->is_credit_issued === 0 || $booking->is_credit_issued === "0") {
			$booking->is_credit_issued = 1;
			$booking->save();

			$booking->customer->promo_credits = +$booking->paid;
			$booking->customer->save();


			return redirect()->back()->with('success', __('Promo Credits has been issued to customer.'));
		} else {
			return redirect()->back()->with('error', __('Promo Credits already issued.'));
		}
	}

	public function issueCredit(Request $request)
	{
		$validate = [
			'object_id' => 'required',
			'object_model' => 'required',
			'recepient' => 'required',
			'amount' => 'required',
			'type' => 'required',
			'reference' => 'required',
		];
		$request->validate($validate);
		$booking = Booking::where('id', $request->input('object_id'))->first();
		if ($booking == null) {
			return redirect()->back()->with('error', __('Booking not found.'));
		}

		$user = $booking->customer;

		$model = new CreditCoupons($request->all());
		$model->user_id = $booking->customer_id;
		$model->pending = $model->amount;
		$model->expired_at = date(Constants::PHP_DATE_FORMAT, strtotime("+20 years"));
		$model->code = $model->generateCode();
		$model->save();

		$user->promo_credits += $model->amount;
		$user->save();

		$data = CodeHelper::addUserTransaction(
			auth()->user(),
			$model->type,
			$model->amount,
			Constants::DEBIT,
			'ISSUE_CREDIT_SENT_' . time() . '-' . $model->id,
			[],
			[],
			false,
			true
		);

		$data = CodeHelper::addUserTransaction(
			$user,
			$model->type,
			$model->amount,
			Constants::CREDIT,
			'ISSUE_CREDIT_RECEIVED_' . time() . '-' . $model->id,
			[],
			[],
			false,
			true
		);

		Mail::to($request->input('recepient'))->send(
			new NotificationEmail(
				'#' . $booking->id . ' -' . ucfirst($model->type) . ' Received',
				'You have received ' . CodeHelper::formatPrice($model->amount) . ' as ' . $model->type . ' on your booking.</br></br>
				You can use them to book any space from same host. You can redeem them by applying coupon code.</br>
				<h4>Coupon Code: ' . $model->code . '</h4> ',
			)
		);

		return redirect()->back()->with('success', __('Credits has been issued to customer.'));
	}

	public function sendEmailInvoice(Request $request)
	{
		// dd($request->all());
		$validate = [
			'object_id' => 'required',
			'recepient' => 'required',
			'message' => 'required',
		];
		$request->validate($validate);
		$booking = Booking::where('id', $request->input('object_id'))->first();
		if ($booking == null) {
			return redirect()->back()->with('error', __('Booking not found.'));
		}

		$service = $booking->service;
		$page_title = __("Invoice");

		$invoiceFileName = "MyOffice_InvoiceNo_" . $booking->id . ".pdf";
		$path = storage_path("app") . "/" . $invoiceFileName;

		$pdf = PDF::loadView('User::frontend.bookingInvoice', compact('booking', 'service'));
		$pdf->save($path);

		Mail::to($request->input('recepient'))->send(
			new NotificationEmail(
				'#' . $booking->id . ' Booking Invoice',
				$request->input('message'),
				[
					[
						'path' => $path,
						'options' => [
							'as' => $invoiceFileName
						]
					]
				]
			)
		);

		unlink($path);

		return redirect()->back()->with('success', __('Invoice has been sent.'));
	}

	public function sendBookingDetails(Request $request)
	{
		// dd($request->all());
		$validate = [
			'object_id' => 'required',
			'recepient' => 'required',
			'message' => 'required',
		];
		$request->validate($validate);
		$booking = Booking::where('id', $request->input('object_id'))->first();
		if ($booking == null) {
			return redirect()->back()->with('error', __('Booking not found.'));
		}

		Mail::to($request->input('recepient'))->send(
			new NotificationEmail(
				'#' . $booking->id . ' Booking Details',
				$request->input('message')
			)
		);

		return redirect()->back()->with('success', __('Invoice has been sent.'));
	}

	public function bulkBookingInvoice(Request $request)
	{
		$ids = explode(',', $request->pdf_ids);
		$bookings = Booking::whereIn('id', $ids)->get();
		$pdf = PDF::loadView('User::frontend.booking_bulkInvoice', compact('bookings'));
		return $pdf->download('bookings.pdf');
	}

	public function exportBookings(Request $request)
	{
		$ids = explode(',', $request->xls_ids);
		return (new BookingExport($ids))->download('bookings.xls');
	}

	public function archive(Request $request)
	{
		$id = $request->id;
		$booking = Booking::find($id);
		$booking->is_archive = 1;
		$booking->save();
		return back();
	}

	public function completebooking(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::find($id);
		$booking->status = 'completed';
		$booking->save();
		$booking = Booking::where('id', $id)->first();
		$receiveremail = $booking->email;
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host
			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');
			$mail->addAddress($receiveremail);

			$mail->addReplyTo('hemantdesai2009@gmail.com', 'MyOffice');

			$mail->addReplyTo($emailusername, 'Myoffice');
			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-`/".$booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $id;
			$invoicelink = "<a type='button' target='_blank' href='" . route('user.booking.invoice', $booking->id) . "' class='btn btn-des btn-icon btn-lg'>InvoiceLink</a>";
			$mail->Subject = "Thank you for completing booking.";
			$body = "Dear" . $booking->first_name . ", <br/><br/>";
			$body = $body . "Thank you for completing the following booking :" . "<br/><br/>";
			;
			$body = $body . "Booking Details :" . "<br/>";
			$body = $body . "Listing Name :" . $space->title . "<br/>";
			$body = $body . "Arrival Date and Time :" . $booking->start_date . "<br/>";
			$body = $body . "Departure Date and Time :" . $booking->end_date . "<br/>";
			$body = $body . "Final Charges :" . $booking->total_before_discount . "<br/>";
			$body = $body . "Invoice Link :" . $invoicelink . "<br/>";
			$body = $body . "Thank you :" . "<br/><br/>";
			$body = $body . "Management MyOfice Team";
			$mail->Body = $body;
			$mail->AltBody = $body;
			if (!$mail->send()) {
				return redirect()->back()->with('error', __('Error in Updating Complete Status in Booking.'));
			} else {
				return redirect()->back()->with('success', __('Booking Complete Status Updated.'));
			}

		} catch (Exception $e) {
			return redirect()->back()->with('error', __('Error in Updating Complete Status in Booking.'));
		}
	}

	public function sendbooking(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::find($id);
		$space = Space::find($booking->object_id);
		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}
		$receiveremail = $request->sendemailaddress;
		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {
			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host
			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');
			$mail->addAddress($receiveremail);

			$mail->addReplyTo($emailusername, 'Myoffice');
			$mail->isHTML(true);                // Set email content format to HTML

			$mail->Subject = "Booking Details from MyOffice.ca";
			$body = "Dear " . $booking->first_name . ", <br/><br/>";
			$body = $body . "Thank you for booking office space on MyOffice.ca and your booking reference is " . $booking->id . "<br/><br/>";
			$body = $body . "We confirm below the Booking Details: <br/><br/>";
			$body = $body . "Booking Details :" . "<br/>";
			$body = $body . "Listing Name            : " . $space->title . "<br/>";
			$body = $body . "Listing Address         : " . $space->address . "<br/>";
			$body = $body . "Arrival Date and Time   : " . $booking->start_date . "<br/>";
			$body = $body . "Departure Date and Time : " . $booking->end_date . "<br/><br/>";
			$body = $body . "Thank you. :<br/><br/><br/>";
			$body = $body . "MyOffice.ca Management Team";
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
		//Send Booking Email

		return redirect()->back()->with('success', __('Booking Sent To Email.'));
	}

	public function statuschange(Request $request)
	{
		$id = $request->id;
		$status = $request->changetostatus;
		$booking = Booking::find($id);
		$booking->status = strtolower($status);
		$booking->save();
		$booking->sendBookingNotifications();
		return redirect()->back()->with('success', __('Booking Status Updated.'));

	}

	public function reschedule(Request $request)
	{
		$id = $request->booking_id;
		$newstartdate = $request->newstart_date;
		$newenddate = $request->newend_date;
		$booking = Booking::find($id);
		$booking->start_date = date('Y-m-d H:i', strtotime($newstartdate));
		$booking->end_date = date('Y-m-d H:i', strtotime($newenddate));
		$booking->save();
		return redirect()->back()->with('success', __('Booking Rescheduled.'));

	}

	public function availableDates1(Request $request)
	{
		$startMain = date('Y-m-d') . " 00:00:00";
		$id = $request->id;
		$availabilities = [];
		$start = $request->startdate;
		$extendtime = $request->extendtime;
		$end = $request->enddate;
		if ($extendtime == "1 Hour") {
			$end = date("Y-m-d H:i:s", strtotime($end . ' +1 Hour '));
		}
		if ($extendtime == "2 Hours") {
			$end = date("Y-m-d H:i:s", strtotime($end . ' +2 Hours '));
		}
		if ($extendtime == "3 Hours") {
			$end = date("Y-m-d H:i:s", strtotime($end . ' +3 Hours '));
		}
		if ($start != null && $end != null) {
			$start = date('Y-m-d', strtotime($start));
			$end = date('Y-m-d', strtotime($end));
			$dates = Constants::getDatesFromRange($start, $end);
			if ($dates != null) {
				foreach ($dates as $date) {
					$startDateMain = $date . " 00:00:00";
					$toDateMain = $date . " 23:59:59";
					if ($startDateMain >= $startMain) {
						$datesNotAvailable = [];
						//echo $startDateMain . " - " . $toDateMain . PHP_EOL;
						//get bookings between
						$bookingBetween = Booking::whereRaw("`status` != 'draft' and `object_model` = 'space' and `object_id` = " . $id . " and ( (`start_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`end_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `start_date` and `end_date`) OR ('$toDateMain' BETWEEN `start_date` and `end_date`) )")->orderBy('start_date')->get();
						if ($bookingBetween != null && count($bookingBetween) > 0) {
							foreach ($bookingBetween as $bookingRow) { {
									$s = $bookingRow->start_date;
									$e = $bookingRow->end_date;

									$npStart = date('H:i', strtotime($s));
									if ($startDateMain >= $s) {
										$npStart = '00:00';
									}

									$npEnd = date('H:i', strtotime($e));
									if ($e >= $toDateMain) {
										$npEnd = '23:59';
									}

									//$datesNotAvailable[] = $npStart . " - " . $npEnd;
									$availabilities[] = [
										'title' => "Booked For MyOffice Client " . $npStart . " - " . $npEnd,
										'start' => $date . " " . $npStart . ":00",
										'end' => $date . " " . $npEnd . ":59",
									];
								}
							}
						}

						$blockedBetween = SpaceBlockTime::whereRaw("`bravo_space_id` = " . $id . " and ( (`from` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`to` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `from` and `to`) OR ('$toDateMain' BETWEEN `from` and `to`) )")->get();
						if ($blockedBetween != null && count($blockedBetween) > 0) {
							foreach ($blockedBetween as $blockedRow) { {
									$s = $blockedRow->from;
									$e = $blockedRow->to;

									$npStart = date('H:i', strtotime($s));
									if ($startDateMain >= $s) {
										$npStart = '00:00';
									}

									$npEnd = date('H:i', strtotime($e));
									if ($e >= $toDateMain) {
										$npEnd = '23:59';
									}

									//$datesNotAvailable[] = $npStart . " - " . $npEnd;
									$availabilities[] = [
										'title' => "Unavailable " . $npStart . " - " . $npEnd,
										'start' => $date . " " . $npStart . ":00",
										'end' => $date . " " . $npEnd . ":59",
									];
								}
							}
						}

						// print_r($datesNotAvailable);

					}
				}
			}
		}
		return response()->json($availabilities);
	}

	public function availableDates2(Request $request)
	{
		$startMain = date('Y-m-d') . " 00:00:00";
		$id = $request->id;
		$availabilities = [];
		$start = $request->start_date;
		$starttime = $request->start_time;
		$end = $request->end_date;
		$endtime = $request->end_time;
		if ($start != null && $end != null) {
			$start = date('Y-m-d', strtotime($start));
			$end = date('Y-m-d', strtotime($end));
			$dates = Constants::getDatesFromRange($start, $end);
			if ($dates != null) {
				foreach ($dates as $date) {
					$startDateMain = $date . " 00:00:00";
					$toDateMain = $date . " 23:59:59";
					if ($startDateMain >= $startMain) {
						$datesNotAvailable = [];
						//echo $startDateMain . " - " . $toDateMain . PHP_EOL;
						//get bookings between
						$bookingBetween = Booking::whereRaw("`status` != 'draft' and `object_model` = 'space' and `object_id` = " . $id . " and ( (`start_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`end_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `start_date` and `end_date`) OR ('$toDateMain' BETWEEN `start_date` and `end_date`) )")->orderBy('start_date')->get();
						if ($bookingBetween != null && count($bookingBetween) > 0) {
							foreach ($bookingBetween as $bookingRow) { {
									$s = $bookingRow->start_date;
									$e = $bookingRow->end_date;

									$npStart = date('H:i', strtotime($s));
									if ($startDateMain >= $s) {
										$npStart = '00:00';
									}

									$npEnd = date('H:i', strtotime($e));
									if ($e >= $toDateMain) {
										$npEnd = '23:59';
									}

									//$datesNotAvailable[] = $npStart . " - " . $npEnd;
									$availabilities[] = [
										'title' => "Booked For MyOffice Client " . $npStart . " - " . $npEnd,
										'start' => $date . " " . $npStart . ":00",
										'end' => $date . " " . $npEnd . ":59",
									];
								}
							}
						}

						$blockedBetween = SpaceBlockTime::whereRaw("`bravo_space_id` = " . $id . " and ( (`from` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`to` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `from` and `to`) OR ('$toDateMain' BETWEEN `from` and `to`) )")->get();
						if ($blockedBetween != null && count($blockedBetween) > 0) {
							foreach ($blockedBetween as $blockedRow) { {
									$s = $blockedRow->from;
									$e = $blockedRow->to;

									$npStart = date('H:i', strtotime($s));
									if ($startDateMain >= $s) {
										$npStart = '00:00';
									}

									$npEnd = date('H:i', strtotime($e));
									if ($e >= $toDateMain) {
										$npEnd = '23:59';
									}

									//$datesNotAvailable[] = $npStart . " - " . $npEnd;
									$availabilities[] = [
										'title' => "Unavailable </br> " . $npStart . " - " . $npEnd,
										'start' => $date . " " . $npStart . ":00",
										'end' => $date . " " . $npEnd . ":59",
									];
								}
							}
						}

						// print_r($datesNotAvailable);

					}
				}
			}
		}
		return response()->json($availabilities);
	}


	public function verifySelectedTimes1()
	{
		$response = [
			'status' => 'error',
			'message' => 'Failed to check availability',
			'price' => 0,
			'start_date' => null,
			'end_date' => null
		];

		$id = isset($_POST['id']) ? trim($_POST['id']) : null;
		if ($id != null) {
			$start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : null;
			$end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;
			$extendtime = isset($_POST['extendtime']) ? trim($_POST['extendtime']) : null;
			if ($extendtime == "1 Hour") {
				$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' + 1 hour'));
			}
			if ($extendtime == "2 Hours") {
				$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' + 2 hours'));
			}
			if ($extendtime == "3 Hours") {
				$end_date = date('Y-m-d H:i:s', strtotime($end_date . ' + 3 hours'));
			}
			$booking = Booking::where('id', $id)->first();
			$spaceid = $booking->object_id;

			$bookingBetween = Booking::whereRaw("(`status` != 'complete' OR `status` != 'paid') 
			and `object_model` = 'space' and `object_id` = " . $spaceid . " and `id` != " . $id . "
            and ( (`start_date` BETWEEN '" . $start_date . "' and '" . $end_date . "')
            OR (`end_date` BETWEEN '" . $start_date . "' and '" . $end_date . "') )")->get();
			if (sizeof($bookingBetween) == 0) {
				$response['booking_id'] = $id;
				$response['start_date'] = $start_date;
				$response['end_date'] = $end_date;
				$response['extendtime'] = $extendtime;
				$response['status'] = 'success';
				$response['message'] = 'Successfully checked the availability';
			}
			if (sizeof($bookingBetween) > 0) {
				$response['booking_id'] = $id;
				$response['start_date'] = $start_date;
				$response['end_date'] = $end_date;
				$response['extendtime'] = $extendtime;
				$response['status'] = 'bookingexists';
				$response['message'] = 'Successfully checked the availability';
			}
			return response()->json($response);
		}
	}

	public function verifySelectedTimes2()
	{
		$response = [
			'status' => 'error',
			'message' => 'Failed to check availability',
			'price' => 0,
			'start_date' => null,
			'end_date' => null
		];

		$id = isset($_POST['id']) ? trim($_POST['id']) : null;
		if ($id != null) {
			$start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : null;
			$end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;
			$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : null;
			$end_time = isset($_POST['end_time']) ? trim($_POST['end_time']) : null;
			$start_date = $start_date . " " . $start_time;
			$end_date = $end_date . " " . $end_time;
			$booking = Booking::where('id', $id)->first();
			$spaceid = $booking->object_id;
			$space = Space::find($booking->object_id);

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
			if (strtotime($start_date) >= strtotime($end_date)) {
				$response['booking_id'] = $id;
				$response['start_date'] = $start_date;
				$response['end_date'] = $end_date;
				$response['start_time'] = $start_time;
				$response['end_time'] = $end_time;
				$response['status'] = 'endtimeerror';
				$response['message'] = 'Booking End Date Time cannot be lesser than Start Date time.';
				return response()->json($response);
			} elseif (strtotime($systemdate) >= strtotime($start_date)) {
				$response['booking_id'] = $id;
				$response['start_date'] = $start_date;
				$response['end_date'] = $end_date;
				$response['start_time'] = $start_time;
				$response['end_time'] = $end_time;
				$response['status'] = 'error1';
				$response['message'] = 'Booking Start Date Time cannot be lesser than current time.';
				return response()->json($response);
			} elseif (strtotime($systemdate) >= strtotime($end_date)) {
				$response['booking_id'] = $id;
				$response['start_date'] = $start_date;
				$response['end_date'] = $end_date;
				$response['start_time'] = $start_time;
				$response['end_time'] = $end_time;
				$response['status'] = 'error2';
				$response['message'] = 'Booking End Date Time cannot be lesser than current time.';
				return response()->json($response);
			} else {
				$bookingBetween = Booking::whereRaw("(`status` != 'complete' OR `status` != 'paid') 
			and `object_model` = 'space' and `object_id` = " . $spaceid . " and `id` != " . $id . "
            and ( (`start_date` BETWEEN '" . $start_date . "' and '" . $end_date . "')
            OR (`end_date` BETWEEN '" . $start_date . "' and '" . $end_date . "') )")->get();

				if (sizeof($bookingBetween) == 0) {
					$response['booking_id'] = $id;
					$response['start_date'] = $start_date;
					$response['end_date'] = $end_date;
					$response['start_time'] = $start_time;
					$response['end_time'] = $end_time;
					$response['status'] = 'success';
					$response['message'] = 'Successfully checked the availability';
				}
				if (sizeof($bookingBetween) > 0) {
					$response['booking_id'] = $id;
					$response['start_date'] = $start_date;
					$response['end_date'] = $end_date;
					$response['start_time'] = $start_time;
					+
						$response['end_time'] = $end_time;
					$response['status'] = 'bookingexists';
					$response['message'] = 'Successfully checked the availability';
				}
				return response()->json($response);
			}
		}
	}

	public function extendbooking(Request $request)
	{
		$id = $request->booking_id;
		$extendtime = $request->extendtime;
		$extendtime = substr($extendtime, 0, 1);
		$booking = Booking::find($id);
		$space = Space::find($booking->object_id);
		$extendprice = 0;
		// return redirect($booking->getCheckoutUrl());
		// exit;
		if (($space->hourly * $extendtime) > $space->daily) {
			$extendprice = $space->daily;
		} else {
			$extendprice = $space->hourly * $extendtime;
		}

		$row = auth()->user();
		$rules = [];
		$message = [];

		$payment_gateway = $request->input('payment_gateway');
		$gateways = get_payment_gateways();
		if (empty($payment_gateway)) {
			return redirect()->back()->with("error", __("Please select payment gateway"));
		}
		if (empty($payment_gateway) or empty($gateways[$payment_gateway]) or !class_exists($gateways[$payment_gateway])) {
			return redirect()->back()->with("error", __("Payment gateway not found"));
		}
		$gatewayObj = new $gateways[$payment_gateway]($payment_gateway);

		if (!$gatewayObj->isAvailable()) {
			return redirect()->back()->with("error", __("Payment gateway is not available"));
		}
		if ($gRules = $gatewayObj->getValidationRules()) {
			$rules = array_merge($rules, $gRules);
		}
		if ($gMessages = $gatewayObj->getValidationMessages()) {
			$message = array_merge($message, $gMessages);
		}
		$rules['payment_gateway'] = 'required';

		$validator = Validator::make($request->all(), $rules, $message);
		if ($validator->fails()) {
			if (is_array($validator->errors()->messages())) {
				$msg = '';
				foreach ($validator->errors()->messages() as $oneMessage) {
					$msg .= implode('<br/>', $oneMessage);
				}
				return redirect()->back()->with('error', $msg);
			}
			return redirect()->back()->with('error', $validator->errors());
		}


		$payment = new DepositPayment();
		$payment->object_model = 'wallet_deposit';
		$payment->object_id = $row->id;
		$payment->status = 'draft';
		$payment->payment_gateway = $payment_gateway;
		$payment->amount = $extendprice;
		$payment->save();

		$res = $gatewayObj->processNormal($payment);

		$success = $res[0] ?? null;
		$message = $res[1] ?? null;
		$redirect_url = $res[2] ?? null;

		if ($success) {
			$payment->code = $request->input('txnToken');
			$payment->save();

			if (empty($redirect_url) and $payment->status == 'completed') {
				// Send Email
				// $payment->sendNewPurchaseEmail();
			}
			//event(new RequestCreditPurchase($row, $payment));
		}

		if ($success and $payment->status == 'completed')
			$redirect_url = route('user.single.booking.detail', $id);
		if ($redirect_url) {
			return redirect()->to($redirect_url)->with($success ? "success" : "error", $message);
		}
		//return redirect()->back()->with($success ? "success" : "error", $message);
		return redirect()->back()->with('success', __('Booking Extended.'));
	}

	public function sendsmsauto(Request $request)
	{

		$bookings = Booking::all();
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
					echo "strtotime" . strtotime($systemdate);
					echo "strtotime" . strtotime($new_booking_date);

					if (strtotime($systemdate) == $new_booking_date) {
						echo "systemdate and checkin date matches." . "<br/>";
						$message1 = $space->prearrival_checkin_text;
						//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
						$checkinurl = url('user/booking-details/') . "/" . $id;
						$bookinglink = url('user/booking-details/') . "/" . $id;
						$message1 = str_replace("{firstname}", $booking->first_name, $message1);
						$message1 = str_replace("{spacename}", $space->title, $message1);
						$message1 = str_replace("{checkintime}", $space->checkin_reminder_time, $message1);
						$message1 = str_replace("{bookinglink}", $bookinglink, $message1);
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
						$bookinglink = url('user/booking-details/') . "/" . $id;
						$checkouturl = url('user/booking-details/') . "/" . $id;
						$message1 = $space->departure_reminder_text;
						$message1 = str_replace("{bookingno}", $id, $message1);
						$message1 = str_replace("{spacename}", $space->title, $message1);
						$message1 = str_replace("{departure_reminder_time}", $space->checkout_reminder_time, $message1);
						$message1 = str_replace("{bookinglink}", $bookinglink, $message1);
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
					$checkinurl = url('user/booking-details/') . "/" . $id;
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
						$receiveremail = $booking->email;

						$settings = Settings::where('group', 'email')->get();
						for ($i = 0; $i < sizeof($settings); $i++) {
							if ($settings[$i]['name'] == "email_driver") {
								$emaildriver = $settings[$i]['val'];
							}
							if ($settings[$i]['name'] == "email_host") {
								$emailhost = $settings[$i]['val'];
							}
							if ($settings[$i]['name'] == "email_port") {
								$emailport = $settings[$i]['val'];
							}
							if ($settings[$i]['name'] == "email_encryption") {
								$emailencryption = $settings[$i]['val'];
							}
							if ($settings[$i]['name'] == "email_username") {
								$emailusername = $settings[$i]['val'];
							}
							if ($settings[$i]['name'] == "email_password") {
								$emailuserpassword = $settings[$i]['val'];
							}
						}
						$spacecreateuser = $space->create_user;
						$spaceuser = User::where('id', $spacecreateuser)->first();
						$receiveremail = $spaceuser->email;

						$mail = new PHPMailer(true);     // Passing `true` enables exceptions
						try {

							// Email server settings
							$mail->SMTPDebug = 0;
							$mail->isSMTP();
							$mail->Host = $emailhost;             //  smtp host
							$mail->SMTPAuth = true;
							$mail->Username = $emailusername;
							$mail->Password = $emailuserpassword;       // sender password
							$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
							$mail->Port = $emailport;                          // port - 587/465
							$mail->setFrom($emailusername, 'Myoffice');
							$mail->addAddress($receiveremail);

							$mail->addReplyTo($emailusername, 'Myoffice');

							$mail->isHTML(true);                // Set email content format to HTML

							$mail->Subject = "Reminder Email for Guest Not Checked In";
							/*
																																																																														 $body	  = "Dear".$booking->first_name.", <br/><br/>";
																																																																														 $body	  = $body."Your Guest has not yet checked in for #Booking No.".$booking->id."<br/>";
																																																																														 $body    = $body."Please manually CheckIN the guest or contact them to ";
																																																																														 $body    = $body."verify if they are still going to complete their scheduled booking."."<br/><br/>";
																																																																														 $body    = $body."Booking Details :"."<br/>";
																																																																														 $body    = $body."Listing Name :".$space->title."<br/>";
																																																																														 $body    = $body."Arrival Date and Time :".$booking->start_date."<br/>";
																																																																														 $body    = $body."Departure Date and Time :".$booking->end_date."<br/>";
																																																																														 $body    = $body."Cancellation Fee :"."<br/>";
																																																																														 $body    = $body."<a href=''>Contact Guest</a> | <a href=''>Manual Check In</a> | <a href=''>Edit Booking</a>";
																																																																														 */
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
						$checkouturl = url('user/booking-details/') . "/" . $id;
						$message1 = $space->latecheckout_reminder_text;
						// $message1=str_replace("{bookingno}",$booking->bookingno,$message1);
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
					$bookinglink = url('user/booking-details/') . "/" . $id;
					$checkinurl = url('user/booking-details/') . "/" . $id;

					$message1 = $space->arrival_checkin_text;
					$message1 = str_replace("{bookingno}", $id, $message1);
					$message1 = str_replace("{spacename}", $space->title, $message1);
					$message1 = str_replace("{bookinglink}", $bookinglink, $message1);
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


	public function sendsmspre(Request $request)
	{
		$id = $request->id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$locationid = $space->location_id;
		$locationdetails = Location::all();

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

		$receiverNumber = $booking->phone;
		$message1 = $space->prearrival_checkin_text;
		//$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
		$checkinurl = url('user/booking-details/') . "/" . $id;
		$message1 = str_replace("{firstname}", $booking->first_name, $message1);
		$message1 = str_replace("{spacename}", $space->title, $message1);
		$message1 = str_replace("{checkintime}", $space->checkin_reminder_time, $message1);
		$message1 = str_replace("{bookingno}", $id, $message1);
		$message1 = str_replace("{bookinglink}", $checkinurl, $message1);
		try {

			$account_sid = $smstwilioaccountsid;
			$auth_token = $smstwilioaccounttoken;
			$twilio_number = $smstwiliofromno;
			$client = new Client($account_sid, $auth_token);
			$client->messages->create($receiverNumber, [
				'from' => $twilio_number,
				'body' => $message1
			]);
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking Checkin',
				'sms_prearrival_message' => 'SMS Sent Successfully',
				'sms_arrival_message' => '',
				'failed' => '',
				'success' => '',
				'error' => ''
			];
			return view('User::frontend.bookingCheckIn', $data);
		} catch (Exception $e) {
			$error = "Error in Sending SMS";
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking Checkin',
				'sms_prearrival_message' => 'Error in Sending SMS',
				'sms_arrival_message' => '',
				'failed' => '',
				'success' => '',
				'error' => 'Error in sending Email'
			];
			return view('User::frontend.bookingCheckIn', $data);
		}
	}

	public function sendsmsarrival(Request $request)
	{
		$id = $request->id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
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
		$receiverNumber = $booking->phone;
		$bookinglink = url('user/booking-details/') . "/" . $id;
		$checkinurl = url('user/booking-details/') . "/" . $id;

		$message1 = $space->arrival_checkin_text;
		$message1 = str_replace("{bookingno}", $id, $message1);
		$message1 = str_replace("{spacename}", $space->title, $message1);
		$message1 = str_replace("{bookinglink}", $bookinglink, $message1);
		$message1 = str_replace("{checkinurl}", $checkinurl, $message1);


		try {

			$account_sid = $smstwilioaccountsid;
			$auth_token = $smstwilioaccounttoken;
			$twilio_number = $smstwiliofromno;

			$client = new Client($account_sid, $auth_token);
			$client->messages->create($receiverNumber, [
				'from' => $twilio_number,
				'body' => $message1
			]);
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking Checkin',
				'sms_prearrival_message' => '',
				'sms_arrival_message' => 'SMS Sent Successfully',
				'failed' => '',
				'success' => '',
				'error' => ''
			];
			return view('User::frontend.bookingCheckIn', $data);
		} catch (Exception $e) {
			$error = "Error in Sending SMS";
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking Checkin',
				'sms_prearrival_message' => '',
				'sms_arrival_message' => $error,
				'failed' => '',
				'success' => '',
				'error' => ''
			];
			return view('User::frontend.bookingCheckIn', $data);
		}
	}

	public function sendemaillatecheckin(Request $request)
	{
		$id = $request->id;
		$booking = Booking::where('id', $id)->first();
		//$vendor=Vendor::where('vendor_id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$message1 = $space->host_reminder_text;
		$spaceuser = $space->create_user;
		$spaceuserdetails = User::where('id', $spaceuser)->first();
		$receiveremail = $spaceuserdetails->email;
		$bookinglink = url('user/booking-details/') . "/" . $id;

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}
		$checkinurl = url('user/booking-details/') . "/" . $id;

		//$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$id;
		// $message1=str_replace("{FirstName}",$spaceuserdetails->first_name,$message1);
		$message1 = str_replace("{FirstName}", $booking->first_name, $message1);

		$message1 = str_replace("{bookingno}", $booking->id, $message1);
		$message1 = str_replace("{listingname}", $space->title, $message1);
		$message1 = str_replace("{arrivaltime}", $booking->start_date, $message1);
		$message1 = str_replace("{departuretime}", $booking->end_date, $message1);
		// $message1=str_replace("{bookinglink}",$bookinglink,$message1);
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
		//	$message = "Your MyOffice booking at ".$space->title." is ending in 15 minutes. Kindly prepare the office for departure and ensure the space is ready for next tenant. <a href=''>Click Here to Extend Your Booking</a>";

		// $EmailSubject = EmailSubject::where('token', 'j2fr691')->first();
		// $EmailTemplate = EmailTemplate::where('domain', 9)->where('subject_id', $EmailSubject['id'])->first();
		// Mail::to('asiridula@gmail.com')->send(new UserCheckIn($booking, $EmailSubject['subject'], $EmailTemplate,$vendor));
		//User::find($this->vendor_id)

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host
			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');
			$mail->addAddress($receiveremail);
			$mail->addReplyTo('hemantdesai2009@gmail.com', 'MyOffice');

			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
			$mail->addReplyTo($emailusername, 'Myoffice');
			$mail->isHTML(true);                // Set email content format to HTML
			$checkinurl = url('user/booking-details/') . "/" . $booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $booking->id;
			$mail->Subject = "Reminder Email for Guest Not Checked In";
			/*
																														$body	  = "Dear".$booking->first_name.", <br/><br/>";
																														$body	  = $body."Your Guest has not yet checked in for #Booking No.".$booking->id;
																														$body    = $body."Please manually CheckIN the guest or contact them to ";
																														$body    = $body."verify if they are still going to complete their scheduled booking."."<br/><br/>";
																														$body    = $body."Booking Details :";
																														$body    = $body."Listing Name :".$space->title;
																														$body    = $body."Arrival Date and Time :".$booking.start_date;
																														$body    = $body."Departure Date and Time :".$booking.end_date;
																														$body    = $body."Cancellation Fee :";
																														$body    = $body."<a href='".$checkinurl."'>Contact Guest</a> | <a href='".$checkinurl."'>Manual Check In</a> | <a href='".$checkinurl."'>Edit Booking</a>";
																														*/
			$body = $message1;
			$mail->Body = $body;
			$mail->AltBody = $body;

			if (!$mail->send()) {
				$data = [
					'booking' => $booking,
					'page_title' => 'User Booking Checkin',
					'sms_prearrival_message' => '',
					'sms_arrival_message' => '',
					'failed' => 'Email not Sent',
					'success' => '',
					'error' => ''
				];
				return view('User::frontend.bookingCheckIn', $data);
			} else {
				$data = [
					'booking' => $booking,
					'page_title' => 'User Booking Checkin',
					'sms_prearrival_message' => '',
					'sms_arrival_message' => '',
					'failed' => '',
					'success' => 'Email has been sent',
					'error' => ''
				];
				return view('User::frontend.bookingCheckIn', $data);
			}

		} catch (Exception $e) {
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking Checkin',
				'sms_prearrival_message' => '',
				'sms_arrival_message' => '',
				'failed' => '',
				'success' => '',
				'error' => 'Error in sending Email'
			];
			return view('User::frontend.bookingCheckIn', $data);
		}
	}

	public function contactguest(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::where('id', $id)->first();
		$receiveremail = $booking->email;
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$emailtext = $request->emailtext1;

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host

			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');



			$mail->addAddress($receiveremail);
			$mail->addReplyTo($emailusername, 'Myoffice');

			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $booking->id;

			$mail->Subject = "Reminder Email for Guest Check In";
			$body = "Dear" . $booking->first_name . ", <br/><br/>";
			$body = $body . $emailtext . "<br/><br/>";
			$body = $body . "Booking Details :" . "<br/>";
			$body = $body . "Listing Name :" . $space->title . "<br/>";
			$body = $body . "Arrival Date and Time :" . $booking->start_date . "<br/>";
			$body = $body . "Departure Date and Time :" . $booking->end_date . "<br/>";
			$body = $body . "<a href='" . $checkinurl . "'>Contact Guest</a>| <a href='" . $checkinurl . "'>Manual Check In</a> | <a href='" . $checkinurl . "'>Edit Booking</a><br/><br/>";
			$body = $body . "Thank you :" . "<br/><br/>";
			$body = $body . "Management MyOfice Team";
			$mail->Body = $body;
			$mail->AltBody = $body;
			if (!$mail->send()) {
				return redirect()->back()->with('error', __('Error in Sending Guest Email.'));
			} else {
				return redirect()->back()->with('success', __('Contact Guest Email Sent'));
			}

		} catch (Exception $e) {
			return redirect()->back()->with('error', __('Error in Sending Guest Email.'));
		}
	}

	public function contactSubmit(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$spacecreateuser = $space->create_user;
		$spaceuser = User::where('id', $spacecreateuser)->first();
		$receiveremail = $spaceuser->email;
		$emailtext = $request->emailtext1;

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host
			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');

			$mail->addAddress($receiveremail);
			$mail->addReplyTo($emailusername, 'Myoffice');
			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $booking->id;
			$mail->Subject = "Reminder Email for Guest Check In";
			$body = "Dear" . $booking->first_name . ", <br/><br/>";
			$body = $body . $emailtext . "<br/><br/>";
			$body = $body . "Booking Details :" . "<br/>";
			$body = $body . "Listing Name :" . $space->title . "<br/>";
			$body = $body . "Arrival Date and Time :" . $booking->start_date . "<br/>";
			$body = $body . "Departure Date and Time :" . $booking->end_date . "<br/>";
			$body = $body . "<a href='" . $checkinurl . "'>Contact Guest</a>| <a href='" . $checkinurl . "'>Manual Check In</a> | <a href='" . $checkinurl . "'>Edit Booking</a><br/><br/>";
			$body = $body . "Thank you :" . "<br/><br/>";
			$body = $body . "Management MyOfice Team";
			$mail->Body = $body;
			if (!$mail->send()) {
				return redirect()->back()->with('error', __('Error in Sending Guest Email.'));
			} else {
				return redirect()->back()->with('success', __('Contact Host Email Sent'));
			}

		} catch (Exception $e) {
			return redirect()->back()->with('error', __('Error in Sending Host Email.'));
		}
	}

	public function contactHost(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$spacecreateuser = $space->create_user;
		$spaceuser = User::where('id', $spacecreateuser)->first();
		$receiveremail = $spaceuser->email;
		$emailtext = $request->emailtext1;

		if (auth()->user() != null) {

			$userId = auth()->user()->id;


			// echo $request->input('notes');

			$message = $request->input('notes');

			CRMHelper::sendMessage(
				$userId,
				"Re:  Booking #" . $id . "
-----------
" . $message . "
",
				$spaceuser->id
			);

		}

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host

			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');

			$mail->addAddress($receiveremail);
			$mail->addReplyTo($emailusername, 'Myoffice');

			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $booking->id;
			$mail->Subject = "Message from MyOffice for Host";
			/*
																																 $body	  = "Dear".$booking->first_name.", <br/><br/>";
																																 $body	  = $body.$emailtext."<br/><br/>";
																																 $body    = $body."Booking Details :"."<br/>";
																																 $body    = $body."Listing Name :".$space->title."<br/>";
																																 $body    = $body."Arrival Date and Time :".$booking->start_date."<br/>";
																																 $body    = $body."Departure Date and Time :".$booking->end_date."<br/>";
																																 $body    = $body."<a href='".$checkinurl."'>Contact Guest</a>| <a href='".$checkinurl."'>Manual Check In</a> | <a href='".$checkinurl."'>Edit Booking</a><br/><br/>";
																																 $body    = $body."Thank you :"."<br/><br/>";
																																 $body    = $body."Management MyOfice Team";
																																 */
			$body = $request->notes;
			$mail->Body = $body;
			if (!$mail->send()) {
				return redirect()->back()->with('error', __('Error in Sending Guest Email.'));
			} else {
				return redirect()->back()->with('success', __('Contact Host Email Sent'));
			}

		} catch (Exception $e) {
			return redirect()->back()->with('error', __('Error in Sending Host Email.'));
		}
	}




	public function thankyouemail(Request $request)
	{
		$id = $request->booking_id;
		$booking = Booking::where('id', $id)->first();
		$receiveremail = $booking->email;
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
		$emailtext = $request->emailtext;

		$settings = Settings::where('group', 'email')->get();
		for ($i = 0; $i < sizeof($settings); $i++) {
			if ($settings[$i]['name'] == "email_driver") {
				$emaildriver = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_host") {
				$emailhost = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_port") {
				$emailport = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_encryption") {
				$emailencryption = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_username") {
				$emailusername = $settings[$i]['val'];
			}
			if ($settings[$i]['name'] == "email_password") {
				$emailuserpassword = $settings[$i]['val'];
			}
		}

		$mail = new PHPMailer(true);     // Passing `true` enables exceptions
		try {

			// Email server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $emailhost;             //  smtp host

			$mail->SMTPAuth = true;
			$mail->Username = $emailusername;
			$mail->Password = $emailuserpassword;       // sender password
			$mail->SMTPSecure = $emailencryption;                  // encryption - ssl/tls
			$mail->Port = $emailport;                          // port - 587/465
			$mail->setFrom($emailusername, 'Myoffice');
			$mail->addAddress($receiveremail);

			$mail->addReplyTo($emailusername, 'Myoffice');

			$mail->isHTML(true);                // Set email content format to HTML
			//	$checkinurl="http://myofficedev.mybackpocket.co/user/booking-details/".$booking->id;
			$checkinurl = url('user/booking-details/') . "/" . $id;
			$mail->Subject = "Reminder Email for Guest Not Checked In";
			$body = "Dear" . $booking->first_name . ", <br/><br/>";
			$body = $body . $emailtext;
			$body = $body . "Booking Details :";
			$body = $body . "Listing Name :" . $space->title;
			$body = $body . "Arrival Date and Time :" . $booking->start_date;
			$body = $body . "Departure Date and Time :" . $booking->end_date . "<br/><br/>";
			$body = $body . "Thank you :" . "<br/><br/>";
			$body = $body . "Management MyOfice Team";
			$mail->Body = $body;

			if (!$mail->send()) {
				return redirect()->back()->with('error', __('Error in Sending Thank you Email.'));
			} else {
				return redirect()->back()->with('success', __('Thank you Email Sent.'));
			}

		} catch (Exception $e) {
			return redirect()->back()->with('error', __('Error in Sending Thank you Email.'));
		}
	}

	public function sendsmspredeparture(Request $request)
	{
		$id = $request->id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
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
		$receiverNumber = $booking->phone;
		$bookinglink = url('user/booking-details/') . "/" . $id;
		$message1 = $space->departure_reminder_text;
		$message1 = str_replace("{spacename}", $space->title, $message1);
		$message1 = str_replace("{departure_reminder_time}", $booking->departure_reminder_time, $message1);
		$message1 = str_replace("{bookinglink}", $bookinglink, $message1);
		try {

			$account_sid = $smstwilioaccountsid;
			$auth_token = $smstwilioaccounttoken;
			$twilio_number = $smstwiliofromno;

			$client = new Client($account_sid, $auth_token);
			$client->messages->create($receiverNumber, [
				'from' => $twilio_number,
				'body' => $message1
			]);
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking CheckOut',
				'sms_predeparture_message' => 'SMS Sent Successfully',
				'sms_latecheckout_message' => ''
			];
			return view('User::frontend.bookingCheckOut', $data);
		} catch (Exception $e) {
			$error = "Error in Sending SMS";
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking CheckOut',
				'sms_predeparture_message' => $error,
				'sms_latecheckout_message' => ''
			];
			return view('User::frontend.bookingCheckOut', $data);
		}
	}

	public function sendsmslatecheckout(Request $request)
	{
		$id = $request->id;
		$booking = Booking::where('id', $id)->first();
		$spaceid = $booking->object_id;
		$space = Space::where('id', $spaceid)->first();
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
		$receiverNumber = $booking->phone;
		$checkouturl = url('user/booking-details/') . "/" . $id;
		$message1 = $space->latecheckout_reminder_text;
		$message1 = str_replace("{bookingno}", $booking->id, $message1);

		$message1 = str_replace("{spacename}", $space->title, $message1);
		$message1 = str_replace("{checkouturl}", $checkouturl, $message1);
		try {

			$account_sid = $smstwilioaccountsid;
			$auth_token = $smstwilioaccounttoken;
			$twilio_number = $smstwiliofromno;

			$client = new Client($account_sid, $auth_token);
			$client->messages->create($receiverNumber, [
				'from' => $twilio_number,
				'body' => $message1
			]);
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking CheckOut',
				'sms_predeparture_message' => '',
				'sms_latecheckout_message' => 'SMS Sent Successfully'
			];
			return view('User::frontend.bookingCheckOut', $data);
		} catch (Exception $e) {
			$error = "Error in Sending SMS";
			$data = [
				'booking' => $booking,
				'page_title' => 'User Booking CheckOut',
				'sms_predeparture_message' => '',
				'sms_latecheckout_message' => $error
			];
			return view('User::frontend.bookingCheckOut', $data);
		}
	}

	public function ticket($code = '')
	{
		$booking = Booking::where('code', $code)->first();
		$user_id = Auth::id();
		if (empty($booking)) {
			return redirect('user/booking-history');
		}
		if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
			return redirect('user/booking-history');
		}
		$data = [
			'booking' => $booking,
			'service' => $booking->service,
			'page_title' => __("Ticket")
		];
		return view('User::frontend.booking.ticket', $data);
	}
}
