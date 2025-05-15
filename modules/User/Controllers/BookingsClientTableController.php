<?php

namespace Modules\User\Controllers;

use App\BaseModel;
use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;
use Modules\Core\Models\Terms;
use Modules\Location\Models\Location;
use Modules\Space\Models\Space;
use Yajra\DataTables\Facades\DataTables;
use Modules\Space\Models\SpaceTerm;

class BookingsClientTableController extends Controller
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

        $userIds = Booking::orderBy('id', 'DESC')->where('vendor_id', '=', $user_id);
        $searchQuery = request()->search_query;

        $dateChoosed = false;
        $from = $to = null;

        if (array_key_exists('from', $searchQuery) && $searchQuery['from']) {
            $from = $this->dateConvertion($searchQuery['from']);
            if (!isset($from)) {
                $from = Carbon::now()->startOfYear();
            } else {
                $from = $from . " 00:00:00";
            }
            $userIds = $userIds->where('start_date', '>=', $from);
            $dateChoosed = true;
        }

        if (array_key_exists('to', $searchQuery) && $searchQuery['to']) {
            $to = $this->dateConvertion($searchQuery['to']);
            if (!isset($to)) {
                $to = Carbon::now()->startOfYear();
            } else {
                $to = $to . " 23:59:59";
            }
            $userIds = $userIds->where('end_date', '<=', $to);
            $dateChoosed = true;
        }

        if (!$dateChoosed) {
            if (array_key_exists('date_option', $searchQuery)) {
                switch ($searchQuery['date_option']) {
                    case 'yesterday':
                        $userIds = $userIds->whereDate('start_date', '=', Carbon::now()->subDay());
                        break;
                    case 'today':
                        $userIds = $userIds->whereDate('start_date', '=', Carbon::now());
                        break;
                    case 'this_weekdays':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek()->subDays(2);
                        $userIds = $userIds->whereBetween('start_date', [$start, $end]);
                        break;
                    case 'this_whole_week':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek();
                        $userIds = $userIds->whereBetween('start_date', [$start, $end]);
                        break;
                    case 'this_month':
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMonth();
                        $userIds = $userIds->whereBetween('start_date', [$start, $end]);
                        break;
                    case 'this_year':
                        $start = Carbon::now()->startOfYear();
                        $end = Carbon::now()->endOfYear();
                        $userIds = $userIds->whereBetween('start_date', [$start, $end]);
                        break;
                    default:
                        break;
                }
            }
        }

        // CodeHelper::debugQuery($userIds);

        $userIds = $userIds->groupBy('customer_id')->pluck('customer_id')->toArray();
        if ($userIds == null) {
            $userIds = [-1];
        }

        $clients = User::whereIn('id', $userIds)->select([
            '*',
            DB::raw('CONCAT(first_name, " ", last_name) AS full_name')
        ]);

        if (array_key_exists('search', $searchQuery) && $searchQuery['search'] != '') {
            $searchQueryData = trim($searchQuery['search']);
            if ($searchQueryData != null) {
                $clients = $clients->where(function ($query) use ($searchQueryData) {
                    $query->where('email', 'like', '%' . $searchQueryData . '%');
                    $query->orWhere('full_name', 'like', '%' . $searchQueryData . '%');
                });
            }
        }

        // CodeHelper::debugQuery($clients);

        $dataTable = DataTables::of($clients)
            ->addColumn('checkboxes', function ($client) {
                $select = '<input type="checkbox" name="checkbox[]" value="' . $client->id . '">';
                return $select;
            })
            ->addColumn('name', function ($client) {
                $title = $client->name;
                return '<a target="_blank" href="' . route('user.profile.publicProfile', $client->id) . '">' .
                    clean($title) . '
                                </a>';
            })
            ->addColumn('last_booking', function ($client) use ($user_id) {
                $lastBooking = Booking::orderBy('id', 'DESC')->where('vendor_id', '=', $user_id)
                    ->where('customer_id', $client->id)->orderBy('id', 'DESC')->first();
                if ($lastBooking != null) {
                    return '<a target="_blank" href="' . route('user.single.booking.detail', $lastBooking->id) . '">#' . $lastBooking->id . '</a>';
                }
                return '-';
            })
            ->addColumn('total_bookings', function ($client) use ($user_id) {
                $totalBookings = Booking::orderBy('id', 'DESC')->where('vendor_id', '=', $user_id)
                    ->where('customer_id', $client->id)->count();
                if ($totalBookings == null) {
                    $totalBookings = 0;
                }
                return $totalBookings;
            })
            ->addColumn('total_revenue', function ($client) use ($user_id) {
                $totalRevenue = Booking::orderBy('id', 'DESC')->where('vendor_id', '=', $user_id)
                    ->where('customer_id', $client->id)->sum('host_amount');
                if ($totalRevenue == null) {
                    $totalRevenue = 0;
                }
                return CodeHelper::printAmount($totalRevenue);
            })
            ->addColumn('actions', function ($client) {
                $buttons = [
                    // 'edit' => ['url' => 'javascript:;', 'class' => 'modifySingleBooking', 'extra' => ['data-value' => $booking->id, 'data-details' => json_encode($booking)]],                  
                ];
                return BaseModel::getActionButtons($buttons);
            })
            ->rawColumns(['name', 'last_booking'])
            ->make(true);

        $dataTable = CodeHelper::passDataToDatatableResponse($dataTable, []);
        return $dataTable;
    }
}
