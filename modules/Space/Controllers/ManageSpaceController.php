<?php

namespace Modules\Space\Controllers;

use App\BaseModel;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Notifications\AdminChannelServices;
use Carbon\Carbon;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\Core\Models\Settings;
use Modules\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Location\Models\LocationCategory;
use Modules\Space\Models\Space;
use Modules\Location\Models\Location;
use Modules\Core\Models\Attributes;
use Modules\Booking\Models\Booking;
use Modules\Coupon\Models\Coupon;
use Modules\Space\Models\SpaceBlockTime;
use Modules\Space\Models\SpaceTerm;
use Modules\Space\Models\SpaceTranslation;
use Yajra\DataTables\Facades\DataTables;

class ManageSpaceController extends FrontendController
{

    protected $spaceClass;
    protected $spaceTranslationClass;
    protected $spaceTermClass;
    protected $attributesClass;
    protected $locationClass;
    protected $bookingClass;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        parent::__construct();
        $this->spaceClass = Space::class;
        $this->spaceTranslationClass = SpaceTranslation::class;
        $this->spaceTermClass = SpaceTerm::class;
        $this->attributesClass = Attributes::class;
        $this->locationClass = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
        $this->bookingClass = Booking::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Space::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters);
    }

    public function manageSpace(Request $request)
    {
        $this->checkPermission('space_view');
        $user_id = Auth::id();
        $rows = $this->spaceClass::where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $rows->paginate(5),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Manage Spaces"),
        ];
        return view('Space::frontend.manageSpace.index', $data);
    }

    public function recovery(Request $request)
    {
        $this->checkPermission('space_view');
        $user_id = Auth::id();
        $rows = $this->spaceClass::onlyTrashed()->where("create_user", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $rows->paginate(5),
            'recovery' => 1,
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Recovery'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Recovery Spaces"),
        ];
        return view('Space::frontend.manageSpace.index', $data);
    }

    public function restore($id)
    {
        $this->checkPermission('space_delete');
        $user_id = Auth::id();
        $query = $this->spaceClass::onlyTrashed()->where("create_user", $user_id)->where("id", $id)->first();
        if (!empty($query)) {
            $query->restore();
            event(new UpdatedServiceEvent($query));
        }
        return redirect(route('space.vendor.recovery'))->with('success', __('Restore space success!'));
    }

    public function setDefultPrice(Request $request, $space_id)
    {
        $price_id = $request->input('price_id');
        $row = $this->spaceClass::find($space_id);
        switch ($price_id) {
            case 1:
                $row->hourly_price_set_default = 1;
                $row->daily_price_set_default = 0;
                $row->weekly_price_set_default = 0;
                $row->monthly_price_set_default = 0;
                $row->save();
                break;
            case 2:
                $row->hourly_price_set_default = 0;
                $row->daily_price_set_default = 1;
                $row->weekly_price_set_default = 0;
                $row->monthly_price_set_default = 0;
                $row->save();
                break;
            case 3:
                $row->hourly_price_set_default = 0;
                $row->daily_price_set_default = 0;
                $row->weekly_price_set_default = 1;
                $row->monthly_price_set_default = 0;
                $row->save();
                break;
            case 4:
                $row->hourly_price_set_default = 0;
                $row->daily_price_set_default = 0;
                $row->weekly_price_set_default = 0;
                $row->monthly_price_set_default = 1;
                $row->save();
        }
    }

    public function createSpace(Request $request)
    {
        $this->checkPermission('space_create');
        $row = new $this->spaceClass();

        if ($row->desk == null) {
            $row->desk = 1;
        }

        if ($row->seat == null) {
            $row->seat = 1;
        }

        if ($row->max_guests == null) {
            $row->max_guests = 1;
        }

        if ($row->prearrival_checkin_text == null) {
            $row->prearrival_checkin_text = 'Hi {firstname},

Your MyOffice Booking at {spacename} is coming up in {checkintime}. 

Please be sure to Check IN upon arrival:
{bookinglink}';
        }

        if ($row->arrival_checkin_text == null) {
            $row->arrival_checkin_text = 'Your MyOffice booking #{bookingno} at {spacename} is starting now. 

Please be sure to Check IN to let your Host know that you have arrived:
{checkinurl}';
        }

        if ($row->host_reminder_text == null) {
            $row->host_reminder_text = "Dear {FirstName},
                                            
Your Guest has not yet Checked IN for Booking #{bookingno}. 
                                        
Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
            
Booking Details 
Listing Name : {listingname}
Arrival Date and Time : {arrivaltime}
Departure Date and Time : {departuretime}
Cancellation Fee: {cancellationfee}

Contact Guest  | Manual Check IN | Edit Booking at following link
<a href='{bookinglink}'>Click Here</a>";
        }

        if ($row->departure_reminder_text == null) {
            $row->departure_reminder_text = 'Your MyOffice Booking #{bookingno} at {spacename} is ending in {checkouttime}.

Kindly prepare the office for departure and ensure the space is ready for next tenant.
    
Remember to Check OUT or you may EXTEND your stay (If available).
{checkouturl}';
        }

        if ($row->latecheckout_reminder_text == null) {
            $row->latecheckout_reminder_text = 'Your MyOffice Booking #{bookingno} at {spacename} has expired and you have not yet Checked OUT of the space.

Please note that it is important to notify your Host that you have departed, to ensure that your Space is checked for any damages or cleaning required.
    
Check OUT 
{checkouturl}';
        }

        $translation = new $this->spaceTranslationClass();

        $spaceSettings = Settings::getSettings('space');
        $translation->faqs = $spaceSettings['space_default_faqs'];

        $row->house_rules = $spaceSettings['space_default_house_rules'];
        $row->tos = $spaceSettings['space_default_terms'];

        $data = [
            'blockTimings' => [],
            'row' => $row,
            'translation' => $translation,
            'space_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes' => $this->attributesClass::where('service', 'space')->get(),
            'spaceSettings' => $spaceSettings,
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Create Spaces"),
        ];

        return view('Space::frontend.manageSpace.detail', $data);
    }


    public function store(Request $request, $id)
    {

        if ($id > 0) {
            $this->checkPermission('space_update');
            $row = $this->spaceClass::find($id);
            if (empty($row)) {
                return redirect(route('space.vendor.index'));
            }

            if ($row->create_user != Auth::id() and !$this->hasPermission('space_manage_others')) {
                return redirect(route('space.vendor.index'));
            }
        } else {
            $this->checkPermission('space_create');
            $row = new $this->spaceClass();
            $row->status = "publish";
            if (setting_item("space_vendor_create_service_must_approved_by_admin", 0)) {
                $row->status = "pending";
            }
        }

        $dataKeys = [
            'title',
            'alias',
            'house_rules',
            'tos',
            'content',
            'price',
            'is_instant',
            'video',
            'faqs',
            'image_id',
            'banner_image_id',
            'gallery',
            'desk',
            'seat',
            'square',
            'location_id',
            'address',
            'address_unit',
            'city',
            'state',
            'country',
            'zip',
            'map_lat',
            'map_lng',
            'map_zoom',
            'default_state',
            'price',
            'sale_price',
            'max_guests',
            'enable_extra_price',
            'extra_price',
            'is_featured',
            'default_state',
            'min_day_before_booking',
            'min_day_stays',
            'min_hour_stays',
            'enable_service_fee',
            'service_fee',
            'surrounding',
            'available_from',
            'available_to',
            'first_working_day',
            'last_working_day',
            'long_term_rental',
            'free_cancellation',
            'rapidbook',
            'discount',
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'discounted_hourly',
            'discounted_daily',
            'discounted_weekly',
            'discounted_monthly',
            'hours_after_full_day',
            'checkin_reminder_time',
            'prearrival_checkin_text',
            'arrival_checkin_reminder',
            'arrival_checkin_text',
            'host_checkin_reminder',
            'host_reminder_text',
            'checkout_reminder_time',
            'departure_reminder_text',
            'latecheckout_reminder_time',
            'latecheckout_reminder_text'
        ];

        if ($this->hasPermission('space_manage_others')) {
            $dataKeys[] = 'create_user';
        }

        $row->fillByAttr($dataKeys, $request->input());
        $row->ical_import_url = $request->ical_import_url;

        if ($request->input('long_term_rental') && $request->input('long_term_rental') == "on") {
            $row->long_term_rental = 1;
        } else {
            $row->long_term_rental = 0;
        }

        if ($request->input('rapidbook') && $request->input('rapidbook') == "on") {
            $row->rapidbook = 1;
        } else {
            $row->rapidbook = 0;
        }

        if ($request->input('accessible_workspace') && $request->input('accessible_workspace') == "on") {
            $row->accessible_workspace = 1;
        } else {
            $row->accessible_workspace = 0;
        }

        if ($request->input('free_cancellation') && $request->input('free_cancellation') == "on") {
            $row->free_cancellation = 1;
        } else {
            $row->free_cancellation = 0;
        }

        $row->fillByAttrNumber([
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'discounted_hourly',
            'discounted_daily',
            'discounted_weekly',
            'discounted_monthly'
        ], $request->input());

        if ($row->alias == null) {
            $row->alias = $row->title;
        }

        if (
            !CodeHelper::haveValidPrice($row->hourly) &&
            !CodeHelper::haveValidPrice($row->daily) &&
            !CodeHelper::haveValidPrice($row->weekly) &&
            !CodeHelper::haveValidPrice($row->monthly)
        ) {
            return redirect()->back()->with('error', 'Standard Rate is Required')->withInput();
        }

        $terms = $request->input('terms');

        if (is_array($terms) && count($terms) > 0) {
            $spaceTypes = [];
            $amenties = [];

            $attributes = $this->attributesClass::where('service', 'space')->get();
            if ($attributes != null) {
                foreach ($attributes as $attributeModel) {
                    if ($attributeModel->slug == "space-type") {
                        foreach ($attributeModel->terms as $termModel) {
                            $spaceTypes[] = $termModel->id;
                        }
                    } elseif ($attributeModel->slug == "amenities") {
                        foreach ($attributeModel->terms as $termModel) {
                            $amenties[] = $termModel->id;
                        }
                    }
                }
            }

            $spaceTypeMatches = array_intersect($terms, $spaceTypes);
            if (is_array($spaceTypeMatches) && count($spaceTypeMatches) <= 0) {
                return redirect()->back()->with('error', 'Select atleast one Space Type')->withInput();
            }

            $amentiesMatches = array_intersect($terms, $amenties);
            if (is_array($amentiesMatches) && count($amentiesMatches) < 3) {
                return redirect()->back()->with('error', 'Select atleast 3 Amenities')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Select atleast one Space Type and atleast 3 Amenities')->withInput();
        }

        // dd($request->all());
        // dd($row);

        $res = $row->saveOriginOrTranslation($request->input('lang'), true);

        if ($res) {

            $blockedTimings = isset($_POST['block_timings']) ? json_decode($_POST['block_timings'], true) : [];
            if (is_array($blockedTimings) && count($blockedTimings) > 0) {
                SpaceBlockTime::where('bravo_space_id', $row->id)->delete();
                foreach ($blockedTimings as $blockedTiming) {
                    $timeZone = isset($_POST['timezone']) ? trim($_POST['timezone']) : $_ENV['APP_DEFAULT_TIME_ZONE'];
                    if ($timeZone == null) {
                        $timeZone = $_ENV['APP_DEFAULT_TIME_ZONE'];
                    }

                    date_default_timezone_set($timeZone);

                    $blockedTiming['start_php_time'] = date('Y-m-d H:i:s', strtotime($blockedTiming['start']));
                    $blockedTiming['end_php_time'] = date('Y-m-d H:i:s', strtotime($blockedTiming['end']));

                    $startTime = $blockedTiming['start_php_time'];
                    $toTime = $blockedTiming['end_php_time'];

                    $model = new SpaceBlockTime();
                    $model->bravo_space_id = $row->id;
                    $model->from = $startTime;
                    $model->to = $toTime;
                    $model->data = json_encode($blockedTiming);
                    $model->created_at = $model->updated_at = date('Y-m-d H:i:s');
                    $model->save();
                }
            }

            if (!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            //coupons sync
            $coupons = $request->input('coupons');
            Coupon::where('object_id', $row->id)->where('object_model', 'space')->delete();
            if (is_array($coupons) && count($coupons) > 0) {
                foreach ($coupons as $couponData) {
                    if (array_key_exists('code', $couponData) && array_key_exists('amount', $couponData) && array_key_exists('type', $couponData)) {
                        $couponModel = new Coupon();
                        $couponModel->code = $couponData['code'];
                        $couponModel->amount = $couponData['amount'];
                        $couponModel->discount_type = $couponData['type'];
                        $couponModel->object_id = $row->id;
                        $couponModel->object_model = 'space';
                        $couponModel->status = 'publish';
                        $couponModel->save();
                    }
                }
            }

            if ($id > 0) {
                event(new UpdatedServiceEvent($row));

                return back()->with('success', __('Space updated'));
            } else {
                event(new CreatedServicesEvent($row));
                return redirect(route('space.vendor.edit', ['id' => $row->id]))->with('success', __('Space created'));
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->spaceTermClass::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->spaceTermClass::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->spaceTermClass::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function calendar(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $user_id = Auth::id();
        $row = $this->spaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));

        $blockTimings = [];

        $blockedTimings = SpaceBlockTime::where('bravo_space_id', $row->id)->get();
        if ($blockedTimings != null) {
            foreach ($blockedTimings as $blockedTiming) {
                $blockTimings[] = json_decode($blockedTiming->data, true);
            }
        }

        $data = [
            'blockedTimings' => $blockedTimings,
            'blockTimings' => $blockTimings,
            'translation' => $translation,
            'row' => $row,
            'space_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes' => $this->attributesClass::where('service', 'space')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Space Calendar"),
        ];
        return view('Space::frontend.manageSpace.calendar', $data);
    }

    public function editSpace(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $user_id = Auth::id();
        $row = $this->spaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));

        $blockTimings = [];

        $blockedTimings = SpaceBlockTime::where('bravo_space_id', $row->id)->get();
        if ($blockedTimings != null) {
            foreach ($blockedTimings as $blockedTiming) {
                $blockTimings[] = json_decode($blockedTiming->data, true);
            }
        }

        // dd($blockTimings);
        $coupons = Coupon::where('object_id', $row->id)->where('object_model', 'space')->get();
        $row->coupons = $coupons;

        $spaceSettings = Settings::getSettings('space');

        $data = [
            'blockedTimings' => $blockedTimings,
            'blockTimings' => $blockTimings,
            'translation' => $translation,
            'spaceSettings' => $spaceSettings,
            'row' => $row,
            'space_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes' => $this->attributesClass::where('service', 'space')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Edit Spaces"),
        ];
        return view('Space::frontend.manageSpace.detail', $data);
    }

    public function spaceEarningReports(Request $request)
    {
        $id = $request->input('id');
        $this->checkPermission('space_update');
        $user_id = Auth::id();
        $row = $this->spaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));

        $blockTimings = [];

        $blockedTimings = SpaceBlockTime::where('bravo_space_id', $row->id)->get();
        if ($blockedTimings != null) {
            foreach ($blockedTimings as $blockedTiming) {
                $blockTimings[] = json_decode($blockedTiming->data, true);
            }
        }

        // dd($blockTimings);
        $coupons = Coupon::where('object_id', $row->id)->where('object_model', 'space')->get();
        $row->coupons = $coupons;

        $userSpaces = Space::where('create_user', $user_id)->get();

        $stats = [$id];

        $vendorSpaceIds = [$id];

        $stats['ratings'] = CodeHelper::getRatingsBySpaces($vendorSpaceIds);
        $stats['analytics'] = CodeHelper::getAnalyticsBySpaces($vendorSpaceIds);

        $data = [
            'blockedTimings' => $blockedTimings,
            'blockTimings' => $blockTimings,
            'translation' => $translation,
            'row' => $row,
            'space_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes' => $this->attributesClass::where('service', 'space')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Space Earning Report"),
            'id' => $id,
            'stats' => $stats,
            'userSpaces' => $userSpaces
        ];
        return view('Space::frontend.manageSpace.earning-reports', $data);
    }


    public function allEarningReports(Request $request)
    {
        $this->checkPermission('space_update');
        $user_id = Auth::id();

        $userSpaces = Space::where('create_user', $user_id)->get();
        $id = $userSpaces[0]['id'];

        $row = $this->spaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));

        $blockTimings = [];

        $blockedTimings = SpaceBlockTime::where('bravo_space_id', $row->id)->get();
        if ($blockedTimings != null) {
            foreach ($blockedTimings as $blockedTiming) {
                $blockTimings[] = json_decode($blockedTiming->data, true);
            }
        }

        // dd($blockTimings);
        $coupons = Coupon::where('object_id', $row->id)->where('object_model', 'space')->get();
        $row->coupons = $coupons;

        $userSpaces = Space::where('create_user', $user_id)->get();

        $stats = [$id];

        $vendorSpaceIds = [];

        $stats['ratings'] = CodeHelper::getRatingsBySpaces($vendorSpaceIds);
        $stats['analytics'] = CodeHelper::getAnalyticsBySpaces($vendorSpaceIds);

        $data = [
            'blockedTimings' => $blockedTimings,
            'blockTimings' => $blockTimings,
            'translation' => $translation,
            'row' => $row,
            'space_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'attributes' => $this->attributesClass::where('service', 'space')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Spaces'),
                    'url' => route('space.vendor.index')
                ],
                [
                    'name' => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Space Earning Report"),
            'id' => $id,
            'stats' => $stats,
            'userSpaces' => $userSpaces
        ];
        return view('Space::frontend.manageSpace.all-earning-reports', $data);
    }

    public function spaceEarningReportStats(Request $request)
    {
        $spaceId = $request->get('spaceId');
        $user_id = Auth::id();
        $earningStats = Booking::getEarningStats($user_id, $request->get('durationType'), [$spaceId]);
        return response()->json($earningStats);
    }

    public function deleteSpace($id)
    {
        $this->checkPermission('space_delete');
        $user_id = Auth::id();
        if (\request()->query('permanently_delete')) {
            $query = $this->spaceClass::where("create_user", $user_id)->where("id", $id)->withTrashed()->first();
            if (!empty($query)) {
                $query->forceDelete();
            }
        } else {
            $query = $this->spaceClass::where("create_user", $user_id)->where("id", $id)->first();
            if (!empty($query)) {
                $query->delete();
                event(new UpdatedServiceEvent($query));
            }
        }
        return redirect(route('space.vendor.index'))->with('success', __('Delete space success!'));
    }

    public function bulkEditSpace($id, Request $request)
    {
        $this->checkPermission('space_update');
        $action = $request->input('action');
        $user_id = Auth::id();
        $query = $this->spaceClass::where("create_user", $user_id)->where("id", $id)->first();
        if (empty($id)) {
            return redirect()->back()->with('error', __('No item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if (empty($query)) {
            return redirect()->back()->with('error', __('Not Found'));
        }
        switch ($action) {
            case "make-hide":
                $query->status = "draft";
                break;
            case "make-publish":
                $query->status = "publish";
                break;
        }
        $query->save();
        event(new UpdatedServiceEvent($query));

        return redirect()->back()->with('success', __('Update success!'));
    }

    public function bookingReportBulkEdit($booking_id, Request $request)
    {
        $status = $request->input('status');
        if (!empty(setting_item("space_allow_vendor_can_change_their_booking_status")) and !empty($status) and !empty($booking_id)) {
            $query = $this->bookingClass::where("id", $booking_id);
            $query->where("vendor_id", Auth::id());
            $item = $query->first();
            if (!empty($item)) {
                $item->status = $status;
                $item->save();

                if ($status == Booking::CANCELLED)
                    $item->tryRefundToWallet();

                event(new BookingUpdatedEvent($item));
                return redirect()->back()->with('success', __('Update success'));
            }
            return redirect()->back()->with('error', __('Booking not found!'));
        }
        return redirect()->back()->with('error', __('Update fail!'));
    }

    public function cloneSpace(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $user_id = Auth::id();
        $row = $this->spaceClass::where("create_user", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('space.vendor.index'))->with('warning', __('Space not found!'));
        }
        try {
            $clone = $row->replicate();
            $clone->status = 'draft';
            $clone->push();
            if (!empty($row->terms)) {
                foreach ($row->terms as $term) {
                    $e = $term->replicate();
                    if ($e->push()) {
                        $clone->terms()->save($e);
                    }
                }
            }
            if (!empty($row->meta)) {
                $e = $row->meta->replicate();
                if ($e->push()) {
                    $clone->meta()->save($e);
                }
            }
            if (!empty($row->translations)) {
                foreach ($row->translations as $translation) {
                    $e = $translation->replicate();
                    $e->origin_id = $clone->id;
                    if ($e->push()) {
                        $clone->translations()->save($e);
                    }
                }
            }

            return redirect()->back()->with('success', __('Space clone was successful'));
        } catch (\Exception $exception) {
            $clone->delete();
            return redirect()->back()->with('warning', __($exception->getMessage()));
        }
    }


    public function updateBooking($id, Request $request)
    {
        $status = 'error';
        $message = 'Failed to save Booking';

        $query = $this->bookingClass::where("id", $id);
        $item = $query->first();

        if (!empty($item)) {

            $startDateMain = $request->get('start');
            $toDateMain = $request->get('end');
            $status = 'ok';

            $message = '';

            $objectId = $item->object_id;

            if ($startDateMain != null && $toDateMain != null) {

                $bookingBetween = Booking::whereRaw("`status` != 'draft' and `object_model` = 'space' and `id` != " . $id . " and `object_id` = " . $objectId . " and ( (`start_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`end_date` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `start_date` and `end_date`) OR ('$toDateMain' BETWEEN `start_date` and `end_date`) )")->orderBy('start_date')->get();

                if ($bookingBetween != null && count($bookingBetween) > 0) {
                    $status = 'error';
                    $message = 'There are some booking(s) in selected date';
                }


                if ($status == 'ok') {

                    $blockedBetween = SpaceBlockTime::whereRaw("`bravo_space_id` = " . $objectId . " and ( (`from` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR (`to` BETWEEN '" . $startDateMain . "' and '" . $toDateMain . "') OR ('$startDateMain' BETWEEN `from` and `to`) OR ('$toDateMain' BETWEEN `from` and `to`) )")->get();
                    if ($blockedBetween != null && count($blockedBetween) > 0) {
                        $status = 'error';
                        $message = 'Selected date already blocked';
                    }
                }


                if ($status == 'ok') {

                    $item->start_date = $startDateMain;
                    $item->end_date = $toDateMain;
                    $item->update();
                }
            } else {
                $message = 'Start and End dates are required';
            }
        } else {
            $message = 'Booking not found';
        }


        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function datatable(Request $request)
    {
        $this->checkPermission('space_view');
        $user_id = Auth::id();

        $query = $this->spaceClass::where("create_user", $user_id);

        $spaceIds = [];
        $haveOtherFilters = false;

        $searchFilters = request()->input('search_query');
        $searchFilters = CodeHelper::cleanArray($searchFilters);

        $earningFrom = 0;
        $earningTo = 10000000000; //kinda max

        $haveEarningsFilter = false;

        if (isset($searchFilters) && array_key_exists('earnings_from', $searchFilters)) {
            $earningsFrom = trim($searchFilters['earnings_from']);
            if ($earningsFrom != null) {
                $haveEarningsFilter = true;
                $earningFrom = $earningsFrom;
            }
        }

        if (isset($searchFilters) && array_key_exists('earnings_to', $searchFilters)) {
            $earningsTo = trim($searchFilters['earnings_to']);
            if ($earningsTo != null) {
                $haveEarningsFilter = true;
                $earningTo = $earningsTo;
            }
        }

        if ($haveEarningsFilter) {
            $haveOtherFilters = true;
            $spaceModelsIds = Booking::where([
                'object_model' => 'space'
            ])
                ->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                ->where(function ($query) use ($user_id) {
                    $query->where(Space::getTableName() . '.create_user', '=', $user_id);
                    $query->where(Booking::getTableName() . '.status', '!=', Constants::BOOKING_STATUS_DRAFT);
                })
                ->selectRaw(Space::getTableName() . '.id , SUM(' . Booking::getTableName() . '.host_amount' . ') as totalEarnings')
                ->havingRaw('totalEarnings >= ' . $earningFrom)
                ->havingRaw('totalEarnings <= ' . $earningTo)
                ->groupBy(Booking::getTableName() . '.object_id')
                ->pluck(Space::getTableName() . '.id')->toArray();

            if (is_array($spaceModelsIds) && count($spaceModelsIds) > 0) {
                $spaceIds = array_merge($spaceIds, $spaceModelsIds);
            }
        }


        if (array_key_exists('from', $searchFilters) && $searchFilters['from']) {
            $haveOtherFilters = true;
            $from = CodeHelper::dateConvertion($searchFilters['from']);
            if (!isset($from)) {
                $from = Carbon::now()->startOfYear();
            } else {
                $from = $from . " 00:00:00";
            }
            $spaceModelsIds = Booking::where([
                'object_model' => 'space'
            ])
                ->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                ->where(function ($query) use ($user_id) {
                    $query->where(Space::getTableName() . '.create_user', '=', $user_id);
                })
                ->whereRaw("`" . Booking::getTableName() . "`.`start_date` >= '" . $from . "'")
                ->groupBy(Booking::getTableName() . '.object_id')
                ->pluck(Space::getTableName() . '.id')->toArray();

            if (is_array($spaceModelsIds) && count($spaceModelsIds) > 0) {
                $spaceIds = array_merge($spaceIds, $spaceModelsIds);
            }
        }

        if (array_key_exists('to', $searchFilters) && $searchFilters['to']) {
            $haveOtherFilters = true;
            $to = CodeHelper::dateConvertion($searchFilters['to']);
            if (!isset($to)) {
                $to = Carbon::now()->endOfYear();
            } else {
                $to = $to . " 23:59:59";
            }
            $spaceModelsIds = Booking::where([
                'object_model' => 'space'
            ])
                ->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                ->where(function ($query) use ($user_id) {
                    $query->where(Space::getTableName() . '.create_user', '=', $user_id);
                })
                ->whereRaw("`" . Booking::getTableName() . "`.`end_date` <= '" . $to . "'")
                ->groupBy(Booking::getTableName() . '.object_id')
                ->pluck(Space::getTableName() . '.id')->toArray();

            if (is_array($spaceModelsIds) && count($spaceModelsIds) > 0) {
                $spaceIds = array_merge($spaceIds, $spaceModelsIds);
            }
        }

        if ($haveOtherFilters) {
            if (count($spaceIds) <= 0) {
                $spaceIds = [-1];
            }
        }

        if (count($spaceIds) > 0) {
            $query->whereIn('id', $spaceIds);
        }

        if (array_key_exists('city', $searchFilters) && $searchFilters['city'] && $searchFilters['city'] != "") {
            $query->where('city', 'LIKE', '%' . $searchFilters['city'] . '%');
        }

        // CodeHelper::debugQuery($query);

        BaseModel::buildFilterQuery($query, [
            'q' => ['id', 'title', 'alias'],
            'address',
            'status'
        ]);

        return DataTables::eloquent($query)
            ->addColumn('checkboxes', function ($model) {
                $select = '<input type="checkbox" name="checkbox[]" value="' . $model->id . '">';
                return $select;
            })
            ->addColumn('idLink', function ($model) {
                if (Auth::user()->hasPermissionTo('space_update')) {
                    return '<a href="' . route('space.vendor.edit', [$model->id]) . '">' . $model->id . '</a>';
                }
                return $model->id;
            })
            ->addColumn('title', function ($model) {
                if (Auth::user()->hasPermissionTo('space_update')) {
                    return '<a target="_blank" href="' . route('space.vendor.edit', [$model->id]) . '">' . $model->title . '</a>';
                }
                return $model->title;
            })
            ->addColumn('status', function ($model) {
                return '<span class="badge badge-' . $model->status . '">' . $model->status_text . '</span>';
            })
            ->addColumn('lastBooking', function ($model) {
                $lastBooking = $model->lastBooking();
                if ($lastBooking != null) {
                    $text = CodeHelper::formatDateTime($lastBooking->start_date);
                    return '<a href="' . route('user.single.booking.detail', $lastBooking->id) . '">' . $text . '</a>';
                }
                return '-';
            })
            ->addColumn('totalBookings', function ($model) {
                $text = $model->totalBookings();
                return '<a href="' . route('user.bookings.details') . '?type=all&space=' . $model->id . '">' . $text . '</a>';
            })
            ->addColumn('alias', function ($model) {
                return $model->alias != null ? $model->alias : '-';
            })
            ->addColumn('earnings', function ($model) {
                $total = CodeHelper::formatPrice($model->totalEarnings());
                return '<a href="' . route('space.vendor.spaceEarningReports', ['id' => $model->id]) . '">' . $total . '</a>';
            })
            ->addColumn('price', function ($model) {
                return CodeHelper::formatPrice($model->price);
            })
            ->addColumn('actions', function ($row) {
                $buttons = [
                    'view' => ['url' => $row->getDetailUrl(), 'target' => '_blank'],
                    'calendar' => ['url' => route('space.vendor.calendar', [$row->id]), 'class' => 'viewSpaceCalendars', 'extra' => ['data-id' => $row->id]],
                    'edit' => ['url' => route('space.vendor.edit', [$row->id]), 'visible' => Auth::user()->hasPermissionTo('space_update')],
                    'clone' => ['url' => route('space.vendor.clone', [$row->id]), 'target' => '_blank'],
                    'hide' => ['url' => route("space.vendor.bulk_edit", [$row->id, 'action' => "make-hide"]), 'visible' => $row->status == 'publish'],
                    'publish' => ['url' => route("space.vendor.bulk_edit", [$row->id, 'action' => "make-publish"]), 'visible' => $row->status == 'draft'],
                    'delete' => ['url' => route('space.vendor.delete', [$row->id]), 'visible' => Auth::user()->hasPermissionTo('space_delete'), 'extra' => ['data-confirm' => 'Are you sure you want to Delete this Space? <br/> This cannot be undone.']],
                ];
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['checkboxes', 'status', 'actions', 'idLink', 'lastBooking', 'totalBookings', 'earnings', 'title'])
            ->make(true);
    }
}
