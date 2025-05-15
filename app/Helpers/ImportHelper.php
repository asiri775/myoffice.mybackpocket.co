<?php


namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\Core\Events\CreateReviewEvent;
use Modules\Core\Models\Settings;
use Modules\Review\Models\Review;
use Modules\Space\Models\Space;

class ImportHelper
{

    public static function importData2()
    {
        $address = public_path("MyOffice-Listings.xlsx");
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($address);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $spaceSettings = Settings::getSettings('space');

        $columns = [];

        $fixSpaces = true;

        foreach ($rows as $key => $value) {
            if ($key === 0) {
                $columns = $value;
            } else {
                $data = [];
                foreach ($value as $iter => $columnVal) {
                    $column = $columns[$iter];
                    $data[$column] = $columnVal;
                }

                if ($fixSpaces) {

                    $space = Space::where('title', $data['title'])->first();
                    if ($space != null) {
                        if ($space->zip == 20) {
                            $space->zip = $data['zip'];
                            $space->save();
                        }
                    }

                } else {

                    $space = new Space();
                    $space->title = $data['title'];
                    $space->alias = $data['title'];
                    $space->slug = strtolower($data['slug']);
                    $space->content = $data['content'];
                    $space->address_unit = $data['address_unit'];
                    $space->address = $data['address'];

                    $space->hourly = $data['hourly'];
                    $space->daily = $data['daily'];
                    $space->weekly = $data['weekly'];
                    $space->monthly = $data['monthly'];

                    $space->hourly_price_set_default = 1;
                    $space->hours_after_full_day = 8;

                    $space->city = $data['city'];
                    $space->state = $data['state'];
                    $space->country = $data['country'];
                    $space->zip = $data['zip'];

                    $space->faqs = $spaceSettings['space_default_faqs'];
                    $space->map_zoom = 12;
                    $space->allow_children = 0;
                    $space->allow_infant = 0;
                    $space->max_guests = 10;

                    $space->enable_extra_price = 0;

                    $space->status = "publish";

                    $space->create_user = 2;
                    $space->update_user = 2;

                    $space->available_from = "09:00";
                    $space->available_to = "17:00";

                    $space->first_working_day = "Monday";
                    $space->last_working_day = "Friday";

                    $space->long_term_rental = 0;
                    $space->free_cancellation = 0;
                    $space->rapidbook = 0;

                    $space->map_lat = 0;
                    $space->map_lng = 0;

                    $space->checkin_reminder_time = "30 Minutes";
                    $space->prearrival_checkin_text = "Hi {firstname},

Your MyOffice Booking at {spacename} is coming up in {checkintime}. 

Please be sure to Check IN upon arrival:
{bookinglink}";

                    $space->arrival_checkin_reminder = "On Time";
                    $space->arrival_checkin_text = "Your MyOffice booking #{bookingno} at {spacename} is starting now. 

Please be sure to Check IN to let your Host know that you have arrived:
{checkinurl}";

                    $space->host_checkin_reminder = "5 Minutes";
                    $space->host_reminder_text = "Dear {FirstName},
                                            
Your Guest has not yet Checked IN for Booking #{bookingno}. 
                                            
Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
                
Booking Details 
Listing Name : {listingname}
Arrival Date and Time : {arrivaltime}
Departure Date and Time : {departuretime}
Cancellation Fee: {cancellationfee}

Contact Guest  | Manual Check IN | Edit Booking at following link
<a href='{bookinglink}'>Click Here</a>";

                    $space->checkout_reminder_time = "15 Minutes";
                    $space->departure_reminder_text = "Your MyOffice Booking #{bookingno} at {spacename} is ending in {checkouttime}.

Kindly prepare the office for departure and ensure the space is ready for next tenant.
    
Remember to Check OUT or you may EXTEND your stay (If available).
{checkouturl}";

                    $space->latecheckout_reminder_time = "15 Minutes";
                    $space->latecheckout_reminder_text = "Your MyOffice Booking #{bookingno} at {spacename} has expired and you have not yet Checked OUT of the space.

Please note that it is important to notify your Host that you have departed, to ensure that your Space is checked for any damages or cleaning required.
    
Check OUT 
{checkouturl}";

                    $space->house_rules = $spaceSettings['space_default_house_rules'];
                    $space->tos = $spaceSettings['space_default_terms'];

                    $space->save();

                    $terms = [
                        26,
                        28,
                        29,
                        31,
                        93,
                        94,
                        95,
                        96,
                        97,
                        98,
                        99,
                        106
                    ];

                    if (
                        in_array(
                            $data['category'],
                            ['Cafe', 'Café', 'Cafee', 'caffe', 'ccafe']
                        )
                    ) {
                        $terms[] = 17;
                    } elseif (
                        in_array(
                            $data['category'],
                            ['Corporate', 'Office Space', 'Officee Space', 'Offiice Space']
                        )
                    ) {
                        $terms[] = 18;
                    }

                    $termData = [];
                    foreach ($terms as $term) {
                        $termData[] = [
                            'term_id' => $term,
                            'target_id' => $space->id,
                            'create_user' => 2,
                            'update_user' => 2,
                        ];
                    }

                    DB::table('bravo_space_term')->insert($termData);

                }

            }
        }
    }

