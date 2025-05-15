<?php

namespace App\Http\Controllers\Pwa;

use App\Helpers\CodeHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Modules\FrontendController;
use Auth;

use Modules\Location\Models\LocationCategory;
use Modules\Review\Models\Review;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceTerm;
use Modules\Core\Models\Terms;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Booking\Models\Booking;
use Modules\Space\Models\SpaceBlockTime;
use App\Models\AddToFavourite;
use Carbon\Carbon;
use Session;
use Mail;
use App\Mail\SubmitSupport;
use App\Models\User as ModelsUser;

class HomeController extends FrontendController
{


    protected $spaceClass;
    protected $locationClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        $this->spaceClass = Space::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }


    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect('m/home');
    }


    public function signin()
    {
        // echo "Hello PWA";
        return view('pwa/auth/signin');
    }


    public function signup()
    {
        // echo "Hello PWA";
        return view('pwa/auth/signup');
    }


    public function home(Request $request)
    {
        $user = Auth::user();


        $type = $request->query("type");

        $current_lat = $request->query("currentLatValue");
        $current_long = $request->query("currentLongName");

        Session::put('current_lat', $current_lat);
        Session::put('current_long', $current_long);

        $nearby_spaces = [];

        if ($current_lat != null) {
            $nearby_spaces = $this->nearBySpaces($current_lat, $current_long, $type);
        }

        if (!$type || $type === "hourly") {

            $categoryDetail = SpaceTerm::groupBy('term_id')
                ->select('term_id', \DB::raw('count(*) as count'))
                ->with('term')
                ->get();
        }

        if ($type == "long_term") {

            $long_term_ids = Space::where('long_term_rental', 1)->pluck('id');

            $categoryDetail = SpaceTerm::whereIn('target_id', $long_term_ids)
                ->groupBy('term_id')
                ->select('term_id', \DB::raw('count(*) as count'))
                ->with('term')
                ->get();
        }


        if ($type == 'parking') {

            //Set paking id default 91 in space_term table

            // $parking_target_id = SpaceTerm::where('term_id', 91)->pluck('target_id');

            $categoryDetail = SpaceTerm::where('term_id', 91)
                ->groupBy('term_id')
                ->select('term_id', \DB::raw('count(*) as count'))
                ->with('term')
                ->get();
        }


        if ($type == 'cafe') {

            //Set paking id default 91 in space_term table

            $parking_target_id = SpaceTerm::where('term_id', 17)->pluck('target_id');

            $categoryDetail = SpaceTerm::where('term_id', 17)
                ->groupBy('term_id')
                ->select('term_id', \DB::raw('count(*) as count'))
                ->with('term')
                ->get();
        }



        return view('pwa/home')->with('user', $user)
            ->with('categoryDetail', $categoryDetail)
            ->with('nearby_spaces', $nearby_spaces)
            ->with('type', $type);
    }


    public function nearBySpaces($current_lat = null, $current_long = null, $type = null)
    {
        $spaces = Space::where('id', '>', 0);

        if ($type === "hourly") {
        } else if ($type === "long_term") {
            $spaces->where('long_term_rental', 1);
        } else if ($type === "parking") {
            $spaceIds = SpaceTerm::where('term_id', 91)->pluck('target_id');
            $spaces->whereIn('id', $spaceIds);
        } else if ($type === "cafe") {
            $spaceIds = SpaceTerm::where('term_id', 17)->pluck('target_id');
            $spaces->whereIn('id', $spaceIds);
        }

        if (!$current_lat && !$current_long) {
            return $spaces->inRandomOrder()->limit(10)->get();
        } else {
            $distance = 100;
            return $spaces->orderBy('id', 'asc')->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $current_long . "," . $current_lat . "))) <= (" . $distance . " / 0.001)")->limit(10)->get();
        }
    }

    public function viewAllNearbySpaces(Request $request)
    {
        $current_lat = $request->query("currentLatValue");
        $current_long = $request->query("currentLongName");
        $list = $this->nearBySpaces($current_lat, $current_long);
        return view('pwa/nearby-spaces')->with('list', $list);
    }


    public function spaceDeatils(Request $request, $space_id)
    {
        $data = Space::where('id', $space_id)->first();
        $similar_space = Space::where('status', 'publish')
            ->where('id', '!=', $data->id)
            ->whereRaw("(ST_Distance_Sphere(point(`map_lng`, `map_lat`), point(" . $data->map_lng . "," . $data->map_lat . "))) <= (50 / 0.001)")
            ->limit(5)->get();
        return view('pwa/space_detail')->with('data', $data)->with('similar_space', $similar_space);
    }



    public function profile()
    {

        $user = Auth::user();
        if (!$user) {
            return redirect('m/signin');
        }
        $data = [
            'dataUser' => $user,
            'page_title' => __("Profile"),
            'breadcrumbs' => [
                [
                    'name' => __('Setting'),
                    'class' => 'active'
                ]
            ],
            'is_vendor_access' => $this->hasPermission('dashboard_vendor_access')
        ];


        $fav_list = AddToFavourite::where('user_id', $user->id)->orderBy('id', 'desc')->with('space')->get();

        $booking_list = Booking::where('customer_id', $user->id)
            ->where('object_model', 'space')
            ->orderBy('id', 'desc')
            ->where('is_archive', '!=', 1)
            ->with('objectDetails')
            ->get();

        $totalReviews = Review::where('create_user', $user->id)->where('status', 'approved')->get();

        return view('pwa/profile/profile')->with('data', $data)->with('fav_list', $fav_list)->with('booking_list', $booking_list)
            ->with('totalReviews', $totalReviews);
    }



    public function myFavourites(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return redirect('m/signin');
        } else {
            $list = AddToFavourite::where('user_id', $user->id)->orderBy('id', 'desc')->with('space')->get();
            return view('pwa/my_favourites')->with('user', $user)->with('list', $list);
        }
    }

    public function cafesList(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return redirect('m/signin');
        } else {
            $cafe_target_id = SpaceTerm::where('term_id', 17)->pluck('target_id');
            $list = Space::whereIn('id', $cafe_target_id)
                ->get();

            return view('pwa/cafes')->with('user', $user)->with('list', $list);
        }
    }


    public function search(Request $request)
    {

        $page_no = $request->query('page');

        $selected_category_parm = $request->query('categories');
        $selected_amenities_parm = $request->query('amenities');
        $selected_capacity_parm = $request->query('capacity');
        $selected_distance_parm = $request->query('distance');

        $listingname = $request->query('name');

        $addressSearch = $request->query('address_search');

        $current_lat = $request->query('current_lat');
        $current_long = $request->query('current_long');

        $searched_lat = $request->query('searched_lat');
        $searched_long = $request->query('searched_long');
        $searched_address = $request->query('searched_address');

        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $capacity_with_dates = $request->query('capacity_with_dates');

        $attributes = Terms::where('attr_id', 4)->get();

        $data = [
            'attributes' => $attributes
        ];

        $query = Space::orderBy('id', 'asc');

        if ($start_date != null && $end_date != null) {

            $startDate = Carbon::createFromFormat('Y-m-d', $start_date)->format('m/d/Y');
            $endDate = Carbon::createFromFormat('Y-m-d', $end_date)->format('m/d/Y');
            $startHour = "12:00";
            $endHour = "12:00";

            if ($startHour != null && $endHour != null) {
                $startDate = date('Y-m-d', strtotime($startDate)) . " " . $startHour . ":01";
                $toDate = date('Y-m-d', strtotime($endDate)) . " " . $endHour . ":00";
                $toDate = date('Y-m-d H:i:s', (strtotime($toDate) - 1));

                // echo $startDate." -> ".$toDate;die;

                //find booked listings in this date
                $bookedListings = Booking::whereRaw("`status` != 'draft' and `object_model` = 'space' and ( (`start_date` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR (`end_date` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR ('$startDate' BETWEEN `start_date` and `end_date`) OR ('$toDate' BETWEEN `start_date` and `end_date`) )")->pluck('object_id')->toArray();
                $bookedListings = array_unique($bookedListings);
                if (is_array($bookedListings) && count($bookedListings) > 0) {
                    $query->whereRaw('`bravo_spaces`.`id` NOT IN (' . implode(',', $bookedListings) . ')');
                }

                //find blocked listings in this date
                $blockedListings = SpaceBlockTime::whereRaw("( (`from` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR (`to` BETWEEN '" . $startDate . "' and '" . $toDate . "') OR ('$startDate' BETWEEN `from` and `to`) OR ('$toDate' BETWEEN `from` and `to`) )")->pluck('bravo_space_id')->toArray();
                $blockedListings = array_unique($blockedListings);
                if (is_array($blockedListings) && count($blockedListings) > 0) {
                    $query->whereRaw('`bravo_spaces`.`id` NOT IN (' . implode(',', $blockedListings) . ')');
                }
            }
        }

        if ($capacity_with_dates) {
            $query = $query->where('max_guests', $capacity_with_dates);
        }

        if ($addressSearch != null) {
            $query = $query->where('address', 'like', "%" . $addressSearch . "%");
        }

        if ($listingname != null) {
            $query = $query->where('title', 'like', "%" . $listingname . "%");
        }

        if ($selected_category_parm) {
            $explode_selected_category_parm = explode(',', $selected_category_parm);
            $space_ids = SpaceTerm::whereIn('term_id', $explode_selected_category_parm)->pluck('target_id');
            $query = $query->whereIn('id', $space_ids);
        }
        if ($selected_amenities_parm) {
            $explode_selected_amenities_parm = explode(',', $selected_amenities_parm);
            $space_ids = SpaceTerm::whereIn('term_id', $explode_selected_amenities_parm)->pluck('target_id');
            $query = $query->whereIn('id', $space_ids);
        }
        if ($selected_capacity_parm) {
            if ($selected_capacity_parm == "1-9") {
                $query = $query->where('max_guests', '>', '0')->where('max_guests', '<', '10');
            } elseif ($selected_capacity_parm == "10+") {
                $query = $query->where('max_guests', '>=', '10');
            } elseif ($selected_capacity_parm != "11") {
                // $explode_capacity = explode('-', $selected_capacity_parm);
                // $query = $query->where('max_guests', '>=',  $explode_capacity[0])->where('max_guests', '<=',  $explode_capacity[1]);
                $query = $query->where('max_guests', (int) $selected_capacity_parm);
            } else {
                $query = $query->where('max_guests', '>=', '11');
            }
        }
        if ($selected_distance_parm) {
            if (strtolower($selected_distance_parm) != 'all') {
                $explode_distance = explode('-', $selected_distance_parm);
                $query->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $current_long . "," . $current_lat . "))) <= (" . $explode_distance[1] . " / 0.001)");
            }
        }
        if ($searched_address && $searched_lat && $searched_long) {
            $searched_location_radius = 50;
            $query = $query->whereRaw("(ST_Distance_Sphere(point(`bravo_spaces`.`map_lng`, `bravo_spaces`.`map_lat`), point(" . $searched_long . "," . $searched_lat . "))) <= (" . $searched_location_radius . " / 0.001)");
        }


        // if($selected_category_parm || $selected_amenities_parm  || $selected_capacity_parm || $selected_distance_parm || $searched_address || $searched_lat || $searched_long || $start_date  || $end_date || $capacity_with_dates){
        //        $list = $query->paginate('10');
        // }
        // else{
        //     $list = [];
        // }
        $list = $query->paginate('20');

        // return $list;

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id" => $row->id,
                    "title" => $row->title,
                    "lat" => (float) $row->map_lat,
                    "lng" => (float) $row->map_lng,
                    "gallery" => $row->getGallery(true),
                    "infobox" => view('Space::frontend.layouts.search.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                    'marker' => get_file_url(setting_item("space_icon_marker_map"), 'full') ?? url('images/myoffice-marker-1.png'),
                ];
                Space::where('id', $row->id)->increment('views');
            }
        }

        return view('pwa/search')
            ->with('selected_category_parm', $selected_category_parm)
            ->with('selected_amenities_parm', $selected_amenities_parm)
            ->with('selected_capacity_parm', $selected_capacity_parm)
            ->with('selected_distance_parm', $selected_distance_parm)
            ->with('searched_address', $searched_address)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('capacity_with_dates', $capacity_with_dates)
            ->with('list', $list)
            ->with('markers', $markers)
            ->with('listingname', $listingname)
            ->with('data', $data);
    }


    public function updateProfileInfo(Request $request)
    {

        $user = Auth::user();

        // return $request->all();
        $data = [
            "profile_info" => $request->profile_info,
            "instagram_link" => $request->instagram_link,
            "site_link" => $request->site_link,
        ];

        User::where('id', $user->id)->update($data);

        return back()->with('message', 'Update successfully.');
    }


    public function updateNetworkingInfo(Request $request)
    {

        $user = Auth::user();

        // return $request->all();
        $data = [
            "myoffice_user_email" => $request->myoffice_user_email,
            "linkedin_link" => $request->linkedin_link,
            "facebook_page" => $request->facebook_page,
            "meetup_account" => $request->meetup_account,
        ];

        User::where('id', $user->id)->update($data);

        return back()->with('message', 'Update successfully.');
    }


    public function updateSocialInfo(Request $request)
    {

        $user = Auth::user();

        // return $request->all();
        $data = [
            "instagram_link" => $request->instagram_link,
            "facebook_personal" => $request->facebook_personal,
            "google_plus" => $request->google_plus,
        ];

        User::where('id', $user->id)->update($data);

        return back()->with('message', 'Update successfully.');
    }



    public function bookingList(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('m/signin');
        } else {

            $searched_from_date = $request->query('from_date');
            $searched_to_date = $request->query('to_date');
            $searched_space_name = $request->query('space_name');
            $searched_city = $request->query('city');
            $searched_list_id = $request->query('list_id');
            $searched_amount_from = $request->query('amount_from');
            $searched_amount_to = $request->query('amount_to');

            $sort = $request->query('sort');

            $searched_data = [
                "searched_from_date" => $searched_from_date,
                "searched_to_date" => $searched_to_date,
                "searched_space_name" => $searched_space_name,
                "searched_city" => $searched_city,
                "searched_list_id" => $searched_list_id,
                "searched_amount_from" => $searched_amount_from,
                "searched_amount_to" => $searched_amount_to,
            ];

            $booking_list = Booking::where('customer_id', $user->id)
                ->where('bravo_bookings.object_model', 'space')
                ->orderBy('bravo_bookings.id', 'desc')
                ->with('objectDetails');

            if ($searched_from_date) {
                $booking_list = $booking_list->where('bravo_bookings.start_date', '>', $searched_from_date);
            }

            if (CodeHelper::isNotEmpty($searched_to_date)) {
                $booking_list = $booking_list->where('bravo_bookings.end_date', '<', $searched_to_date);
            }
            if ($searched_space_name) {
                $space_ids = Space::where('title', 'like', '%' . $searched_space_name . '%')->pluck('id');
                $booking_list = $booking_list->whereIn('bravo_bookings.object_id', $space_ids);
            }
            if ($searched_city) {
                $space_ids = Space::where('address', 'like', '%' . $searched_city . '%')->pluck('id');
                $booking_list = $booking_list->whereIn('bravo_bookings.object_id', $space_ids);
            }
            if ($searched_list_id) {
                $booking_list = $booking_list->where('bravo_bookings.code', $searched_list_id);
            }
            if ($searched_amount_from) {
                $searched_amount_from = str_replace("$", "", $searched_amount_from);
                $booking_list = $booking_list->where('bravo_bookings.total', '>', $searched_amount_from);
            }
            if ($searched_amount_to) {
                $searched_amount_to = str_replace("$", "", $searched_amount_to);
                $booking_list = $booking_list->where('bravo_bookings.total', '<', $searched_amount_to);
            }

            $booking_list = $booking_list->join('bravo_spaces', 'bravo_bookings.object_id', '=', 'bravo_spaces.id');
            $booking_list = $booking_list->addSelect('bravo_bookings.*', 'bravo_spaces.*');
            $booking_list = $booking_list->addSelect('bravo_bookings.id as booking_id', 'bravo_spaces.id as space_id');

            switch ($sort) {
                case "name":
                    $booking_list = $booking_list->reorder('bravo_spaces.title', 'asc');
                    break;
                case "oldest":
                    $booking_list = $booking_list->reorder('bravo_bookings.created_at', 'asc');
                    break;
                case "newest":
                    $booking_list = $booking_list->reorder('bravo_bookings.created_at', 'desc');
                    break;
                case "costly":
                    $booking_list = $booking_list->reorder('bravo_bookings.total', 'desc');
                    break;
                case "cheap":
                    $booking_list = $booking_list->reorder('bravo_bookings.total', 'asc');
                    break;
            }

            // CodeHelper::debugQuery($booking_list);
            $booking_list = $booking_list->get();
            // dd($booking_list);

            return view('pwa/booking_list')->with('booking_list', $booking_list)->with('searched_data', $searched_data);
        }
    }


    public function spaceByCategory(Request $request, $category_id)
    {
        $space_ids_of_category = SpaceTerm::where('term_id', $category_id)->pluck('target_id');
        $list = Space::whereIn('id', $space_ids_of_category)->get();
        return view('pwa/category-spaces-list')->with('list', $list);
    }

    public function spaceByHost(Request $request, $host_id)
    {
        $host = ModelsUser::where('id', $host_id)->firstOrFail();
        $list = Space::where('create_user', $host_id)->limit(50)->get();
        return view('pwa/host-listing')->with('list', $list)->with('host', $host);
    }


    public function updateNotificationProfileDetails(Request $request)
    {
        $user = Auth::user();

        $email = $request->email;
        $phone = $request->phone;

        $check_email_exist = User::where('email', $email)->where('id', '!=', $user->id)->first();
        $check_phone_exist = User::where('phone', $phone)->where('id', '!=', $user->id)->first();

        if ($check_email_exist) {
            return back()->with('error', 'Email already Exist.');
        } elseif ($check_phone_exist) {
            return back()->with('error', 'Phone already Exist.');
        } else {
            User::where('id', $user->id)->update(['email' => $email, 'phone' => $phone]);
            return back()->with('success', 'Details updated successfully.');
        }
    }




    public function bookingDetails(Request $request, $booking_id)
    {

        $booking_details = Booking::where('id', $booking_id)->first();

        $space_details = Space::where('id', $booking_details->object_id)->first();

        $space_ids_of_category = SpaceTerm::where('target_id', $booking_details->object_id)->pluck('target_id');

        if (count($space_ids_of_category) > 0) {

            $similar_space = Space::whereIn('id', $space_ids_of_category)->where('id', '!=', $booking_details->object_id)->limit(5)->get();
            if ($similar_space) {
                $similar_space = Space::inRandomOrder()->limit(5)->get();
            }
        } else {
            $similar_space = Space::inRandomOrder()->limit(5)->get();
        }

        return view('pwa/booking_details')->with('space_details', $space_details)->with('similar_space', $similar_space)->with('booking_details', $booking_details);
    }



    public function supportSubmit(Request $request)
    {

        $user = Auth::user();

        $data = [
            'category' => $request->category,
            'issue' => $request->issue,
        ];


        // Mail::to('info@myoffice.ca')->send(new SubmitSupport($data, $user));
        Mail::to('kasyap459@gmail.com')->send(new SubmitSupport($data, $user));

        return response()->json(['status' => 'success']);
    }
}
