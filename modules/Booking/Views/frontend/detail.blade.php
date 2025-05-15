@php
    use App\Helpers\CodeHelper;
@endphp
@extends('layouts.yellow_user')
@section('head')
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.version')) }}" rel="stylesheet">
@endsection
<style type="text/css">
    .notice-success {
        padding-bottom: 40px;
        color: #6c6c6c;
    }

    .payment-block {
        padding: 15px;
        background: #fff;
        margin-bottom: 25px;
        border-radius: 25px;
        border: 1px solid rgba(222, 222, 222, 0.7);
    }

    .payment-block td {
        font-family: 'Montserrat';
    }

    .payment-block h4 {
        font-family: 'Montserrat';
    }

    @media screen and (max-width: 480px) {
        #slide4 .description .key {
            text-align: left !important;
        }
    }

    #slide4 .description .key {
        text-align: right;
    }

    #slide4 .description .value {
        text-align: left;
    }

    .link-icon .material-icons {
        font-size: 30px !important;
        background: #FFC107;
        padding: 10px !important;
        border-radius: 70px;
        color: #060606;
        border: 1px solid #060606;
    }

    .btn-default {
        color: #fff !important;
        background-color: #000 !important;
        border: 1px solid #ffc107cb !important;
        border-bottom-color: rgba(255, 193, 7, 0.796);
        border-bottom-style: solid;
        border-bottom-width: 1px;
        border-bottom: 1px solid rgba(18, 18, 18, 0.22) !important;
        font-family: 'Montserrat', sans-serif !important;
    }

    .btn-default:hover {
        background-color: #FFC107 !important;
        color: #000 !important;
    }