    public static function importData($start, $end)
    {

        $address = public_path("Myoffice-old-listings.xlsx");
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($address);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $spaceSettings = Settings::getSettings('space');

        $columns = [];

        foreach ($rows as $key => $value) {
            if ($key === 0) {
                $columns = $value;
            } else {

                $data = [];

                foreach ($value as $iter => $columnVal) {
                    $column = $columns[$iter];
                    $data[$column] = $columnVal;
                }

                if ($key >= $start && $key <= $end) {

                    $addressData = trim($data['address']);

                    $address = $city = $state = $country = "";

                    $addressData = explode(',', $addressData);
                    $addressData = array_filter($addressData, function ($value) {
                        return !empty (trim($value));
                    });

                    $addressData = array_values($addressData);

                    if (count($addressData) >= 3) {

                        $lastItem = trim(end($addressData));

                        $secondLastIndex = count($addressData) - 2;
                        $secondLast = $addressData[$secondLastIndex];

                        if (strtolower($lastItem) == "canada" || strtolower($lastItem) == "ca") {
                            $country = "ca";
                        } elseif (strtolower($lastItem) == "united states") {
                            $country = "us";
                        } elseif (strlen($lastItem) === 3) {
                            $country = $lastItem;
                        } elseif (strlen($lastItem) === 2) {
                            $state = $lastItem;
                        } else {
                            $state = $lastItem;
                        }

                        if ($state == null) {
                            $state = $secondLast;
                        }

                        if (count($addressData) == 4) {
                            $city = trim($addressData[1]);
                            $address = trim($addressData[0]);
                        }

                        if (trim($addressData[0]) === trim($addressData[1])) {
                            $address = trim($addressData[0]);
                        } else {
                            if (count($addressData) == 3) {
                                $city = trim($addressData[1]);
                                $address = trim($addressData[0]);
                            } elseif (count($addressData) == 5) {
                                $address = trim($addressData[0]) . ", " . trim($addressData[1]) . ", " . trim($addressData[2]);
                            }
                        }

                        if (trim($city) === trim($state)) {
                            $city = "";
                        }

                        // echo $address . "  -  " . $city . "  -  " . $state . "  -  " . $country . "    <<<<<<";

                        // print_r($addressData);

                        // echo '-------------';

                        echo $key . PHP_EOL;

                    } else {
                        // print_r($addressData);die;
                        echo trim($data['address']) . PHP_EOL;
                        // break;
                    }

                    // $space = Space::where('title', $data['title'])
                    //     ->where('address', $address)
                    //     ->first();

                    $space = null;

                    if ($space == null) {

                        $space = new Space();
                        $space->title = $data['title'];
                        $space->alias = $data['title'];
                        $space->content = "Great Office Property";
                        $space->address = $address;

                        $space->hourly = 15;
                        $space->daily = 75;
                        $space->weekly = 500;
                        $space->monthly = 2000;

                        $space->hourly_price_set_default = 1;
                        $space->hours_after_full_day = 8;

                        $space->city = $city;
                        $space->state = $state;
                        $space->country = $country;

                        $space->faqs = $spaceSettings['space_default_faqs'];
                        $space->map_zoom = 12;
                        $space->allow_children = 0;
                        $space->allow_infant = 0;
                        $space->max_guests = 10;

                        $space->enable_extra_price = 0;

                        $space->status = "publish";

                        $space->create_user = 2;
                        $space->update_user = 2;

                        $space->available_from = "09:00";
                        $space->available_to = "17:00";

                        $space->first_working_day = "Monday";
                        $space->last_working_day = "Friday";

                        $space->long_term_rental = 0;
                        $space->free_cancellation = 0;
                        $space->rapidbook = 0;

                        $space->map_lat = $data['map_lat'];
                        $space->map_lng = $data['map_lng'];

                        $space->checkin_reminder_time = "30 Minutes";
                        $space->prearrival_checkin_text = "Hi {firstname},

Your MyOffice Booking at {spacename} is coming up in {checkintime}. 

Please be sure to Check IN upon arrival:
{bookinglink}";

                        $space->arrival_checkin_reminder = "On Time";
                        $space->arrival_checkin_text = "Your MyOffice booking #{bookingno} at {spacename} is starting now. 

Please be sure to Check IN to let your Host know that you have arrived:
{checkinurl}";

                        $space->host_checkin_reminder = "5 Minutes";
                        $space->host_reminder_text = "Dear {FirstName},
                                            
Your Guest has not yet Checked IN for Booking #{bookingno}. 
                                            
Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
                
Booking Details 
Listing Name : {listingname}
Arrival Date and Time : {arrivaltime}
Departure Date and Time : {departuretime}
Cancellation Fee: {cancellationfee}

Contact Guest  | Manual Check IN | Edit Booking at following link
<a href='{bookinglink}'>Click Here</a>";

                        $space->checkout_reminder_time = "15 Minutes";
                        $space->departure_reminder_text = "Your MyOffice Booking #{bookingno} at {spacename} is ending in {checkouttime}.

Kindly prepare the office for departure and ensure the space is ready for next tenant.
    
Remember to Check OUT or you may EXTEND your stay (If available).
{checkouturl}";

                        $space->latecheckout_reminder_time = "15 Minutes";
                        $space->latecheckout_reminder_text = "Your MyOffice Booking #{bookingno} at {spacename} has expired and you have not yet Checked OUT of the space.

Please note that it is important to notify your Host that you have departed, to ensure that your Space is checked for any damages or cleaning required.
    
Check OUT 
{checkouturl}";

                        $space->house_rules = $spaceSettings['space_default_house_rules'];
                        $space->tos = $spaceSettings['space_default_terms'];

                        $space->save();

                        $terms = [
                            26,
                            28,
                            29,
                            31,
                            93,
                            94,
                            95,
                            96,
                            97,
                            98,
                            99,
                            106
                        ];

                        $terms[] = 17;
                        $terms[] = 18;

                        $termData = [];
                        foreach ($terms as $term) {
                            $termData[] = [
                                'term_id' => $term,
                                'target_id' => $space->id,
                                'create_user' => 2,
                                'update_user' => 2,
                            ];
                        }

                        DB::table('bravo_space_term')->insert($termData);

                    } else {
                        echo "exist ->   " . $data['title'] . "   ->   " . $address . PHP_EOL;
                    }

                }

            }
        }
    }

