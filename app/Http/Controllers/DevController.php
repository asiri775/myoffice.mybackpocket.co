<?php

namespace App\Http\Controllers;

use App\Helpers\CodeHelper;
use App\Models\AddToFavourite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingTimeSlots;
use Modules\Core\Models\Settings;
use Modules\Coupon\Models\Coupon;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceBlockTime;
use Modules\Space\Models\SpaceDate;
use Modules\Space\Models\SpaceTerm;

class DevController extends Controller
{

    public function fixLocations()
    {
        $spaces = Space::where('map_lat', 0)->whereNotNull('address')->where('address', '!=', '""')->orderBy('id', 'asc')->limit(250)->get();
        if ($spaces != null) {
            foreach ($spaces as $space) {
                //echo $space->id;die;
                // $address = $space->address;
                $address = "$space->address, $space->city, $space->state $space->zip, $space->country";
                //echo $address;
                $pageUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc';
                $addressResponse = CodeHelper::pageResponse($pageUrl);
                if (is_array($addressResponse)) {
                    if (array_key_exists('results', $addressResponse)) {
                        $results = $addressResponse['results'];
                        //print_r($results);die;
                        if (is_array($results) && array_key_exists(0, $results)) {
                            $addressComponent = $results[0];
                            if (is_array($addressComponent)) {
                                $geo = $addressComponent['geometry']['location'];
                                if (is_array($geo)) {
                                    if (array_key_exists('lat', $geo) && array_key_exists('lng', $geo)) {
                                        $space->map_lat = (string) $geo['lat'];
                                        $space->map_lng = (string) $geo['lng'];
                                        $space->update();

                                        echo $address . " --> LAT " . $space->map_lat . "  --> LANG " . $space->map_lng . "</br>";

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
  
    public function fixLatLng()
    {
        die("close");
        $spaces = Space::whereNull('zip')
            ->whereNotNull('map_lat')
            ->whereNotNull('map_lng')
            ->whereNotNull('address')->where('address', '!=', '""')->orderBy('id', 'asc')->limit(100)->get();
        if ($spaces != null) {
            foreach ($spaces as $space) {
                $mapLat = $space->map_lat;
                $mapLng = $space->map_lng;
                $pageUrl = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $mapLat . ',' . $mapLng . '&key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc';
                $addressResponse = CodeHelper::pageResponse($pageUrl);
                if (is_array($addressResponse)) {
                    if (array_key_exists('results', $addressResponse)) {
                        $results = $addressResponse['results'];
                        // dd($results);
                        if (is_array($results) && array_key_exists(0, $results)) {
                            $addressComponent = $results[0];
                            if (is_array($addressComponent)) {
                                $address_unit = $address = $city = $state = $country = $zip = '';

                                foreach ($addressComponent['address_components'] as $addr) {

                                    if (in_array('street_number', $addr['types'])) {
                                        $address_unit = $addr['short_name'];

                                    } elseif (in_array('route', $addr['types'])) {
                                        $address = $addr['short_name'];

                                    } elseif (in_array('locality', $addr['types'])) {
                                        $city = $addr['long_name'];

                                    } elseif (in_array('administrative_area_level_1', $addr['types'])) {
                                        $state = $addr['short_name'];

                                    } elseif (in_array('country', $addr['types'])) {
                                        $country = $addr['short_name'];

                                    } elseif (in_array('postal_code', $addr['types'])) {
                                        $zip = $addr['short_name'];
                                    }

                                }

                                $space->address_unit = $address_unit;
                                $space->address = $address;
                                $space->city = $city;
                                $space->state = $state;
                                $space->country = $country;
                                $space->zip = $zip;
                                $space->save();

                                // dd($space);
                            }
                        }
                    }
                }
            }
        }
    }

    public function fixLatLngOld()
    {
        $spaces = Space::where('map_lat', 0)->whereNotNull('address')->where('address', '!=', '""')->orderBy('id', 'asc')->limit(250)->get();
        if ($spaces != null) {
            foreach ($spaces as $space) {
                $fullAddress = "";
                // if ($space->address_unit != null) {
                //     $fullAddress .= $space->address_unit . " ";
                // }

                $fullAddress .= $space->address . " " . $space->city;

                $pageUrl = 'https://geocode.maps.co/search?q=' . urlencode($fullAddress) . '&api_key=65c3b18a3db57226630166mop473bfa';

                $addressResponse = CodeHelper::pageResponse($pageUrl);
                if (is_array($addressResponse)) {
                    if (array_key_exists(0, $addressResponse)) {
                        $addressComponent = $addressResponse[0];
                        if (
                            array_key_exists('lat', $addressComponent) &&
                            array_key_exists('lon', $addressComponent)
                        ) {
                            $space->map_lat = $addressComponent['lat'];
                            $space->map_lng = $addressComponent['lon'];
                            $space->save();
                        }
                    }
                }

                // $fullAddress .= $space->address." ".$space->city.", ".$space->state." - ".$space->zip;

                // $pageUrl = 'http://api.positionstack.com/v1/forward?access_key=17fd26867733e1bea95b5b4bc185b762&query='.urlencode($fullAddress);

                // $addressResponse = CodeHelper::pageResponse($pageUrl);
                // if (is_array($addressResponse)) {
                //     if (array_key_exists('data', $addressResponse)) {
                //         $results = $addressResponse['data'];
                //         //print_r($results);die;
                //         if (is_array($results) && array_key_exists(0, $results)) {
                //             $addressComponent = $results[0];
                //             if (
                //                 array_key_exists('latitude', $addressComponent) &&
                //                 array_key_exists('longitude', $addressComponent)
                //             ) {
                //                 $space->map_lat = $addressComponent['latitude'];
                //                 $space->map_lng = $addressComponent['longitude'];
                //                 $space->save();
                //             }
                //         }
                //     }
                // }
            }
        }
    }

    public function deleteOldData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'add_to_favourites',
            'bravo_bookings',
            'bravo_booking_coupons',
            'bravo_booking_meta',
            'bravo_booking_payments',
            'bravo_booking_time_slots',
            'bravo_booking_transactions',
            'bravo_coupons',
            'bravo_events',
            'bravo_event_term',
            'bravo_payouts',
            'bravo_review',
            'bravo_review_meta',
            'bravo_spaces',
            'bravo_space_block_times',
            'bravo_space_dates',
            'bravo_space_term',
            'bravo_space_translations',
            'credit_coupons',
            'favorites',
            'referral_links',
            'user_transactions'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
