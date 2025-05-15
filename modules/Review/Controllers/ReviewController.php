<?php
namespace Modules\Review\Controllers;

use App\BaseModel;
use App\Helpers\CodeHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Booking\Models\Booking;
use Modules\Core\Events\CreateReviewEvent;
use Modules\Review\Models\Review;
use Modules\Review\Models\ReviewMeta;
use Modules\Space\Models\Space;
use Modules\User\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ReviewController extends Controller
{
    public function __construct()
    {
    }

    public function addReview(Request $request, $is_return = false)
    {
        $service_type = $request->input('review_service_type');
        $service_id = $request->input('review_service_id');
        $review_upload = $request->input('review_upload', []);
        $referenceId = $request->input('reference_id', null);

        $booking = Booking::where('id', $referenceId)->first();
        if ($booking == null) {
            return $this->sendError(__("Booking not found"));
        }

        $allServices = get_bookable_services();

        if (empty($allServices[$service_type])) {
            if ($is_return) {
                return $this->sendError(__("Service type not found"));
            } else {
                return redirect()->to(url()->previous() . '#review-form')->with('error', __('Service type not found'));
            }
        }

        $module_class = $allServices[$service_type];
        // dd($service_id);
        $module = $module_class::find($service_id);
        if (empty($module)) {
            if ($is_return) {
                return $this->sendError(__("Service not found"));
            } else {
                return redirect()->to(url()->previous() . '#review-form')->with('error', __('Service not found'));
            }
        }

        // $reviewEnable = $module->getReviewEnable();
        // if (!$reviewEnable) {
        //     if($is_return){
        //         return $this->sendError(__("Review not enable"));
        //     }else{
        //         return redirect()->to(url()->previous() . '#review-form')->with('error', __('Review not enable'));
        //     }
        // }
        // $reviewEnableAfterBooking = $module->check_enable_review_after_booking();
        // if (!$reviewEnableAfterBooking) {
        //     if($is_return){
        //         return $this->sendError(__("You need booking before write a review"));
        //     }else{
        //         return redirect()->to(url()->previous() . '#review-form')->with('error', __('You need booking before write a review'));
        //     }
        // }else{
        //     if (!$module->check_allow_review_after_making_completed_booking() ) {
        //         if($is_return){
        //             return $this->sendError(__("You can review after making completed booking"));
        //         }else{
        //             return redirect()->to(url()->previous() . '#review-form')->with('error', __('You can review after making completed booking'));
        //         }
        //     }  
        // }

        // if ($module->create_user == Auth::id()) {
        //     if($is_return){
        //         return $this->sendError(__("You cannot review your service"));
        //     }else{
        //         return redirect()->to(url()->previous() . '#review-form')->with('error', __('You cannot review your service'));
        //     }
        // }

        // $approvalRequried = $module->getReviewApproved();
        $approvalRequried = false;

        $rules = [
            'review_title' => 'required',
            'review_content' => 'required|min:10'
        ];
        $messages = [
            'review_title.required' => __('Review Title is required field'),
            'review_content.required' => __('Review Content is required field'),
            'review_content.min' => __('Review Content has at least 10 character'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            if ($is_return) {
                return $this->sendError($validator->errors());
            } else {
                return redirect()->to(url()->previous() . '#review-form')->withErrors($validator->errors());
            }
        }


        $review_stats = $request->input('review_stats');

        $total_point = 0;
        $total_count = 0;

        foreach ($review_stats as $key => $value) {
            $metaReview[] = [
                "object_id" => $service_id,
                "object_model" => $service_type,
                "name" => $key,
                "val" => $value ?? 0,
            ];
            $total_point += $value ?? 0;
            $total_count++;
        }

        $rate = round(($total_point / $total_count), 1);
        if ($rate > 5) {
            $rate = 5;
        }

        //$all_stats = setting_item($service_type . "_review_stats");
        // $metaReview = [];
        // if (!empty($all_stats)) {
        //     $all_stats = json_decode($all_stats, true);
        //     $total_point = 0;
        //     foreach ($all_stats as $key => $value) {
        //         if (isset($review_stats[$value['title']])) {
        //             $total_point += $review_stats[$value['title']];
        //         }
        //         $metaReview[] = [
        //             "object_id"    => $service_id,
        //             "object_model" => $service_type,
        //             "name"         => $value['title'],
        //             "val"          => $review_stats[$value['title']] ?? 0,
        //         ];
        //     }
        //     $rate = round($total_point / count($all_stats), 1);
        //     if ($rate > 5) {
        //         $rate = 5;
        //     }
        // } else {
        //     $rate = $request->input('review_rate');
        // }

        if (setting_item('review_upload_picture') && !empty($review_upload)) {
            $metaReview[] = [
                "object_id" => $service_id,
                "object_model" => $service_type,
                "name" => 'upload_picture',
                "val" => json_encode($review_upload)
            ];
        }

        // dd($booking->vendor_id == auth()->user()->id ? $booking->customer_id : $booking->vendor_id);

        $review = new Review([
            "object_id" => $service_id,
            "object_model" => $service_type,
            "title" => $request->input('review_title'),
            "content" => $request->input('review_content'),
            "rate_number" => $rate ?? 0,
            "author_ip" => $request->ip(),
            "status" => $approvalRequried ? "pending" : "approved",
            'vendor_id' => $module->create_user,
            "reference_id" => $referenceId,
            "review_by" => $booking->vendor_id == auth()->user()->id ? "host" : "guest",
            "review_to" => $booking->vendor_id == auth()->user()->id ? $booking->customer_id : $booking->vendor_id,
        ]);
        if ($review->save()) {
            if (!empty($metaReview)) {
                foreach ($metaReview as $meta) {
                    $meta['review_id'] = $review->id;
                    $reviewMeta = new ReviewMeta($meta);
                    $reviewMeta->save();
                }
            }
            $images = $request->input('review_upload');
            if (is_array($images) and !empty($images)) {
                foreach ($images as $image) {
                    if (!$this->validateUploadImage($image))
                        continue;
                    $review->addMeta('review_image', $image, true);
                }
            }

            $msg = __('Review has been sent!');
            if ($approvalRequried) {
                $msg = __("Review success! Please wait for admin approved!");
            }
            event(new CreateReviewEvent($module, $review));
            $module->update_service_rate($review->review_to);
            if ($is_return) {
                return $this->sendSuccess($msg);
            } else {
                return redirect()->to(url()->previous() . '#bravo-reviews')->with('success', $msg);
            }
        }
        if ($is_return) {
            return $this->sendError(__('Review error!'));
        } else {
            return redirect()->to(url()->previous() . '#review-form')->with('error', __('Review error!'));
        }
    }

    protected function validateUploadImage($image)
    {

        if (empty($image['file_extension']))
            return false;
        if (!in_array(strtolower($image['file_extension']), ['png', 'jpg', 'jpeg', 'gif', 'bmp']))
            return;

        if (empty($image['file_type']))
            return false;
        if (strpos(strtolower($image['file_type']), 'image/') !== 0)
            return false;

        return true;
    }


    public function index(Request $request)
    {
        $model = Review::query();
        $model->orderBy('id', 'desc');
        if (!empty($author = $request->input('customer_id'))) {
            $model->where('create_user', $author);
        }
        $allServices = get_bookable_services();
        $allServicesKeys = array_keys($allServices);

        if (!empty($search_name = $request->input('s'))) {
            $search_name = "%" . $search_name . "%";
            $model->whereRaw(" ( title LIKE ? OR author_ip LIKE ? OR content LIKE ? ) ", [$search_name, $search_name, $search_name]);
            $model->orderBy('title', 'asc');
        }
        if (!empty($status = $request->input('status'))) {
            $model->where('status', $status);
        }
        if (!empty($service_type = $request->input('service'))) {
            $model->where('object_model', $service_type);
        }
        if (!empty($service_id = $request->input('service_id'))) {
            $model->where('object_id', $service_id);
        }
        if (!empty($object_model = $request->input('object_model')) and in_array($object_model, $allServicesKeys)) {
            $model->where('object_model', $object_model);
        }
        $model->whereIn('object_model', $allServicesKeys);
        $data = [
            'rows' => $model->paginate(10),
            'breadcrumbs' => [
                [
                    'name' => __('Review'),
                    'class' => 'active'
                ],
            ],
            'page_title' => 'All Reviews'
        ];
        return view('Review::frontend.index', $data);
    }


    public function datatable(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $query = Review::query()->where(function ($query) use ($userId) {
            $query->where('review_to', $userId)
                ->orWhere('create_user', $userId);
        });

        $searchFilters = request()->input('search_query');
        $searchFilters = CodeHelper::cleanArray($searchFilters);

        if ($searchFilters == null) {
            $searchFilters = [];
        }

        $ratingFrom = 0;
        $ratingTo = 5;

        if (isset($searchFilters) && array_key_exists('rating_from', $searchFilters)) {
            $ratingsFrom = trim($searchFilters['rating_from']);
            if ($ratingsFrom != null) {
                $ratingFrom = $ratingsFrom;
            }
        }

        if (isset($searchFilters) && array_key_exists('rating_to', $searchFilters)) {
            $ratingsTo = trim($searchFilters['rating_to']);
            if ($ratingsTo != null) {
                $ratingTo = $ratingsTo;
            }
        }

        $query->where('rate_number', '>=', $ratingFrom);
        $query->where('rate_number', '<=', $ratingTo);


        if (array_key_exists('from', $searchFilters) && $searchFilters['from']) {
            $from = CodeHelper::dateConvertion($searchFilters['from']);
            if (!isset($from)) {
                $from = Carbon::now()->startOfYear();
            } else {
                $from = $from . " 00:00:00";
            }
            $query->where('created_at', '>=', $from);
        }

        if (array_key_exists('to', $searchFilters) && $searchFilters['to']) {
            $to = CodeHelper::dateConvertion($searchFilters['to']);
            if (!isset($to)) {
                $to = Carbon::now()->endOfYear();
            } else {
                $to = $to . " 23:59:59";
            }
            $query->where('created_at', '<=', $to);
        }

        if (array_key_exists('booking_id', $searchFilters) && trim($searchFilters['booking_id']) != null) {
            $query->where('reference_id', $searchFilters['booking_id']);
        }

        if (array_key_exists('object_id', $searchFilters) && trim($searchFilters['object_id']) != null) {
            $query->where('object_id', $searchFilters['object_id']);
        }


        BaseModel::buildFilterQuery($query, [
            'q' => ['id', 'title', 'content'],
            'status'
        ]);

        return DataTables::eloquent($query)
            ->addColumn('checkboxes', function ($model) {
                $select = '<input type="checkbox" name="checkbox[]" value="' . $model->id . '">';
                return $select;
            })
            ->addColumn('idLink', function ($model) {
                if (Auth::user()->hasPermissionTo('space_update')) {
                    return $model->id;
                }
                return $model->id;
            })
            ->addColumn('title', function ($model) {
                return $model->title;
            })
            ->addColumn('content', function ($model) {
                return $model->content;
            })
            ->addColumn('rating', function ($model) {
                return $model->rate_number;
            })
            ->addColumn('date', function ($model) {
                return CodeHelper::formatDateTime($model->created_at);
            })
            ->addColumn('booking', function ($model) {
                return '<a href="' . route('user.single.booking.detail', [$model->reference_id]) . '">#' . $model->reference_id . '</a>';
            })
            ->addColumn('reviewByUser', function ($model) use ($user) {
                if ($model->create_user == $user->id) {
                    return 'You';
                }
                $reviewUser = User::where('id',$model->create_user)->first();
                if ($reviewUser != null) {
                    return '<a href="' . route('user.profile', [$reviewUser->user_name]) . '">' . $reviewUser->getDisplayName() . '</a>';
                }
            })
            ->addColumn('status', function ($model) {
                return '<span class="badge badge-' . $model->status . '">' . $model->status . '</span>';
            })
            ->addColumn('actions', function ($row) use ($user) {
                $buttons = [];
                if (is_vendor() && $row->review_to == $user->id) {
                    $buttons = [
                        'hide' => ['url' => route("reviews.vendor.updateStatus", [$row->id, 'status' => "hidden"]), 'visible' => $row->status != 'hidden'],
                        'pending' => ['url' => route("reviews.vendor.updateStatus", [$row->id, 'status' => "pending"]), 'visible' => $row->status != 'pending'],
                        'approve' => ['url' => route("reviews.vendor.updateStatus", [$row->id, 'status' => "approved"]), 'visible' => $row->status != 'approved'],
                    ];
                }
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['checkboxes', 'status', 'reviewByUser', 'actions', 'idLink', 'booking', 'totalBookings', 'earnings', 'title'])
            ->make(true);
    }

    public function updateStatus($id)
    {
        $review = Review::where('id', $id)->firstOrFail();
        $review->status = $_GET['status'];
        $review->save();
        return redirect()->back()->with('success', 'Rating status has been updated');
    }


}
