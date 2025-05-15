<?php

namespace App\Helpers;

use App\Models\CreditCoupons;
use App\Models\TaskData;
use Bavix\Wallet\Models\Wallet;
use Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Modules\Booking\Models\Booking;
use Modules\Core\Models\Settings;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Models\CouponBookings;
use Modules\Media\Models\MediaFile;
use Modules\Review\Models\Review;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceTerm;
use Modules\User\Emails\TestEmail;
use Modules\User\Models\Wallet\Transaction;
use Illuminate\Support\Str;
use Modules\Booking\Models\Payment;
use App\Models\Transactions;
use App\Models\UserTransactionDetails;

class CodeHelper
{
    public static function pageResponse($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 7,
            CURLOPT_POST => false
        ]);
        $response = curl_exec($ch);
        if ($response) {
            return json_decode($response, true);
        }
        return false;
    }

    public static function getAMPMFromHourTime($hour)
    {
        $fullTime = "1970-01-01 " . $hour . ":00";
        return date('A', strtotime($fullTime));
    }

    public static function getSmallMinTimeFromHourTime($hour)
    {
        $fullTime = "1970-01-01 " . $hour . ":00";
        return date('H:i', strtotime($fullTime));
        //return date('h:i', strtotime($fullTime));
    }

    public static function getAfterLoginRedirectUrl(\Illuminate\Http\Request $request)
    {
        $redirectTo = "/user/user-dashboard";

        if (Auth::user()->hasPermissionTo('dashboard_vendor_access')) {
            $redirectTo = '/user/dashboard';
        } elseif (Auth::user()->hasPermissionTo('dashboard_access')) {
            $redirectTo = '/admin';
        }

        //$redirectUrl = $request->input('redirect') ?? $request->headers->get('referer') ?? url(app_get_locale(false, $redirectTo));
        $redirectUrl = $request->input('redirect');

        if ($redirectUrl == null) {
            $redirectUrl = url($redirectTo);
        }

        if (Session::has('afterLoginRedirect')) {
            $redirectUrl = Session::pull('afterLoginRedirect');
        }
        return $redirectUrl;
    }

    public static function withAppUrl($path)
    {
        $path = ltrim($path, '/');
        return url('') . '/' . $path;
    }

    public static function getFullUrl()
    {
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function getReferrer()
    {
        // Check if the HTTP_REFERER is set
        if (!empty($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }

        // Return null if no referrer is set
        return self::getFullUrl();
    }

    public static function getDiscountedPrice($rate, $discount)
    {
        return number_format((float) $rate * (1 - $discount / 100), 2, '.', '');
    }

    public static function getValueOrDefault($value, $default = null)
    {
        if ($value != null) {
            return $value;
        }
        return $default;
    }

    public static function checkIfNumValNotNull($value)
    {
        if ($value != null && $value > 0) {
            return true;
        }
        return false;
    }

    public static function getNumValueOrDefault($value, $default = null)
    {
        if (self::checkIfNumValNotNull($value)) {
            return $value;
        }
        return $default;
    }


    public static function getBookingPriceInfo($booking)
    {
        return self::getSpacePrice(
            Space::where('id', $booking->object_id)->first(),
            $booking->start_date,
            $booking->end_date,
            $booking->id
        );
    }

    public static function getFormatBookingPrice($price)
    {
        return str_replace('$', '', $price);
    }

    public static function getSpacePrice($space, $from, $to, $bookingId = null)
    {
        $items = [];
        $price = 0;
        if ($space != null) {
            $start = new \DateTime($from);
            $start->modify('-1 seconds');

            $end = new \DateTime($to);
            $end->modify('+1 seconds');

            $difference = $start->diff($end, true);

            // $totalDays = 0;
            // $totalHours = 0;

            // $months = $difference->m;
            // $days = $difference->d;
            // $hours = $difference->h;
            // $minutes = $difference->i;
            // $weeks = floor($difference->d / 7);

            //old logic

            // $totalDays = $difference->days;
            // $totalHours = $difference->h + $difference->days * 24;

            // $months = $difference->m;
            // $weeks = floor($difference->d / 7);

            // $days = $difference->d - ($weeks * 7);

            // $hours = $difference->h;
            // $minutes = $difference->i;


            //new 28 days a month logic
            $totalDays = $difference->days;

            $totalHours = $difference->h + $difference->days * 24;
            $months = floor($totalDays / 28);
            $weeks = floor(($totalDays % 28) / 7);

            $days = $totalDays % 7;

            $hours = $difference->h;
            $minutes = $difference->i;

            if ($minutes > 0) {
                $hours++;
            }

            $maxHoursPerDay = trim($space->hours_after_full_day);
            if ($maxHoursPerDay == null) {
                $maxHoursPerDay = 24;
            }

            if ($hours > $maxHoursPerDay) {
                $hours = 0;
                $days++;
            }

$hours++;
$days++;

            $diff = strtotime($to) - strtotime($from);
            $totalDaysDifference = abs(ceil(($diff)));

            $listingPrice = self::getNumValueOrDefault($space->sale_price, $space->price);

            $monthlyPrice = self::getNumValueOrDefault($space->discounted_monthly, $space->monthly);
            $dayPrice = self::getNumValueOrDefault($space->discounted_daily, $space->daily);
            $hourPrice = self::getNumValueOrDefault($space->discounted_hourly, $space->hourly);
            $weekPrice = self::getNumValueOrDefault($space->discounted_weekly, $space->weekly);



            $totalMonthPrice = $totalDayPrice = $totalHourPrice = $totalWeekPrice = $otherPrice = 0;

            if (
                self::checkIfNumValNotNull($monthlyPrice) && self::checkIfNumValNotNull($dayPrice) &&
                self::checkIfNumValNotNull($hourPrice) && self::checkIfNumValNotNull($weekPrice)
            ) {

                if ($months > 0) {
                    $totalMonthPrice = ($monthlyPrice * $months);
                    $items[] = [
                        'type' => 'months',
                        'quantity' => $months,
                        'rate' => $monthlyPrice,
                        'total' => $totalMonthPrice
                    ];
                }

                if ($days > 0) {
                    $totalDays = $days;
                    $totalHours = ($days * 24);
                    $totalDayPrice = ($dayPrice * $days);
                    $items[] = [
                        'type' => 'days',
                        'quantity' => $days,
                        'rate' => $dayPrice,
                        'total' => $totalDayPrice
                    ];
                } else {
                    $totalDays = 1;
                }

                if ($hours > 0) {
                    $totalHours += $hours;
                    $totalHourPrice = ($hourPrice * $hours);
                    $items[] = [
                        'type' => 'hours',
                        'quantity' => $hours,
                        'rate' => $hourPrice,
                        'total' => $totalHourPrice
                    ];
                }

                if ($weeks > 0) {
                    $totalWeekPrice = ($weekPrice * $weeks);
                    $items[] = [
                        'type' => 'weeks',
                        'quantity' => $weeks,
                        'rate' => $weekPrice,
                        'total' => $totalWeekPrice
                    ];
                }
            } else {
                $dayPrice = $listingPrice;
                if ($totalDaysDifference > 0) {
                    $otherPrice = ($totalDaysDifference * $listingPrice);
                    $items[] = [
                        'type' => 'days',
                        'quantity' => $totalDaysDifference,
                        'rate' => $listingPrice,
                        'total' => ($listingPrice * $totalDaysDifference)
                    ];
                }
            }


            $price = ($totalMonthPrice + $totalDayPrice + $totalHourPrice + $totalWeekPrice + $otherPrice);

            if ($days == 0 && $hours > 0 && $weeks == 0 && $months == 0) {
                if ($price > $dayPrice) {
                    $price = $dayPrice;
                }
            } elseif ($weeks == 0 && $days > 0 && $months == 0) {
                if ($price > $weekPrice) {
                    $price = $weekPrice;
                }
            } elseif ($months == 0 && $weeks > 0) {
                if ($price > $monthlyPrice) {
                    $price = $monthlyPrice;
                }
            }

            // print_r([
            //     'monthly' => [
            //         'count' => $months,
            //         'price' => $monthlyPrice,
            //         'total' => $totalMonthPrice,
            //     ],
            //     'days' => [
            //         'count' => $days,
            //         'price' => $dayPrice,
            //         'total' => $totalDayPrice,
            //     ],
            //     'hours' => [
            //         'count' => $hours,
            //         'price' => $hourPrice,
            //         'total' => $totalHourPrice,
            //     ],
            //     'weeks' => [
            //         'count' => $weeks,
            //         'price' => $weekPrice,
            //         'total' => $totalWeekPrice,
            //     ],
            //     'other' => $otherPrice,
            //     'minutes' => $minutes,
            //     'totalDaysDifference' => $totalDaysDifference,
            //     'price' => $price,
            //     'start' => $start->format('Y-m-d H:i:s'),
            //     'end' => $end->format('Y-m-d H:i:s'),
            //     'difference' => $difference
            // ]);
            // die;

        }
        //dd($price);

        $priceDetails = [
            'items' => $items,

            'extraFeeList' => [],
            'guestFeeList' => [],
            'hostFeeList' => [],

            'timeInfo' => [
                'hours' => $hours,
                'days' => $days,
                'weeks' => $weeks,
                'months' => $months,
            ],

            'price' => $price,

            'extraFee' => 0,
            'priceAfterExtraFee' => $price,

            'guestFee' => 0,
            'priceAfterGuestFee' => $price,

            'tax' => 0,
            'priceAfterTax' => $price,

            'couponType' => 0,
            'discount' => 0,
            'payableAmount' => $price,

            'hostFee' => 0,

            'adminAmount' => 0,
            'hostAmount' => 0,
        ];

        if ($priceDetails['price'] > 0) {

            $guestFees = json_decode(setting_item('space_booking_buyer_fees'), true);
            $hostFees = json_decode(setting_item('space_booking_seller_fees'), true);

            $extraPrices = $space->extra_price;

            if (is_array($extraPrices) && count($extraPrices) > 0) {
                foreach ($extraPrices as $extraPriceItem) {
                    $extraFeeRow = 0;
                    switch ($extraPriceItem['type']) {
                        case 'one_time':
                            if ($extraPriceItem['price'] != null) {
                                $extraFeeRow = $extraPriceItem['price'];
                            }
                            break;
                        case 'per_hour':
                            if ($extraPriceItem['price'] != null) {
                                $extraFeeRow = ($totalHours * $extraPriceItem['price']);
                            }
                            break;
                        case 'per_day':
                            if ($extraPriceItem['price'] != null) {
                                $extraFeeRow = ($totalDays * $extraPriceItem['price']);
                            }
                            break;
                    }
                    $extraFeeRowItem = $extraPriceItem;
                    $extraFeeRowItem['totalAmount'] = $extraFeeRow;
                    $priceDetails['extraFee'] = $priceDetails['extraFee'] + $extraFeeRow;
                    $priceDetails['extraFeeList'][] = $extraFeeRowItem;
                }
            }

            $priceDetails['priceAfterExtraFee'] = $priceDetails['price'] + $priceDetails['extraFee'];

            if (is_array($guestFees) && count($guestFees) > 0) {
                foreach ($guestFees as $guestFee) {
                    $guestRowFee = 0;
                    if ($guestFee['unit'] == "fixed") {
                        $guestRowFee = ($guestFee['price'] * 1);
                    } elseif ($guestFee['unit'] == "percent") {
                        $guestRowFee = (($guestFee['price'] * $priceDetails['priceAfterExtraFee']) / 100);
                    }
                    $priceDetails['guestFee'] = $priceDetails['guestFee'] + $guestRowFee;
                    $guestFeeInfo = $guestFee;
                    $guestFeeInfo['totalAmount'] = $guestRowFee;
                    $priceDetails['guestFeeList'][] = $guestFeeInfo;
                }
            }

            $priceDetails['priceAfterGuestFee'] = $priceDetails['priceAfterExtraFee'] + $priceDetails['guestFee'];

            $priceDetails['tax'] = (($priceDetails['priceAfterGuestFee'] * Constants::TAX_PERCENT));
            $priceDetails['priceAfterTax'] = ($priceDetails['priceAfterGuestFee'] + $priceDetails['tax']);

            //host fee is calculated on priceAfterExtraFee
            if (is_array($hostFees) && count($hostFees) > 0) {
                foreach ($hostFees as $hostFee) {
                    $hostRowFee = 0;
                    if ($hostFee['unit'] == "fixed") {
                        $hostRowFee = ($hostFee['price'] * 1);
                    } elseif ($hostFee['unit'] == "percent") {
                        $hostRowFee = (($hostFee['price'] * $priceDetails['priceAfterExtraFee']) / 100);
                    }
                    $priceDetails['hostFee'] = $priceDetails['hostFee'] + $hostRowFee;
                    $guestFeeInfo = $hostFee;
                    $guestFeeInfo['totalAmount'] = $hostRowFee;
                    $priceDetails['hostFeeList'][] = $guestFeeInfo;
                }
            }

            $coupon_type = "global"; //global/space

            $couponOffPrice = 0;

            if ($bookingId != null) {

                $bookingCoupons = CouponBookings::where('booking_id', $bookingId)->get();
                if ($bookingCoupons != null) {
                    foreach ($bookingCoupons as $bookingCoupon) {
                        $couponData = ($bookingCoupon['coupon_data']);
                        if ($couponData['object_id'] != null) {
                            $coupon_type = "space";
                        }
                        $priceDetails['couponType'] = $coupon_type;
                        $couponOffPriceN = 0;
                        if ($couponData['discount_type'] == "fixed") {
                            $couponOffPriceN = $couponData['amount'];
                        } elseif ($couponData['discount_type'] == "percent") {
                            $couponOffPriceN = ($priceDetails['priceAfterExtraFee'] * $couponData['amount']) / 100;
                        }

                        $bookingCoupon->coupon_amount = $couponOffPriceN;
                        $bookingCoupon->save();

                        $couponOffPrice += $couponOffPriceN;

                    }
                }
            }

            $priceDetails['discount'] += $couponOffPrice;
            //coupon

            //deduct discount
            $priceDetails['payableAmount'] = ($priceDetails['priceAfterTax']);

            $priceDetails['adminAmount'] = $priceDetails['hostFee'] + $priceDetails['tax'] + $priceDetails['guestFee'];
            $priceDetails['hostAmount'] = $priceDetails['payableAmount'] - $priceDetails['adminAmount'];

            if ($priceDetails['discount'] > $priceDetails['priceAfterTax']) {
                $priceDetails['discount'] = $priceDetails['priceAfterTax'];
            }
            //reupdate with discount
            $priceDetails['payableAmount'] = ($priceDetails['priceAfterTax'] - $priceDetails['discount']);
            if ($priceDetails['payableAmount'] < 0) {
                $priceDetails['payableAmount'] = 0;
            }

            if ($coupon_type == "global") {
                $priceDetails['adminAmount'] = $priceDetails['adminAmount'] - $priceDetails['discount'];
            } else {
                $priceDetails['hostAmount'] = $priceDetails['hostAmount'] - $priceDetails['discount'];
            }


        }

        $priceDetails['rentalTotal'] = $priceDetails['price'] + $priceDetails['extraFee'];
        $priceDetails['subTotal'] = $priceDetails['rentalTotal'] + $priceDetails['guestFee'];
        $priceDetails['total'] = $priceDetails['subTotal'] + $priceDetails['tax'];
        $priceDetails['grandTotal'] = $priceDetails['total'] - $priceDetails['discount'];

        // dd($priceDetails);

        $priceDetails['priceInfoHtml'] = View::make('components/_space_prices')->with('priceDetails', $priceDetails)->render();
        $priceDetails['itemInfoHtml'] = View::make('components/_space_price_item')->with('priceDetails', $priceDetails)->render();
        // dd($priceDetails);



        return $priceDetails;
    }

    public static function formatPrice($value, $zeroifNull = false)
    {
        if (!self::checkIfNumValNotNull($value)) {
            if ($zeroifNull) {
                $value = 0;
            } else {
                return '-';
            }
        }
        return '$' . number_format($value, 2);
    }

    public static function formatNumber($value, $zeroifNull = false)
    {
        return $value;
    }

    public static function haveValidPrice($moneyVal)
    {
        if ($moneyVal != null) {
            $moneyVal = trim($moneyVal);
            if ($moneyVal != "") {
                if ($moneyVal > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function totalUnreadNotifications($notifications)
    {
        $totalUnRead = 0;
        if ($notifications != null) {
            foreach ($notifications as $notification) {
                if (empty($notification->read_at)) {
                    $totalUnRead++;
                }
            }
        }
        return $totalUnRead;
    }

    public static function totalUnreadEmails($emails)
    {
        $totalUnRead = 0;
        if ($emails != null) {
            foreach ($emails as $email) {
                if (empty($email->read_at)) {
                    $totalUnRead++;
                }
            }
        }
        return $totalUnRead;
    }

    public static function numWeeks($dateOne, $dateTwo)
    {
        //Create a DateTime object for the first date.
        $firstDate = new \DateTime($dateOne);
        //Create a DateTime object for the second date.
        $secondDate = new \DateTime($dateTwo);
        //Get the difference between the two dates in days.
        $differenceInDays = $firstDate->diff($secondDate)->days;
        //Divide the days by 7
        $differenceInWeeks = $differenceInDays / 7;
        //Round down with floor and return the difference in weeks.
        return floor($differenceInWeeks);
    }

    public static function getVendorSpaceIds($user_id)
    {
        $vendorSpaceIds = Space::where('create_user', $user_id)->pluck('id')->toArray();
        if ($vendorSpaceIds == null) {
            $vendorSpaceIds = [-1];
        }
        return $vendorSpaceIds;
    }

    public static function getRatingsBySpaces($spaceIds)
    {
        // dd($spaceIds);
        $ratingsNumbers = [
            1 => ['from' => 0, 'to' => 1],
            2 => ['from' => 1.1, 'to' => 2],
            3 => ['from' => 2.1, 'to' => 3],
            4 => ['from' => 3.1, 'to' => 4],
            5 => ['from' => 4.1, 'to' => 5],
        ];
        $spaceRatings = [];
        $totalRatings = Review::whereIn('object_id', $spaceIds)->where('object_model', 'space')
            ->where('review_by', 'guest')->count();
        if ($totalRatings == null) {
            $totalRatings = 0;
        }

        foreach ($ratingsNumbers as $ratingsNumber => $range) {
            $totalCount = 0;
            $percentage = 0;
            if ($totalRatings > 0) {
                $totalCountData = Review::whereIn('object_id', $spaceIds)->where('object_model', 'space')
                    ->where('rate_number', '>=', $range['from'])->where('rate_number', '<=', $range['to'])
                    ->selectRaw('count(*) as totalRating')
                    ->where('review_by', 'guest')->first()->toArray();
                $totalCount = $totalCountData['totalRating'];
                $percentage = ($totalCount * 100) / $totalRatings;
                $percentage = round($percentage, 2);
            }
            $spaceRatings[$ratingsNumber] = [
                'totalRatings' => $totalCount,
                'percentage' => $percentage,
            ];
        }
        return $spaceRatings;
    }

    public static function getAnalyticsBySpaces($spaceIds)
    {
        $analyticsTypes = ['views', 'clicks', 'bookings', 'cancellations', 'repeat'];
        $spaceAnalytics = [];
        foreach ($analyticsTypes as $analyticsType) {
            $spaceAnalytics[$analyticsType] = [
                'total' => 0,
                'percentage' => 0,
            ];
        }

        $totalValue = 0;

        foreach ($analyticsTypes as $analyticsType) {
            $totalR = 0;
            switch ($analyticsType) {
                case "views":
                    $totalR = Space::whereIn('id', $spaceIds)->sum('views');
                    break;
                case "clicks":
                    $totalR = Space::whereIn('id', $spaceIds)->sum('clicks');
                    break;
                case "bookings":
                    $totalR = Booking::where('object_model', 'space')->whereIn('status', [
                        Constants::BOOKING_STATUS_BOOKED,
                        Constants::BOOKING_STATUS_CHECKED_IN,
                        Constants::BOOKING_STATUS_CHECKED_OUT,
                        Constants::BOOKING_STATUS_COMPLETED
                    ])->whereIn('object_id', $spaceIds)->count();
                    break;
                case "cancellations":
                    $totalR = Booking::where('object_model', 'space')->whereIn('status', [
                        Constants::BOOKING_STATUS_CANCELLED
                    ])->whereIn('object_id', $spaceIds)->count();
                    break;
                case "repeat":
                    $totalR = Booking::whereIn('object_id', $spaceIds)->whereIn('status', array_keys(Constants::BOOKING_STATUES))->where('object_model', 'space')->selectRaw('count(*) as totalBooked')->groupBy('customer_id')->havingRaw('COUNT(*) > 1')->count();
                    // print_r($repeated);die;
                    break;
            }
            if (array_key_exists($analyticsType, $spaceAnalytics)) {
                $spaceAnalytics[$analyticsType]['total'] = $totalR;
                $totalValue += $totalR;
            }
        }

        // print_r($spaceAnalytics);die;

        foreach ($spaceAnalytics as $key => $val) {
            if ($val['total'] > 0 && $totalValue > 0) {
                $percentage = ($val['total'] * 100) / $totalValue;
                $spaceAnalytics[$key]['percentage'] = round($percentage, 2);
            }
        }



        return $spaceAnalytics;
    }

    public static function cleanArray($list)
    {
        if (is_array($list) && count($list) > 0) {
            foreach ($list as $k => $v) {
                if (is_array($v)) {
                    $v = self::cleanArray($v);
                    if (is_array($v) && count($v) <= 0) {
                        unset($k);
                    }
                } else {
                    $v = trim($v);
                    if ($v == null) {
                        unset($k);
                    }
                }
            }
        }
        return $list;
    }

    public static function formatDateTime($time, $returnNull = false, $defaultValue = '')
    {
        if ($time) {
            if (is_string($time)) {
                $time = strtotime($time);
            }

            if (is_object($time)) {
                return $time->format(get_date_format());
            }
        } else {
            if ($returnNull) {
                return $defaultValue;
            }
            $time = strtotime(today());
        }

        return date("M j, Y | h:i A", $time);
    }

    public static function getTableColumns($tableName, $includeTableName = true)
    {
        $columns = [];
        $attributes = Schema::getColumnListing(Booking::getTableName());
        foreach ($attributes as $attribute) {
            if ($includeTableName) {
                $attribute = $tableName . "." . $attribute;
            }
            $columns[] = $attribute;
        }
        return $columns;
    }

    public static function passDataToDatatableResponse($response, $additionalData = null)
    {
        if ($response instanceof JsonResponse) {
            $current_data = $response->getData();
            $data = json_decode(json_encode($current_data->data), true);
            if (is_array($data) && count($data) > 0) {
                $data[0]['additionalData'] = $additionalData;
            }
            $current_data->data = $data;
            $response->setData($current_data);
        }
        return $response;
    }

    public static function dataTableOptions()
    {
        return [
            "serverSide" => true,
            "sDom" => '<"H">rt<"F data-table-footer"lip>',
            "destroy" => true,
            "pageLength" => 10,
            "sPaginationType" => "full_numbers",
            "lengthMenu" => [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "order" => [
                [0, "asc"]
            ],
            "processing" => true,
            "retrieve" => true,
            "language" => [
                "info" => "Showing _START_ to _END_ of _TOTAL_ records",
                'processing' => '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span>'
            ],
        ];
    }

    public static function isValidDateTimeFormat($string)
    {
        return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $string) === 1;
    }

    public static function dateConvertion($date)
    {
        if (self::isValidDateTimeFormat($date)) {
            return date('Y-m-d', strtotime($date));
        }
        $d = explode('/', $date);
        $date = $d[2] . '-' . $d[0] . '-' . $d[1];
        return $date;
    }

    public static function debugQuery($query)
    {
        print_r($query->getBindings());
        echo $query->toSql();
        die;
    }

    public static function maskPhoneNo($mobileNo)
    {
        // return $mobileNo;
        $mobileNo = preg_replace('/\s+/', '', $mobileNo);
        $firstThree = substr($mobileNo, 0, 3);
        $mobileNo = substr($mobileNo, 3);
        $secondThree = substr($mobileNo, 0, 3);
        $mobileNo = substr($mobileNo, 3);
        return '(' . $firstThree . ') ' . $secondThree . ' ' . $mobileNo;
    }

    public static function getHoursBetweenDates($start, $end)
    {
        $hourdiff = round((strtotime($end) - strtotime($start)) / 3600, 1);
        return $hourdiff;
        $d1 = new \DateTime($start);
        $d2 = new \DateTime($end);
        $interval = $d1->diff($d2);
        return ($interval->days * 24) + $interval->h;
    }

    public static function assignSpacePricingToBooking($booking, $totalInfo)
    {
        $booking->items = json_encode($totalInfo['items']);
        $booking->extra_fee_items = json_encode($totalInfo['extraFeeList']);
        $booking->guest_fee_items = json_encode($totalInfo['guestFeeList']);
        $booking->host_fee_items = json_encode($totalInfo['hostFeeList']);

        $booking->price = $totalInfo['price'];
        $booking->extra_fee = $totalInfo['extraFee'];
        $booking->guest_fee = $totalInfo['guestFee'];
        $booking->tax = $totalInfo['tax'];
        $booking->discount = $totalInfo['discount'];
        $booking->payable_amount = $totalInfo['payableAmount'];
        $booking->total = $totalInfo['payableAmount'];

        $booking->host_fee = $totalInfo['hostFee'];

        $booking->admin_amount = $totalInfo['adminAmount'];
        $booking->host_amount = $totalInfo['hostAmount'];
        return $booking;
    }

    public static function getUserWallet($user = null)
    {
        if ($user == null) {
            $user = Auth::user();
        }
        $wallet = Wallet::where('holder_type', 'App\User')->where('holder_id', $user->id)->first();
        if ($wallet == null) {
            $wallet = new Wallet();
            $wallet->holder_type = 'App\User';
            $wallet->holder_id = $user->id;
            $wallet->name = 'Default Wallet';
            $wallet->slug = 'default';
            $wallet->save();
        }
        return $wallet;
    }

    public static function addUserTransaction(
        $user,
        $type,
        $amount,
        $isDebit,
        $refernceId = null,
        $meta = [],
        $otherFields = [],
        $effectUserBalance = true,
        $markedConfirmed = false,
        $insertPromoCouponCode = false,
        $creditCouponFields = []
    ) {

        $transaction = new Transaction();
        $transaction->payable_type = 'App\User';

        $wallet = null;
        if ($user != null) {
            $wallet = self::getUserWallet($user);
            $transaction->payable_id = $user->id;
            $transaction->wallet_id = $wallet->id;
        } else {
            $transaction->payable_id = null;
            $transaction->wallet_id = null;
        }

        $transaction->type = $type;
        $transaction->amount = $amount;

        $transaction->is_debit = $isDebit;

        if ($refernceId != null) {
            $transaction->reference_id = (string) $refernceId;
            $findTrans = Transaction::where('reference_id', $transaction->reference_id)->first();
            if ($findTrans !== null) {
                // return false;
            }
        }

        $transaction->meta = json_encode($meta);
        $transaction->uuid = Str::uuid();

        if ($transaction->save()) {
            if ($effectUserBalance) {
                // if ($wallet != null) {
                //     if ($isDebit) {
                //         $wallet->balance = $wallet->balance - $amount;
                //     } else {
                //         $wallet->balance = $wallet->balance + $amount;
                //     }
                //     $wallet->save();
                // }
            }
        }

        if ($markedConfirmed) {
            $transaction->confirmed = 2;
            $transaction->save();
        }

        if ($insertPromoCouponCode) {
            if ($isDebit) {

            } else {
                $model = new CreditCoupons($creditCouponFields);
                $model->user_id = $user->id;
                $model->code = $model->generateCode();
                $model->recepient = $user->email;
                $model->amount = $amount;
                $model->used = 0;
                $model->pending = $amount;
                $model->type = $type;
                $model->expired_at = date(Constants::PHP_DATE_FORMAT, strtotime("+20 years"));

                $model->save();

                $user->promo_credits += $model->amount;
                $user->save();
            }
        }

        return [
            'transaction' => $transaction,
            'wallet' => $wallet,
            'user' => $user
        ];
    }

    public static function addUserTransactionExtend(
        $user,
        $type,
        $amount,
        $isDebit,
        $refernceId = null,
        $meta = [],
        $otherFields = [],
        $effectUserBalance = true,
        $markedConfirmed = false,
        $paymentId = null,
        $bookingId = null
    ) {
        $transaction = new Transaction();
        $transaction->payable_type = 'App\User';

        $wallet = null;
        if ($user != null) {
            $wallet = self::getUserWallet($user);
            $transaction->payable_id = $user->id;
            $transaction->wallet_id = $wallet->id;
        } else {
            $transaction->payable_id = null;
            $transaction->wallet_id = null;
        }

        $transaction->type = $type;
        $transaction->amount = $amount;
        $transaction->is_debit = $isDebit;

        if ($refernceId != null) {
            $transaction->reference_id = (string) $refernceId;
            $findTrans = Transaction::where('reference_id', $transaction->reference_id)->first();
            if ($findTrans !== null) {
                return false;
            }
        }

        $transaction->meta = json_encode($meta);
        $transaction->uuid = Str::uuid();
        $transaction->payment_id = $paymentId;
        $transaction->booking_id = $bookingId;
        $transaction->save();

        if ($markedConfirmed) {
            $transaction->confirmed = 1;
            $transaction->save();
        }

        return [
            'transaction' => $transaction,
            'wallet' => $wallet
        ];
    }

    public static function markTransactionConfirmed($transaction, $meta = [])
    {
        $lastMeta = json_decode($transaction->meta, true);
        $transaction->meta = json_encode(array_merge($lastMeta, $meta));
        $transaction->confirmed = 2;
        $transaction->save();
        return $transaction;
    }

    public static function getCurrentUrl()
    {
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function getCurrentUrlWithoutQury()
    {
        $url = self::getCurrentUrl();
        return explode('?', $url)[0];
    }

    public static function getBookingPayments($bookingId)
    {
        $payments = [];
        $models = Payment::where('booking_id', $bookingId)->get();
        if ($models != null) {
            foreach ($models as $model) {
                $time = date(Constants::PHP_DATE_FORMAT, strtotime($model->created_at));
                $method = $amount = $ref = $model->id;
                $logs = json_decode($model->logs, true);
                if (is_array($logs)) {
                    if (array_key_exists('amount', $logs)) {
                        $amount = self::formatPrice($logs['amount']);
                    }
                    if (array_key_exists('pay_type', $logs)) {
                        $method = $logs['pay_type'];
                    }
                }
                $payment = [
                    'time' => $time,
                    'method' => $method,
                    'amount' => $amount,
                    'ref' => $ref,
                ];
                $payments[] = $payment;
            }
        }
        return $payments;
    }

    public static function getBookingTransactions($bookingId)
    {
        $payments = [];
        $models = Transactions::where('order_id', $bookingId)->get();
        if ($models != null) {
            foreach ($models as $model) {
                $time = date(Constants::PHP_DATE_FORMAT, strtotime($model->payment_date));
                $method = ucfirst($model->payment_type);

                $payment = [
                    'time' => $time,
                    'method' => $method,
                    'amount' => $model->amount,
                    'ref' => $model->transaction_id,
                    'status' => $model->status,
                    'full_amount' => $model->full_amount,
                    'credit_card' => $model->credit_card,
                ];
                $payments[] = $payment;
            }
        }
        return $payments;
    }

    public static function getFullCurrentUrl()
    {
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function printDate($date)
    {
        return date('n-j-Y', strtotime($date));
    }

    public static function printDateAndTime($date, $format = 'n-j-Y H:i')
    {
        return date($format, strtotime($date));
    }

    public static function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public static function isNotEmpty($data)
    {
        if ($data !== "undefined" && $data !== "null" && $data !== null && $data !== "") {
            return true;
        }
        return false;
    }

    public static function spacePriceData($space)
    {
        $prices = [];
        $priceType = 'daily';

        $price = 0;
        $discountedPrice = 0;
        $discountRate = 0;
        $discount = 0;

        if ($space->hourly_price_set_default > 0) {
            $priceType = 'hourly';
        } elseif ($space->daily_price_set_default > 0) {
            $priceType = 'daily';
        } elseif ($space->weekly_price_set_default > 0) {
            $priceType = 'weekly';
        } elseif ($space->monthly_price_set_default > 0) {
            $priceType = 'monthly';
        }

        if (self::checkIfNumValNotNull($space->hourly)) {
            $prices['hourly'] = [
                'price' => $space->hourly,
                'discountedPrice' => $space->hourly
            ];
            if (self::checkIfNumValNotNull($space->discounted_hourly)) {
                $prices['hourly']['discountedPrice'] = $space->discounted_hourly;
            }
        }

        if (self::checkIfNumValNotNull($space->daily)) {
            $prices['daily'] = [
                'price' => $space->daily,
                'discountedPrice' => $space->daily
            ];
            if (self::checkIfNumValNotNull($space->discounted_daily)) {
                $prices['daily']['discountedPrice'] = $space->discounted_daily;
            }
        }

        if (self::checkIfNumValNotNull($space->weekly)) {
            $prices['weekly'] = [
                'price' => $space->weekly,
                'discountedPrice' => $space->weekly
            ];
            if (self::checkIfNumValNotNull($space->discounted_weekly)) {
                $prices['weekly']['discountedPrice'] = $space->discounted_weekly;
            }
        }

        if (self::checkIfNumValNotNull($space->monthly)) {
            $prices['monthly'] = [
                'price' => $space->monthly,
                'discountedPrice' => $space->monthly
            ];
            if (self::checkIfNumValNotNull($space->discounted_monthly)) {
                $prices['monthly']['discountedPrice'] = $space->discounted_monthly;
            }
        }

        if (count($prices) <= 0) {
            $prices['daily'] = [
                'price' => $space->price,
                'discountedPrice' => $space->price
            ];
            if (self::checkIfNumValNotNull($space->sale_price)) {
                $prices['daily']['discountedPrice'] = $space->sale_price;
            }
        }

        if (!array_key_exists($priceType, $prices)) {
            $priceType = array_key_first($prices);
        }

        $price = $prices[$priceType]['price'];
        $discountedPrice = $prices[$priceType]['discountedPrice'];

        if (self::checkIfNumValNotNull($price) && self::checkIfNumValNotNull($discountedPrice)) {
            if ($discountedPrice < $price) {
                $discount = ($price - $discountedPrice);
                $discountRate = ($discount * 100) / $price;
                $discountRate = round($discountRate, 2);
            }
        }

        return [
            'priceType' => $priceType,
            'price' => $price,
            'discountedPrice' => $discountedPrice,
            'discount' => $discount,
            'discountRate' => $discountRate,
            'prices' => $prices
        ];
    }

    public static function shortNameForPriceType($priceType)
    {
        switch ($priceType) {
            case 'hourly':
                return 'Hour';
                break;
            case 'daily':
                return 'Day';
                break;
            case 'weekly':
                return 'Week';
                break;
            case 'monthly':
                return 'Month';
                break;
        }
        return '';
    }

    public static function getBookingItemsAndSummary($booking, $role = '')
    {
        // dd($booking);
        $priceDetails = \App\Helpers\CodeHelper::getBookingPriceInfo($booking);

        // dd($priceDetails);

        $items = [];
        $summaryItems = [];

        $finePrintLine = '';
        if ($role === "guest") {
            $finePrintLine = '<p class="fine-print-info">The Fine Print: <a href="/page/terms-of-service" target="_blank">From Us</a> | <a id="hostterms" href="javascript:;">From Your Host</a></p>';
        }

        $items[] = [
            'name' => 'Space Rental Fee',
            'quantity' => 1,
            'amount' => format_money($priceDetails['price']),
            'type' => 'based-fee'
        ];

        $extra_price = ($priceDetails['extraFeeList']);
        if (!empty($extra_price)) {
            foreach ($extra_price as $type) {
                $items[] = [
                    'name' => $type['name'],
                    'quantity' => 1,
                    'amount' => format_money($type['totalAmount'] ?? 0),
                    'type' => 'extra-fee'
                ];
            }
        }

        $guestPrices = ($priceDetails['guestFeeList']);
        if (!empty($guestPrices)) {
            foreach ($guestPrices as $guestPrice) {
                $items[] = [
                    'name' => $guestPrice['name'],
                    'quantity' => 1,
                    'amount' => format_money($guestPrice['totalAmount'] ?? 0),
                    'type' => 'guest-fee'
                ];
            }
        }

        //summary
        $summaryItems[] = [
            'name' => 'Subtotal',
            'amount' => format_money($priceDetails['subTotal']),
            'type' => 'subtotal',
            'extraData' => $finePrintLine
        ];

        $summaryItems[] = [
            'name' => Constants::TAX_PERCENT_LABEL,
            'amount' => format_money($priceDetails['tax']),
            'type' => 'tax'
        ];


        if ($priceDetails['discount'] > 0) {

            $summaryItems[] = [
                'name' => 'TOTAL',
                'amount' => format_money($priceDetails['total']),
                'type' => 'total'
            ];

            $bookingCoupon = CouponBookings::where('booking_id', $booking->id)->first();

            $summaryItems[] = [
                'name' => $bookingCoupon != null ? 'Coupon Discount (' . $bookingCoupon->coupon_code . ')' : 'Coupon Discount',
                'amount' => '(' . format_money($priceDetails['discount']) . ')',
                'type' => 'discount'
            ];

        }



        $summaryItems[] = [
            'name' => 'Grand Total',
            'amount' => format_money($priceDetails['payableAmount']),
            'type' => 'grand-total'
        ];

        $data = ['items' => $items, 'summaryItems' => $summaryItems];
        // dd($data);
        return $data;
    }

    public static function secondsLeftInBooking($booking)
    {
        $nextTime = strtotime("+" . Constants::BOOKING_BLOCK_MINS . " mins", strtotime($booking->created_at));
        return $nextTime - time();
    }

    public static function deletePendingOldBookings()
    {
        $timeOld = date(Constants::PHP_DATE_FORMAT, strtotime("-" . Constants::BOOKING_BLOCK_MINS . " mins"));
        Booking::where('created_at', '<=', $timeOld)
            ->where('status', 'draft')
            ->whereNull('deleted_at')
            ->delete();
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function replaceMaskFields($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        return $content;
    }

    public static function deleteOldUnUsedCoupon()
    {
        Coupon::where('usage', 'credit_coupon')->delete();
    }

    public static function requestLatLng()
    {
        $lat = $lng = null;

        if (
            isset($_GET['map_lat']) && isset($_GET['map_lgn']) &&
            self::isNotEmpty(trim($_GET['map_lat'])) && self::isNotEmpty(trim($_GET['map_lgn']))
        ) {
            $lat = trim($_GET['map_lat']);
            $lng = trim($_GET['map_lgn']);
        } elseif (
            isset($_GET['userLat']) && isset($_GET['userLng']) &&
            self::isNotEmpty(trim($_GET['userLat'])) && self::isNotEmpty(trim($_GET['userLng']))
        ) {
            $lat = trim($_GET['userLat']);
            $lng = trim($_GET['userLng']);
        }

        return [
            'lat' => $lat,
            'lng' => $lng,
        ];
    }

    public static function getPageData($seo_meta)
    {
        $page_title = null;
        if ($seo_meta) {
            $page_title = $seo_meta['seo_title'] ?? ($seo_meta['service_title'] ?? ($page_title ?? ''));
        }
        $title = $page_title ?? 'MyOffice';
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url_parts = explode('?', $actual_link);
        $url_without_query = $url_parts[0];
        return [
            'title' => $title,
            'url' => $actual_link,
            'url_without_query' => $url_without_query
        ];
    }

    public static function printAmount($amount)
    {
        return '$' . number_format((float) $amount, 2, '.', '');
    }

    public static function printNumber($number)
    {
        return $number;
    }

    public static function findVacanciesOfSpace($space, $startDate = null, $endDate = null)
    {
        if ($startDate == null) {
            $startDate = $space->created_at;
        }

        if ($endDate == null) {
            $endDate = date(Constants::PHP_DATE_FORMAT);
        }

        if (strtotime($startDate) < strtotime($space->created_at)) {
            $startDate = $space->created_at;
        }

        if (strtotime($endDate) > time()) {
            $endDate = date(Constants::PHP_DATE_FORMAT);
        }

        $date1 = new \DateTime($startDate);
        $date2 = new \DateTime($endDate);

        $interval = $date1->diff($date2);
        $totalDays = $interval->days;

        $totalBookedDays = Booking::where('object_id', $space->id)
            ->where('object_model', 'space')
            ->select(DB::raw('DATE(start_date) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(start_date)'))
            ->get()->count();

        if ($totalBookedDays == null) {
            $totalBookedDays = 0;
        }

        if ($totalBookedDays > $totalDays) {
            $totalBookedDays = $totalDays;
        }

        $totalVacacant = $totalDays - $totalBookedDays;
        $per = 0;

        if ($totalVacacant > 0 && $totalDays > 0) {
            $per = ($totalVacacant * 100) / $totalDays;
            $per = round($per, 2);
        }

        return [
            'total' => $totalDays,
            'totalBookedDays' => $totalBookedDays,
            'vacant' => $totalVacacant,
            'percentage' => $per,
        ];
    }

    public static function vacanciesOfVendor($vendorId, $startDate, $endDate = null)
    {
        if ($endDate == null) {
            $endDate = date(Constants::PHP_DATE_FORMAT);
        }

        if (strtotime($endDate) > time()) {
            $endDate = date(Constants::PHP_DATE_FORMAT);
        }

        $date1 = new \DateTime($startDate);
        $date2 = new \DateTime($endDate);

        $interval = $date1->diff($date2);
        $totalDays = $interval->days;

        $totalBookedDays = Booking::where('vendor_id', $vendorId)
            ->where('object_model', 'space')
            ->select(DB::raw('DATE(start_date) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(start_date)'))
            ->get()->count();

        if ($totalBookedDays == null) {
            $totalBookedDays = 0;
        }

        if ($totalBookedDays > $totalDays) {
            $totalBookedDays = $totalDays;
        }

        $totalVacacant = $totalDays - $totalBookedDays;
        $per = 0;

        if ($totalVacacant > 0 && $totalDays > 0) {
            $per = ($totalVacacant * 100) / $totalDays;
            $per = round($per, 2);
        }

        return [
            'total' => $totalDays,
            'totalBookedDays' => $totalBookedDays,
            'vacant' => $totalVacacant,
            'percentage' => $per,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    public static function getFirstOldDayOfVendor($vendorId)
    {
        $model = Space::where('create_user', $vendorId)->orderBy('created_at', 'ASC')->first();
        if ($model != null) {
            return date(Constants::PHP_DATE_FORMAT, strtotime($model->created_at));
        }
        return date(Constants::PHP_DATE_FORMAT);
    }

    public static function slugify($string)
    {
        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        $string = strtolower($string);
        $string = trim($string, '-');
        $string = preg_replace('/-+/', '-', $string);

        return $string;
    }

    public static function generateSpaceImages()
    {
        echo "running generateSpaceImages";

        $task = TaskData::getOrCreateTask('regenerate_all_space_images');
        if ($task->status != "pending") {
            return false;
        }

        $data = $task->data;

        $limit = 1000;

        if ($data == null) {
            $data = 0;
        } else {
            $data = $data * 1;
        }

        $task->data = $data + $limit;
        $task->save();

        $spaces = Space::query()->orderBy('id', 'asc')->offset($data)->limit($limit)->get();

        echo "Total spaces " . count($spaces);

        if (count($spaces) == 0) {
            $task->markAsCompleted();
        }

        $replace = true;
        foreach ($spaces as $space) {
            // $terms = Term
            $terms = [];

            $spaceTerms = SpaceTerm::where('target_id', $space->id)->get();
            if ($spaceTerms) {
                foreach ($spaceTerms as $spaceTerm) {
                    if ($spaceTerm->term->attr_id === 3) {
                        $terms[] = "stock/" . CodeHelper::slugify($spaceTerm->term->name);
                    }
                }
            }

            if (count($terms) > 0) {

                $stock_images = MediaFile::inRandomOrder()->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('file_path', 'LIKE', $term . '%');
                    }
                })->where('reference_type', 'spaces')->groupBy('file_path')->get();

                if (count($stock_images) > 0) {

                    $gallery = $replace ? [] : explode(",", $space->gallery);
                    $firstImage = $stock_images[0];
                    $bannerImage = $stock_images[0];
                    if (count($stock_images) > 1) {
                        $bannerImage = $stock_images[1];
                    }
                    if ($space->image_id == '' || $replace) {
                        $newMediaFile = $firstImage->replicate();
                        $newMediaFile->reference_type = null;
                        $newMediaFile->save();
                        $space->image_id = $newMediaFile->id;
                    }
                    if ($space->banner_image_id == '' || $replace) {
                        $newMediaFile = $bannerImage->replicate();
                        $newMediaFile->reference_type = null;
                        $newMediaFile->save();
                        $space->banner_image_id = $newMediaFile->id;
                    }
                    $medias = [];
                    $si = 0;
                    foreach ($stock_images as $im) {
                        $si++;
                        if (count($gallery) >= 10) {
                            break;
                        }
                        if ($si > 2) {
                            $newMediaFile = $im->replicate();
                            $newMediaFile->reference_type = null;
                            $newMediaFile->save();
                            $gallery[] = $newMediaFile->id;
                            $medias[] = $newMediaFile->file_path;
                        }
                        // if (!in_array($newMediaFile->id, $gallery) && $space->image_id != $newMediaFile->id && $space->banner_image_id != $newMediaFile->id) {
                        //     $gallery[] = $newMediaFile->id;
                        //     break;
                        // }
                    }
                    $space->gallery = implode(",", $gallery);
                    $space->save();
                }
            }
        }
    }

    public static function printMobileNo($mobileNo)
    {
        // Remove all non-numeric characters
        $mobileNo = preg_replace('/[^0-9]/', '', $mobileNo);
        // Check the length of the number to determine how to handle it
        $length = strlen($mobileNo);
        // If the number is exactly 10 digits, assume it does not include a country code
        if ($length == 10) {
            // Format the number into (XXX) XXX XXXX
            $formattedNo = sprintf(
                "(%s) %s %s",
                substr($mobileNo, 0, 3),
                substr($mobileNo, 3, 3),
                substr($mobileNo, 6)
            );
            // Prepend the default country code +1
            $formattedNo;
        }
        // If the number is more than 10 digits, assume the first few digits are the country code
        elseif ($length > 10) {
            // Extract the country code (all digits before the last 10 digits)
            $countryCode = substr($mobileNo, 0, $length - 10);
            // Extract the 10-digit phone number
            $number = substr($mobileNo, -10);
            // Format the number into (XXX) XXX XXXX
            $formattedNo = sprintf(
                "(%s) %s %s",
                substr($number, 0, 3),
                substr($number, 3, 3),
                substr($number, 6)
            );
            // Prepend the extracted country code
            return '+' . $countryCode . ' ' . $formattedNo;
        } else {
            // Handle cases where the number isn't valid
            return "Invalid mobile number";
        }
    }

    public static function convertMarkdownInHtml($html)
    {
        // This will convert bold (**) and italic (_) text, including combined formats
        $html = preg_replace_callback(
            '/(\*\*\*|___)(.*?)\1/',  // Matches bold and italic syntax (*** or ___)
            function ($matches) {
                return '<strong><em>' . $matches[2] . '</em></strong>';  // Replace with <strong><em>
            },
            $html
        );

        // Convert bold syntax (**) or (__)
        $html = preg_replace_callback(
            '/(\*\*|__)(.*?)\1/',  // Matches bold syntax (**) or (__)
            function ($matches) {
                return '<strong>' . $matches[2] . '</strong>';  // Replace with <strong>
            },
            $html
        );

        // Convert italic syntax (_) or (*)
        $html = preg_replace_callback(
            '/(_|\\*)(.*?)\\1/',  // Matches italic syntax (_) or (*)
            function ($matches) {
                return '<em>' . $matches[2] . '</em>';  // Replace with <em>
            },
            $html
        );

        return $html;
    }

    public static function vendorDiscountBookingIds($vendorId, $conditions = [])
    {
        $bookingIds = DB::table(Booking::getTableName())
            ->join(CouponBookings::getTableName(), Booking::getTableName() . '.id', '=', CouponBookings::getTableName() . '.booking_id')
            ->where(Booking::getTableName() . '.vendor_id', $vendorId);

        foreach ($conditions as $item) {
            $bookingIds->whereRaw(Booking::getTableName() . '.' . $item);
        }

        $bookingIds = $bookingIds->pluck(Booking::getTableName() . '.id')->toArray();
        if ($bookingIds == null) {
            $bookingIds = [-1];
        }
        return $bookingIds;
    }

    public static function testEmail()
    {
        return Mail::to('kasyap459@gmail.com')->send(new TestEmail());
    }

    public static function getPaidAmount($payment_id)
    {
        $paid= 0;
        $details = UserTransactionDetails::where('payment_id', $payment_id)->get();
        if($details){
         foreach ($details as $key => $value) { 
           if($value->type!='single')
           {

            $paid= $value->amount+$paid;
           }
        }
        
        return $paid;
       }
       else {
        return 0;
       }

    }

}

