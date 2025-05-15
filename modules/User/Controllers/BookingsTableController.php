<?php

namespace Modules\User\Controllers;

use App\BaseModel;
use App\Exports\BookingHistoryExport;
use App\Exports\BookingTransactionHistoryExport;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Modules\Core\Models\Terms;
use Modules\Location\Models\Location;
use Modules\Space\Models\Space;
use Yajra\DataTables\Facades\DataTables;
use Modules\Space\Models\SpaceTerm;
use PDF;

class BookingsTableController extends Controller
{
    public function dateConvertion($date)
    {
        $d = explode('/', $date);
        $date = $d[2] . '-' . $d[0] . '-' . $d[1];
        return $date;
    }

    //booking history datatable
    public function __invoke(Request $request)
    {
        $user_id = Auth::user()->id;

        $searchQuery = request()->search_query;
        $tType = null;

        $bookings = Booking::orderBy('id', 'DESC')->where(function ($query) use ($user_id) {
            $query->where('customer_id', '=', $user_id)
                ->orWhere('vendor_id', '=', $user_id);
        });

        if (array_key_exists('tType', $searchQuery)) {
            $tType = $searchQuery['tType'];
            if ($tType === "bookings" || $tType === "earnings") {
                $bookings = Booking::orderBy('id', 'DESC')->where('vendor_id', '=', $user_id);
            }
        }

        $showOnlyArchived = false;

        if (array_key_exists('date_option', $searchQuery)) {
            switch ($searchQuery['date_option']) {
                case 'yesterday':
                    $bookings = $bookings->whereDate('start_date', '=', Carbon::now()->subDay());
                    break;
                case 'today':
                    $bookings = $bookings->whereDate('start_date', '=', Carbon::now());
                    break;
                case 'this_weekdays':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek()->subDays(2);
                    $bookings = $bookings->whereBetween('start_date', [$start, $end]);
                    break;
                case 'this_whole_week':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    $bookings = $bookings->whereBetween('start_date', [$start, $end]);
                    break;
                case 'this_month':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $bookings = $bookings->whereBetween('start_date', [$start, $end]);
                    break;
                case 'this_year':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    $bookings = $bookings->whereBetween('start_date', [$start, $end]);
                    break;
                default:
                    break;
            }
        }

        if (array_key_exists('status', $searchQuery)) {
            switch ($searchQuery['status']) {
                case 'archived':
                    $showOnlyArchived = true;
                    break;
                case 'scheduled':
                    // $bookings = $bookings->whereDate('end_date', '>=', Carbon::now());
                    $bookings = $bookings->where('status', 'scheduled');
                    break;
                case 'pending':
                    $bookings = $bookings->where('status', 'draft');
                    break;
                case 'history':
                    // $bookings = $bookings->whereDate('end_date', '<', Carbon::now());
                    $bookings = $bookings->where('status', 'complete');
                    break;
                case 'active':
                case 'booked':
                    $bookings = $bookings->whereIn('status', [
                        Constants::BOOKING_STATUS_BOOKED,
                        Constants::BOOKING_STATUS_CHECKED_IN,
                        Constants::BOOKING_STATUS_CHECKED_OUT
                    ]);
                    break;
                case 'completed':
                    $bookings = $bookings->where('status', Constants::BOOKING_STATUS_COMPLETED);
                    break;
                case 'all':
                    $bookings = $bookings;
                    break;
                default:
                    break;
            }
        }

        if (array_key_exists('transaction_status', $searchQuery)) {
            switch ($searchQuery['transaction_status']) {
                case 'paid':
                    $bookings = $bookings->leftJoin(Payment::getTableName(), 'payment_id', '=', Payment::getTableName() . '.id')
                        ->where(function ($query) {
                            $query->where(Payment::getTableName() . '.status', '=', 'completed');
                        })->select(Payment::getTableName() . '.id');
                    break;
                case 'unpaid':
                    $bookings = $bookings->leftJoin(Payment::getTableName(), Booking::getTableName() . '.payment_id', '=', Payment::getTableName() . '.id')
                        ->where(function ($query) {
                            $query->where(Payment::getTableName() . '.status', '=', 'draft')->orWhereNull(Booking::getTableName() . '.payment_id');
                        })->select(Payment::getTableName() . '.id');
                    break;
                case 'fail':
                    $bookings = $bookings->leftJoin(Payment::getTableName(), 'payment_id', '=', Payment::getTableName() . '.id')
                        ->where(function ($query) {
                            $query->where(Payment::getTableName() . '.status', '=', 'fail');
                        })->select(Payment::getTableName() . '.id');
                    break;
                default:
                    break;
            }
        }

        if (array_key_exists('from', $searchQuery) && $searchQuery['from']) {
            $from = $this->dateConvertion($searchQuery['from']);
            if (!isset($from)) {
                $from = Carbon::now()->startOfYear();
            } else {
                $from = $from . " 00:00:00";
            }
            $bookings = $bookings->where('start_date', '>=', $from);
        }

        if (array_key_exists('to', $searchQuery) && $searchQuery['to']) {
            $to = $this->dateConvertion($searchQuery['to']);
            if (!isset($to)) {
                $to = Carbon::now()->startOfYear();
            } else {
                $to = $to . " 23:59:59";
            }
            $bookings = $bookings->where('end_date', '<=', $to);
        }

        // if (array_key_exists('city', $searchQuery) && $searchQuery->city != '') {
        //     $city_id = $searchQuery->city;
        //     $ids = Space::where('location_id', $city_id)->pluck('id')->toArray();
        //     $bookings = $bookings->whereIn('object_id', $ids);
        // }

        if (array_key_exists('search', $searchQuery) && $searchQuery['search'] != '') {
            $searchQueryData = trim($searchQuery['search']);
            if ($searchQueryData != null) {
                $bookings = $bookings->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                    ->where(function ($query) use ($searchQueryData) {
                        $query->where(Space::getTableName() . '.address', 'like', '%' . $searchQueryData . '%');
                        $query->orWhere(Booking::getTableName() . '.id', 'like', '%' . $searchQueryData . '%');
                    })->select(Space::getTableName() . '.id');
            }
        }

        if (array_key_exists('space', $searchQuery) && $searchQuery['space'] != '') {
            $spaceSearchData = trim($searchQuery['space']);
            if ($spaceSearchData != null) {
                $spaceExist = Space::where('id', $spaceSearchData)->first();
                if ($spaceExist != null) {
                    $bookings = $bookings->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                        ->where(function ($query) use ($spaceSearchData) {
                            $query->where(Space::getTableName() . '.id', '=', $spaceSearchData);
                        })->select(Space::getTableName() . '.id');
                } else {
                    $bookings = $bookings->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                        ->where(function ($query) use ($spaceSearchData) {
                            $query->where(Space::getTableName() . '.title', 'like', '%' . $spaceSearchData . '%');
                            $query->orWhere(Space::getTableName() . '.id', 'like', '%' . $spaceSearchData . '%');
                        })->select(Space::getTableName() . '.id');
                }
            }
        } else {
            //top_search
            $isVendor = is_vendor() ? true : false;
            if (array_key_exists('top_search', $searchQuery) && $searchQuery['top_search'] != '') {
                $topSearch = $searchQuery['top_search'];
                $userIds = User::whereRaw('CONCAT(`first_name`, " ",`last_name`) LIKE "%' . $topSearch . '%"')->pluck('id')->toArray();
                $bookings = $bookings->leftJoin(Space::getTableName(), Space::getTableName() . '.id', '=', Booking::getTableName() . '.object_id')
                    ->where(function ($query) use ($topSearch, $userIds, $isVendor) {
                        $query->where(Space::getTableName() . '.title', 'like', '%' . $topSearch . '%');
                        $query->orWhere(Booking::getTableName() . '.id', 'like', '%' . $topSearch . '%');
                        if ($isVendor) {
                            $query->orWhereIn(Booking::getTableName() . '.vendor_id', $userIds);
                        } else {
                            $query->orWhereIn(Booking::getTableName() . '.customer_id', $userIds);
                        }
                    })->select(Space::getTableName() . '.id');
            }
        }

        if (array_key_exists('guest', $searchQuery) && $searchQuery['guest'] != '') {
            $guestName = $searchQuery['guest'];
            $userIds = User::whereRaw('CONCAT(`first_name`, " ",`last_name`) LIKE "%' . $guestName . '%"')->pluck('id')->toArray();
            if ($userIds == null) {
                $userIds = [-1];
            }
            $bookings = $bookings->where('customer_id', $userIds);
        }

        if (array_key_exists('booking_status', $searchQuery) && $searchQuery['booking_status'] != '') {
            $booking_status = $searchQuery['booking_status'];
            if ($booking_status == "archived") {
                $showOnlyArchived = true;
            } else {
                switch ($booking_status) {
                    case 'booked':
                        $bookings = $bookings->whereIn('status', [
                            Constants::BOOKING_STATUS_BOOKED
                        ]);
                        break;
                    case 'checked-in':
                        $bookings = $bookings->whereIn('status', [
                            Constants::BOOKING_STATUS_CHECKED_IN
                        ]);
                        break;
                    case 'completed':
                        // $bookings = $bookings->whereIn('status', array_keys(Constants::NON_CANCELLED_BOOKING_STATUES));
                        $bookings = $bookings->where('status', Constants::BOOKING_STATUS_COMPLETED);
                        break;
                    case 'all':
                        $bookings = $bookings;
                        break;
                    default:
                        $bookings = $bookings->where('status', $booking_status);
                        break;
                }
            }
        } else {
            $bookings = $bookings->whereIn('bravo_bookings.status', array_keys(Constants::BOOKING_STATUES));
        }

        if (array_key_exists('category', $searchQuery) && $searchQuery['category'] != '') {
            $category_id = $searchQuery['category'];
            $space_ids = SpaceTerm::where('term_id', $category_id)->pluck('target_id')->toArray();
            $bookings = $bookings->whereIn('object_id', $space_ids);
        }

        if (array_key_exists('id', $searchQuery) && $searchQuery['id'] != '') {
            $id = $searchQuery['id'];
            $bookings = $bookings->where('id', $id);
        }

        if (array_key_exists('amount', $searchQuery) && $searchQuery['amount'] != '') {
            $amount = $searchQuery['amount'];
            $bookings = $bookings->where('total', 'LIKE', '%' . $amount . '%');
        }

        if ($showOnlyArchived) {
            $bookings = $bookings->where('is_archive', 1);
        } else {
            $bookings = $bookings->where('is_archive', '!=', 1);
        }



        $tableColumns = CodeHelper::getTableColumns(Booking::getTableName());
        $bookings = $bookings->select($tableColumns);

        // print_r($bookings->getBindings());
        // echo $bookings->toSql();die;

        $bookings = $bookings->get();

        $dataTable = DataTables::of($bookings)
            ->addColumn('checkboxes', function ($booking) {
                $select = '<input type="checkbox" name="checkbox[]" value="' . $booking->id . '">';
                return $select;
            })
            ->addColumn('title', function ($booking) {
                $space = Space::where('id', $booking->object_id)->first();
                if ($space == null) {
                    return '-';
                }
                $title = $space->translateOrOrigin(app()->getLocale());
                return '<a target="_blank" href="' . $space->getDetailUrl($include_param ?? true) . '">' .
                    clean($title->title) . '
                                </a>';
            })
            ->addColumn('address', function ($booking) {
                $space = Space::where('id', $booking->object_id)->first();
                if ($space == null) {
                    return '-';
                }
                return $space->address;
            })
            ->addColumn('categories', function ($booking) {
                $categories = SpaceTerm::where('target_id', $booking->object_id)->pluck('term_id')->toArray();
                if (!empty(Terms::whereIn('id', $categories)->where('attr_id', 3)->pluck('name')->toArray())) {
                    $spaceCats = Terms::whereIn('id', $categories)->where('attr_id', 3)->pluck('name')->toArray();
                    if (count($spaceCats) > 1) {
                        return 'Multi';
                    }
                    return $spaceCats;
                } else {
                    return 'Not Available';
                }
            })
            ->addColumn('id', function ($booking) {
                return '<a target="_blank" href="' . route('user.single.booking.detail', $booking->id) . '">' . $booking->id . '</a>';
            })
            ->addColumn('start_date', function ($booking) {
                $start_date = date("M j, Y", strtotime($booking->start_date));
                $start_date2 = date("h:i A", strtotime($booking->start_date));
                return '<a target="_blank" href="' . route('user.single.booking.detail', $booking->id) . '">' . $start_date . '</br>' . $start_date2 . '</a>';
            })
            ->addColumn('end_date', function ($booking) {
                $end_date = date("M j, Y", strtotime($booking->end_date));
                $end_date2 = date("h:i A", strtotime($booking->end_date));
                return '<a target="_blank" href="' . route('user.single.booking.detail', $booking->id) . '">' . $end_date . '</br>' . $end_date2 . '</a>';
            })
            ->addColumn('totalFormatted', function ($booking) {
                $total = CodeHelper::formatPrice($booking->total);
                return $total;
            })
            ->addColumn('hostAmountFormatted', function ($booking) {
                $total = CodeHelper::formatPrice($booking->host_amount);
                return $total;
            })
            ->addColumn('guest', function ($booking) {
                $customerId = $booking->customer_id;
                $customer = User::where('id', $customerId)->first();
                if ($customer != null) {
                    return '<a target="_blank" href="' . route('user.profile.publicProfile', $booking->customer_id) . '">' . $customer->first_name . " " . $customer->last_name . " (Guest#" . $customer->id . ")" . '<a/>';
                }
                return '-';
            })
            ->addColumn('booking_status', function ($booking) {
                $book_status = $booking->statusText();
                $book_class = $booking->statusClass();
                return '<span class="status-btn ' . $book_class . '">' . strtoupper($book_status) . '</span>';
            })
            ->addColumn('transaction_status', function ($booking) {
                $payment = Payment::where('booking_id', $booking->id)->first();
                if ($payment) {
                    $payment_status = ($payment->status == "completed") ? "PAID" : 'UNPAID';
                } else {
                    $payment_status = "UNPAID";
                }
                return strtoupper($payment_status);
            })
            ->addColumn('actions', function ($booking) {
                $buttons = [
                    // 'edit' => ['url' => 'javascript:;', 'class' => 'modifySingleBooking', 'extra' => ['data-value' => $booking->id, 'data-details' => json_encode($booking)]],                  
                    'view' => ['url' => route('user.single.booking.detail', $booking->id)],
                    'invoice' => ['url' => route('user.booking.invoice', $booking->code)],
                    'share' => ['url' => route('user.single.booking.detail', $booking->id), 'class' => 'sharer'],
                    'archive' => ['url' => route('user.booking.archive', $booking->id)],
                    'checkin' => ['url' => route('user.booking.checkin', $booking->id), 'class' => 'checkInMessage', 'style' => 'background-image:url({{asset(icon/mo_checkin.svg)}})'],
                    'checkout' => ['url' => route('user.booking.checkout', $booking->id), 'class' => 'checkOutMessage', 'style' => 'background-image:url({{asset(icon/mo_checkout.svg)}})'],
                ];
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['title', 'city', 'booking_status', 'guest', 'transaction_status', 'checkboxes', 'actions', 'id', 'start_date', 'end_date', 'booking_status'])
            ->make(true);

        $quantity = count($bookings);

        $grandTotal = $bookings->sum('total');
        $hostAmountTotal = $bookings->sum('host_amount');

        $pageTotal = 0;

        $data = $dataTable->getData()->data;
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $item) {
                $pageTotal = $pageTotal + $item->total;
            }
        }

        $dataTable = CodeHelper::passDataToDatatableResponse($dataTable, [
            'quantity' => CodeHelper::formatNumber($quantity),
            'pageTotal' => CodeHelper::formatPrice($pageTotal),
            'grandTotal' => CodeHelper::formatPrice($grandTotal),
            'hostAmountTotal' => CodeHelper::formatPrice($hostAmountTotal),
        ]);

        if (isset($_GET['exportType'])) {
            if ($tType != null) {
                $fileName = "Booking-Transaction-History-" . date('Y-m-d');
                if ($_GET['exportType'] === "pdf") {
                    return (new BookingTransactionHistoryExport($data))->download($fileName . '.pdf');
                } else {
                    return (new BookingTransactionHistoryExport($data))->download($fileName . '.xls');
                }
            } else {
                $fileName = "Booking-History-" . date('Y-m-d');
                if ($_GET['exportType'] === "pdf") {
                    return (new BookingHistoryExport($data))->download($fileName . '.pdf');
                } else {
                    return (new BookingHistoryExport($data))->download($fileName . '.xls');
                }
            }
        }

        return $dataTable;
    }
}