    public static function cleanData($data)
    {
        $data = trim($data);
        $data = str_replace(',', '', $data);
        $data = trim($data);
        return $data;
    }

    public static function fixZipCode()
    {
        $address = public_path("MyOffice-Listings.xlsx");
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($address);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $columns = [];

        $dataSpaces = [];

        foreach ($rows as $key => $value) {
            if ($key === 0) {
                $columns = $value;
            } else {
                $data = [];
                foreach ($value as $iter => $columnVal) {
                    $column = $columns[$iter];
                    $data[$column] = $columnVal;
                }

                $keyName = self::cleanData($data['title']) . '---' . self::cleanData($data['address']);

                $dataSpaces[$keyName] = [
                    'zip' => $data['zip'],
                ];

            }
        }

        // print_r($dataSpaces);die;

        $spaces = Space::where('zip', 20)->get();
        foreach ($spaces as $space) {
            $keyName = self::cleanData($space['title']) . '---' . self::cleanData($space['address']);
            $keyName2 = $keyName . ",";
            if (array_key_exists($keyName, $dataSpaces)) {
                $space->zip = $dataSpaces[$keyName]['zip'];
                $space->save();
            } elseif (array_key_exists($keyName2, $dataSpaces)) {
                $space->zip = $dataSpaces[$keyName2]['zip'];
                $space->save();
            }
        }

    }