</style>
@section('content')
    <div class="content sm-gutter">
        <div class="container-fluid p-5">
            <div class="row">
                {{-- <div class="col-lg-12 col-sm-12 table-booking-view">
                    <div class="d-flex">
                        <div class="notice-success">
                            <p>Hi <span>{{ $booking->first_name }},</span></p><br>
                            <p>{{ __('Your Booking is ') }}<b>CONFIRMED!
                                </b>{{ __(' Please review the booking details below:') }}
                            </p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-5 col-sm-12 table-booking-view">
                    <div class="title title-fonts sub-title">
                        <h3>Booking Details</h3>
                    </div>
                    <div class="card card-default full-height card-bordered p-4 card-radious">
                        <div class="row book-table mb-4">
                            <div class="col-lg-3 col-sm-3 col-md-3">
                                <div class="date-start text-center mt-3">
                                    <div class="calendar-day">
                                        @php
                                            $date = $booking->start_date;
                                        @endphp
                                        <div class="day-name">{{ date('d', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('F', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('Y', strtotime($date)) }}</div>

                                        <div class="status-btn <?= $booking->statusClass() ?>"><?= $booking->statusText() ?>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-9 col-sm-9 col-md-9">
                                <div class="book-details pl-3">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="td-id text-uppercase">Booking
                                                    #{{ $booking->id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="thumbnail-wrapper circular inline">
                                                        <img src="{{ $booking->vendor->getAvatarUrl() }}" alt=""
                                                            data-src="{{ $booking->vendor->getAvatarUrl() }}"
                                                            data-src-retina="{{ $booking->vendor->getAvatarUrl() }}"
                                                            width="45" height="45">
                                                    </span>
                                                </td>
                                                <td colspan="3">{{ $booking->service->title }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Arrival Date">
                                                        flight_land
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('F d,Y', strtotime($booking->start_date)) }}</td>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Arrival Time">
                                                        access_time
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('g:i A', strtotime($booking->start_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Departure Date">
                                                        flight_takeoff
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('F d,Y', strtotime($booking->end_date)) }}</td>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Departure Time">
                                                        access_time
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('g:i A', strtotime($booking->end_date)) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="No of Guests">
                                                        person
                                                    </span>
                                                </td>
                                                <td colspan="3" class="w-40">
                                                    {{ $booking->total_guests == 0 ? 1 : $booking->total_guests }} Guests
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row item-table payment-block mb-4">
                            <div class="col-sm-12">
                                <h3 class="mt-3 mb-3 text-center">Rates and Fees</h3>
                                <table class="table table-borderless">
                                    <tbody>
                                        <thead>
                                            <tr>
                                                <th style="width:50%;text-align: center;font-family: 'Montserrat';">Item
                                                </th>
                                                <th style="width:35%;text-align: center;font-family: 'Montserrat';">QTY</th>
                                                <th style="width:15%;text-align: right;font-family: 'Montserrat';">Rate</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $price_item = $booking->total_before_extra_price;
                                            $d1 = new DateTime($booking->start_date); // first date
                                            $d2 = new DateTime($booking->end_date); // second date
                                            $interval = $d1->diff($d2); // get difference between two dates
                                            //echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
                                            $days = $interval->days;
                                            $stat_date = $d1->format('Y-m-d');
                                            $end_date = $d2->format('Y-m-d');
                                            $weeks = CodeHelper::numWeeks($stat_date, $end_date);
                                            $months = $interval->format('%m');
                                            
                                            if ($interval->h != '' and $interval->h < 24 and $months == 0 and $days == 0 and $weeks == 0) {
                                                $book_qty = $interval->h;
                                                $book_units = 'Hours';
                                                $book_rate = $service->hourly;
                                                $book_amount = $interval->h * $service->hourly;
                                            } elseif ($days != '' and $days >= 1 and $weeks == 0 and $months == 0) {
                                                $book_qty = $interval->days;
                                                $book_units = 'Days';
                                                $book_rate = $service->daily;
                                                $book_amount = $interval->days * $service->daily;
                                            } elseif ($weeks != '' and $weeks >= 1 and $months == 0) {
                                                $book_qty = $interval->days;
                                                $book_units = 'Weeks';
                                                $book_rate = $service->weekly;
                                                $book_amount = $weeks * $service->weekly;
                                            } elseif ($months != '' and $months >= 1) {
                                                $book_qty = $months;
                                                $book_units = 'Months';
                                                $book_rate = $service->monthly;
                                                $book_amount = $months * $service->monthly;
                                            } else {
                                                $book_qty = 1;
                                                $book_units = 'Fixed';
                                                $book_rate = $price_item;
                                                $book_amount = $price_item;
                                            }
                                        @endphp
                                        <tr class="border-bottom">
                                            <td>Space Booking Fee ({{ $book_units }})</td>
                                            <td class="text-center">
                                                {{ $book_qty }}</td>
                                            <td class="text-right">
                                                {{ format_money($booking->total / $book_qty) }}
                                            </td>
                                        </tr>
                                        @php $extra_price = $booking->getJsonMeta('extra_price') @endphp
                                        @foreach ($extra_price as $type)
                                            <tr class="border-bottom">
                                                <td>Additional Item</td>
                                                <td class="text-center">{{ $type['name_' . $lang_local] ?? $type['name'] }}
                                                </td>
                                                <td class="text-right">{{ format_money($type['total'] ?? 0) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="fs-12"></td>
                                            <td class="fs-14 font-weight-bold text-uppercase text-right">Subtotal
                                            </td>
                                            <td class="text-right">{{ format_money($booking->total / 1.13) }}</td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td class="fs-14 font-weight-bold text-uppercase text-right">Taxes
                                                (13%)
                                            </td>
                                            <td class="text-right">{{ format_money(($booking->total / 1.13) * 0.13) }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="fs-16 font-weight-bold text-uppercase  text-right"
                                                style="font-family: 'Montserrat';">
                                                Grand Total
                                            </td>
                                            <td class="fs-18 text-right"
                                                style="font-weight: 900;font-family: 'Montserrat';">
                                                {{ format_money($booking->total) }}
                                            </td>
                                        </tr>
                                        @if ($booking->status != 'draft')
                                            @if ($booking->pay_now < $booking->total)
                                                <tr>
                                                    <td></td>
                                                    <td class="fs-16 font-weight-bold text-uppercase text-right">
                                                        Paid Amount
                                                    </td>
                                                    <td class="fs-18  text-right"
                                                        style="font-size: 14px;font-weight: 550;color: #007bff;">
                                                        {{ format_money($booking->pay_now) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td class="fs-16 font-weight-bold text-uppercase text-right">
                                                        Remain
                                                    </td>
                                                    <td class="fs-18 text-right"
                                                        style="font-size: 16px;font-weight: 550;border: 2px solid rgb(177, 173, 173);margin:5px;">
                                                        {{ format_money($booking->total - $booking->pay_now) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 mt-4 text-center">
                                <button class="btn btn-primary">MODIFY/CANCEL</button>
                                <button class="btn btn-default">CONTACT HOST</button>
                            </div>
                        </div>
                        @if (isset($booking->payment))
                            @php
                                $payment_log = $booking->payment->logs;
                                $payment_details = json_decode($payment_log);
                                
                            @endphp
                            <div class="payment-block title-fonts">
                                <h3>PAYMENT DETAILS</h3>
                                <table class="table table-borderless text-center">
                                    <tbody>
                                        <tr>
                                            <td class="text-right"><b>PAYMENT STATUS:</b></td>
                                            <td class="text-left">PAID</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>METHODS:</b></td>
                                            <td class="text-left" style="text-transform: uppercase;">
                                                {{ isset($payment_details->pay_type) ? $payment_details->pay_type : 'Paypal' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>PAY DATE:</b></td>
                                            <td class="text-left" style="text-transform: uppercase;">
                                                {{ date('M, d Y', strtotime($payment_details->transaction_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>REFERENCE:</b></td>
                                            <td class="text-left">{{ $booking->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>AMOUNT:</b></td>
                                            <td class="text-left">
                                                ${{ number_format((float) $booking->pay_now, 2, '.', '') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="container link-icon  d-flex justify-content-center">
                                    <div class="row mt-3 mb-3 text-center">
                                        <div class="col-xs-12 col-sm-12">
                                            <div class="btn-icon">
                                                <a style="text-decoration: none" href="#"><span
                                                        class="material-icons">event</span>
                                                    <h4 class="mt-2">Download</h4>
                                                </a>
                                            </div>
                                            <div class="btn-icon">
                                                <a style="text-decoration: none" href="#"><span
                                                        class="material-icons">email</span>
                                                    <h4 class="mt-2">Email</h4>
                                                </a>
                                            </div>
                                            <div class="btn-icon">
                                                <a style="text-decoration: none"
                                                    href="{{ route('user.booking.invoice', $booking->code) }}"><span
                                                        class="material-icons">print</span>
                                                    <h4 class="mt-2">Print</h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-lg-7 col-sm-12 tab-view">
                    <div class="title title-fonts sub-title">
                        <h3>Summary</h3>
                    </div>
                    <div class="card-summary-tabs-guest full-height card-bordered p-4 card-radious">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex"
                            data-init-reponsive-tabs="dropdownfx">
                            <li class="nav-item">
                                <a href="#" class="active" data-toggle="tab" data-target="#slide1"><span>About the
                                        Space</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide2" class=""><span>House
                                        Rules</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide3"
                                    class=""><span>FAQs</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" data-target="#slide4"
                                    class=""><span>Amenities</span></a>
                            </li>
                        </ul>
                        @php
                            if ($service = $booking->service) {
                                $translation = $service->translateOrOrigin(app()->getLocale());
                            }
                        @endphp
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane slide-left active" id="slide1">
                                <div class="row column-seperation">
                                    <div class="col-lg-12">
                                        <h3>
                                            <span class="semi-bold">{!! $booking->service->title !!}</span>
                                        </h3>
                                        <p>{!! $booking->service->content !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide2">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>{!! $booking->service->title !!}</h3>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="image mb-3">
                                            <img src="{{ $booking->service->image_url }}">
                                        </div>
                                        <div class="icon-row">
                                            <div class="icon-div"><span class="material-icons">location_on</span>
                                            </div>
                                            <div class="icon-details">

                                                <a href="https://goo.gl/maps/Ap6g8vrDGg3U4szL8" target="_blank">
                                                    {{ $booking->service->address }}
                                                </a>
                                            </div>
                                        </div>
                                        @php
                                            $userDetails = $booking->vendor;
                                            
                                        @endphp
                                        <div class="icon-row">
                                            <div class="icon-div"><span class="material-icons">phone</span>
                                            </div>
                                            <div class="icon-details"><a
                                                    href="tel:{{ $userDetails->phone }}">{{ $userDetails->phone }}</a>
                                            </div>
                                        </div>
                                        <div class="icon-row">
                                            <div class="icon-div"><span class="material-icons">location_on</span>
                                            </div>
                                            <div class="icon-details"><a
                                                    href="mailto:reservations@myoffice.ca">{{ $userDetails->email }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8  pr-5 pl-5">
                                        <p>{!! $booking->service->content !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3>FAQs</h3>
                                        <div class="card-group horizontal" id="accordion" role="tablist"
                                            aria-multiselectable="true">
                                            @if ($translation->faqs)
                                                @php $i = 1; @endphp
                                                @foreach ($translation->faqs as $item)
                                                    <div class="card card-default m-b-0"
                                                        style="border: 1px solid rgba(18, 18, 18, 0.1)">
                                                        <div class="card-header " role="tab"
                                                            id="heading{{ $booking->convertNumberToWord($i) }}">
                                                            <div class="card-title">
                                                                <a style="text-decoration: none" data-toggle="collapse"
                                                                    class="{{ $i != 1 ? 'collapsed' : '' }}"
                                                                    data-parent="#accordion"
                                                                    href="#collapse{{ $booking->convertNumberToWord($i) }}"
                                                                    aria-expanded="{{ $i == 1 }}"
                                                                    aria-controls="collapse{{ $booking->convertNumberToWord($i) }}">
                                                                    {{ $item['title'] }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div style="visibility: visible"
                                                            id="collapse{{ $booking->convertNumberToWord($i) }}"
                                                            class="collapse {{ $i == 1 ? 'show' : '' }}" role="tabcard"
                                                            aria-labelledby="heading{{ $booking->convertNumberToWord($i) }}">
                                                            <div class="card-body">
                                                                {{ $item['content'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php $i++ @endphp
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane slide-left" id="slide4" style="padding-left: 20px;">
                                <br>
                                <h3>GENERAL INFORMATION</h3>
                                @php
                                    $row = Modules\Space\Models\Space::where('id', $booking->service->id)
                                        ->with(['location', 'translations', 'hasWishList'])
                                        ->first();
                                    
                                @endphp
                                @if (!empty($row->location->name))
                                    @php
                                        $location = $row->location->translateOrOrigin(app()->getLocale());
                                    @endphp
                                @endif
                                <div class="g-rules">
                                    <div class="description">
                                        @php
                                            $terms_ids = $row->terms->pluck('term_id');
                                            $attributes_terms = \Modules\Core\Models\Terms::query()
                                                ->with(['translations', 'attribute'])
                                                ->find($terms_ids)
                                                ->pluck('id')
                                                ->toArray();
                                            $attributes_cat = \Modules\Core\Models\Terms::where('attr_id', 3)->get();
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="key"><b>Category:</b></div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="value">
                                                    @foreach ($attributes_cat as $attribute_cat)
                                                        @if (empty($attribute_cat['parent']['hide_in_single']))
                                                            @php
                                                                $terms = $attribute_cat['child'];
                                                            @endphp
                                                            @if (in_array($attribute_cat->id, $attributes_terms))
                                                                <span class="aminidis">{{ $attribute_cat->name }} </span>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="key"><b>Capacity:</b></div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="value">
                                                    {{ $booking->max_guests ? $booking->max_guests . ' Peoples' : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="key"><b>Hours of Operation:</b></div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="value">{{ date('h:i A', strtotime($row->available_from)) }}
                                                    to {{ date('h:i A', strtotime($row->available_to)) }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="key"><b>#of Desks:</b></div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="value">{{ $row->desk }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="key"><b>#of Seats:</b></div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="value">{{ $row->seat }}</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @php
                                    $attributes = \Modules\Core\Models\Terms::where('attr_id', 4)->get();
                                @endphp
                                <br>
                                <h3>AMENITIES</h3>
                                <ul class="aminitlistingul mgnT20">
                                    @if (!empty($terms_ids) and !empty($attributes))
                                        @foreach ($attributes as $attribute)
                                            @if (empty($attribute['parent']['hide_in_single']))
                                                @php $terms = $attribute['child'] @endphp
                                                <li
                                                    class="detaillistingli {{ in_array($attribute->id, $attributes_terms) ? '' : 'not' }} fulwidthm mgnB10">
                                                    <i class="aminti_icon {{ $attribute->icon }}"></i>
                                                    <span class="aminidis">{{ $attribute->name }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>

                                <div class="container link-icon  d-flex justify-content-center">
                                    <a target="_blank" href="{{ $row->getDetailUrl($include_param ?? true) }}">
                                        <button class="btn btn-primary btn-lg mb-2">View Full Listing Details</button>
                                    </a>
                                </div>
                            </div>
                        </div>



                    </div>


                </div>

            </div>
        </div>
    </div>

    @include('User::frontend._contact_host_guest', [
        'topic' => 'Contact Host',
        'subject' => 'Booking #' . $booking->id,
    ])

@endsection

@section('footer')
    <script>
        window.location.replace("{{route('user.single.booking.detail', $booking->id)}}");
    </script>
@endsection