    public static function fakeBookings()
    {
        $data = [
            [
                'host' => 'host3@myoffice.ca',
                'guest' => 'guest4@myoffice.ca',
                'minBookings' => 5,
                'minReviews' => 0,
                'minRevenue' => 500,
            ],
            [
                'host' => 'host4@myoffice.ca',
                'guest' => 'guest5@myoffice.ca',
                'minBookings' => 51,
                'minReviews' => 21,
                'minRevenue' => 1000,
            ],
            [
                'host' => 'host5@myoffice.ca',
                'guest' => 'guest6@myoffice.ca',
                'minBookings' => 101,
                'minReviews' => 51,
                'minRevenue' => 5000,
            ],
            [
                'host' => 'host6@myoffice.ca',
                'guest' => 'guest7@myoffice.ca',
                'minBookings' => 301,
                'minReviews' => 101,
                'minRevenue' => 10000,
            ],
        ];
        foreach ($data as $item) {
            $host = $item['host'];
            $guest = $item['guest'];
            $minBookings = $item['minBookings'];
            $minReviews = $item['minReviews'];
            $minRevenue = $item['minRevenue'];

            $allServices = get_bookable_services();

            $hostDetails = User::where('email', $host)->first();
            if ($hostDetails != null) {
                $guestDetails = User::where('email', $guest)->first();
                if ($guestDetails != null) {
                    $space = Space::where('create_user', $hostDetails->id)->first();
                    if ($space == null) {
                        $space = Space::where('id', '>', '500')
                            ->where('id', '<', '5000')
                            ->orderByRaw('RAND()')
                            ->first();
                    }
                    $space->create_user = $hostDetails->id;
                    $space->save();
                    $daily = $space->daily;
                    $howManyDaily = 1;
                    $costPerBooking = ceil($minRevenue / $minBookings);
                    if ($daily < $costPerBooking) {
                        $howManyDaily = ceil($costPerBooking / $daily);
                    }
                    echo 'Do bookings ' . $minBookings . " of each " . $howManyDaily . ' day bookings at daily ' . $daily . ' for ' . $minBookings . ' ' . $minRevenue . PHP_EOL;
                    for ($i = 0; $i <= $minBookings; $i++) {
                        $startDate = date(Constants::PHP_DATE_FORMAT, strtotime("+" . $i . " days"));
                        $endDate = date(Constants::PHP_DATE_FORMAT, strtotime("+" . ($i + $howManyDaily) . " days"));
                        $paid = ($daily * $howManyDaily);
                        $booking = new Booking();

                        $booking->vendor_id = $hostDetails->id;
                        $booking->customer_id = $guestDetails->id;
                        $booking->object_id = $space->id;
                        $booking->object_model = 'space';
                        $booking->start_date = $startDate;
                        $booking->end_date = $endDate;
                        $booking->total = $paid;
                        $booking->total_guests = 1;
                        $booking->status = 'booked';
                        $booking->email = $guestDetails->email;
                        $booking->first_name = $guestDetails->first_name;
                        $booking->last_name = $guestDetails->last_name;
                        $booking->phone = $guestDetails->phone;
                        $booking->address = $guestDetails->address;
                        $booking->address2 = $guestDetails->address2;
                        $booking->city = $guestDetails->city;
                        $booking->state = $guestDetails->state;
                        $booking->zip_code = $guestDetails->zip_code;
                        $booking->country = $guestDetails->country;
                        $booking->create_user = $guestDetails->id;
                        $booking->update_user = $guestDetails->id;
                        $booking->host_amount = $paid;
                        $booking->payable_amount = $paid;
                        $booking->paid = $paid;
                        $booking->pay_now = $paid;
                        $booking->payment_status = 'paid';

                        $booking->save();
  
                        $module_class = $allServices['space'];
                        $module = $module_class::find($space->id);

                        // Review from host
                        $reviewFromHost = new Review();

                        $reviewFromHost->object_id = $space->id;
                        $reviewFromHost->object_model = 'space';
                        $reviewFromHost->title = 'Awesome Guest';
                        $reviewFromHost->content = 'Very gentle and nice guest';
                        $reviewFromHost->rate_number = 4;
                        $reviewFromHost->status = 'approved';
                        $reviewFromHost->create_user = $hostDetails->id;
                        $reviewFromHost->update_user = $hostDetails->id;
                        $reviewFromHost->vendor_id = $hostDetails->id;
                        $reviewFromHost->reference_id = $booking->id;
                        $reviewFromHost->review_by = 'host';
                        $reviewFromHost->review_to = $guestDetails->id;

                        $reviewFromHost->save();

                        event(new CreateReviewEvent($module, $reviewFromHost));
                        $module->update_service_rate($reviewFromHost->review_to);

                        // Review from guest
                        $reviewFromGuest = new Review();

                        $reviewFromGuest->object_id = $space->id;
                        $reviewFromGuest->object_model = 'space';
                        $reviewFromGuest->title = 'Awesome Host';
                        $reviewFromGuest->content = 'Very gentle and nice host';
                        $reviewFromGuest->rate_number = 4;
                        $reviewFromGuest->status = 'approved';
                        $reviewFromGuest->create_user = $guestDetails->id;
                        $reviewFromGuest->update_user = $guestDetails->id;
                        $reviewFromGuest->vendor_id = $guestDetails->id;
                        $reviewFromGuest->reference_id = $booking->id;
                        $reviewFromGuest->review_by = 'guest';
                        $reviewFromGuest->review_to = $hostDetails->id;

                        $reviewFromGuest->save();

                        event(new CreateReviewEvent($module, $reviewFromGuest));
                        $module->update_service_rate($reviewFromGuest->review_to);

                    }
                }
            }

        }
    }

}