@extends('layouts.yellow_user')

@section('content')

    <?php
    $payments = \App\Helpers\CodeHelper::getBookingPayments($booking->id);
    $reviewtoHost = \Modules\Review\Models\Review::where('reference_id', $booking->id)
        ->where('object_id', $booking->object_id)
        ->where('create_user', auth()->user()->id)
        ->first();
    ?>



    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Booking Details</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5">

            <div class="row">
                <div class="col-12">
                    @include('admin.message')
                    {{-- @if (session()->has('success'))
                        <div class="alert alert-success">
                            @if (is_array(session('success')))
                                <ul>
                                    @foreach (session('success') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ session('success') }}
                            @endif
                        </div>
                    @endif --}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 col-sm-12 table-booking-view">
                    <div class="title title-fonts sub-title">
                        <h4 class="text-uppercase mb-3">
                            <strong>Booking Details</strong>
                        </h4>
                    </div>
                    <div class="card card-default full-height card-bordered p-4 card-radious">
                        <div class="row book-table mb-2">
                            <div class="col-lg-3 col-sm-3 col-md-3">
                                <div class="date-start text-center mt-3">
                                    <div class="calendar-day">
                                        @php
                                            $date = $booking->start_date;
                                        @endphp
                                        <div class="day-name">{{ date('d', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('F', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('Y', strtotime($date)) }}</div>
                                    </div>
                                    <div class="status-btn <?= $booking->statusClass() ?>"><?= $booking->statusText() ?>
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
                                                    {{ date('F d,Y', strtotime($booking->start_date)) }}
                                                </td>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Arrival Time">
                                                        access_time
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('g:i A', strtotime($booking->start_date)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Departure Date">
                                                        flight_takeoff
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('F d,Y', strtotime($booking->end_date)) }}
                                                </td>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="Departure Time">
                                                        access_time
                                                    </span>
                                                </td>
                                                <td class="w-40">
                                                    {{ date('g:i A', strtotime($booking->end_date)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w-20">
                                                    <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                        title="No of Guests">
                                                        person
                                                    </span>
                                                </td>
                                                <td colspan="3" class="w-40">
                                                    {{ $booking->total_guests }}&nbsp;Guests
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row item-table">
                            <div class="col-sm-12">
                                <h3 class="mt-3 mb-3 text-center">Rates and Fees</h3>
                                @include('User::frontend._booking_rate_table')
                                <div class="view-btn text-center mt-4 mb-3 bottom-btn">
                                    {{-- <button class="btn btn-primary btn-lg mb-2" data-toggle="modal"
                                        data-target="#myModal">Modify</button> --}}
                                    <button class="btn btn-primary btn-lg mb-2" data-toggle="modal"
                                        data-target="#myModal">Cancel</button>
                                    <button data-toggle="modal" data-target="#contactBookModal"
                                        class="btn btn-primary btn-lg mb-2">Contact Host</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--card-->
                </div>
                <!-- Modal -->
                <div class="modal fade confirm-dialog" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="post" action="{{ route('user.bookings.get.cancel') }}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="booking_id" id="booking_id"
                                        value="{{ $booking->id }}">
                                    <h4>Are you sure want to cancel the booking?</h4>
                                    <div class="actions">
                                        <button type="submit" class="btn btn-primary reverse">Continue</button>
                                        <a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-sm-12 tab-view">
                    <div class="title title-fonts sub-title">
                        <h4 class="text-uppercase mb-3">
                            <strong>&nbsp;</strong>
                        </h4>
                    </div>
                    <div class="card taber-card card-default full-height card-bordered p-4 card-radious">
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

                                                <a href="https://www.google.com/maps/place/{{ urlencode($booking->service->address) }}"
                                                    target="_blank">
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
                                                    href="mailto:{{ $userDetails->email }}">{{ $userDetails->email }}</a>
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
                            <div class="tab-pane slide-left" id="slide4">
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

                                        {{-- <div class="row">
                                        <div class="col-lg-4">
                                            <div class="key">Space Type</div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="value">Entire Home</div>
                                        </div>
                                    </div> --}}
                                        {{-- @endif --}}
                                        {{-- @if ($row->bathroom != '') --}}
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="key">Accommodates</div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="value">{{ $booking->total_guests }}</div>
                                            </div>
                                        </div>
                                        {{-- @endif --}}
                                        @if ($row->bathroom != '')
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="key">Bathrooms</div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="value">{{ $row->bathroom }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($row->available_from != '')
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="key">Check In Time</div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="value">
                                                        {{ date('h:i A', strtotime($row->available_from)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($row->available_to != '')
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="key">Check Out Time</div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="value">
                                                        {{ date('h:i A', strtotime($row->available_to)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($row->bed != '')
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="key">Beds</div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="value">{{ $row->bed }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $terms_ids = $row->terms->pluck('term_id');
                                    
                                    $attributes_terms = \Modules\Core\Models\Terms::query()
                                        ->with(['translations', 'attribute'])
                                        ->find($terms_ids)
                                        ->pluck('id')
                                        ->toArray();
                                    $attributes = \Modules\Core\Models\Terms::where('attr_id', 4)->get();
                                    
                                @endphp
                                <br>
                                <h3>FACILITIES</h3>
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
                            </div>
                        </div>

                        <div class="card mt-0 card-default full-height-n card-bordered p-4 card-radious payment-card">
                            <h2>Payment Details</h2>
                            <?php
                            if(count($payments) > 0){
                                foreach ($payments as $payment) {
                                    ?>
                            <div class="payment-info">
                                <div class="info">
                                    <h6>Payment Method</h6>
                                    <div class="info-data">
                                        <h5 class="payment-method">{{ $payment['method'] }}</h5>
                                        <div class="information">
                                            <h6>{{ $payment['time'] }}</h6>
                                            <p>REFERENCE#: {{ $payment['ref'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="amount">
                                    <h6>Amount</h6>
                                    <h1>${{$payment['amount']}}</h1>
                                </div>
                            </div>
                            <?php
                                }
                            }else{
                                ?>
                            <p class="no-res">No Payments Found</p>
                            <?php
                            }
                            ?>

                            <div class="payment-actions d-none">
                                <a href="" class="btn btn-primary reverse">Download Invoice</a>
                                <a href="" class="btn btn-primary reverse">Issue Promo Credit</a>
                            </div>
                        </div>

                        <div class="card mb-5 mt-0 card-default full-height-n card-bordered p-4 card-radious payment-card">
                            <h2>Rating to Host</h2>
                            <?php
                            if($reviewtoHost!=null){
                                ?>
                            <h6 class="my-0"><?= $reviewtoHost->title ?></h6>
                            <p class="my-2"><?= $reviewtoHost->content ?></p>
                            <hr>
                            <p class="my-0">Added
                                On:- <b><?= \App\Helpers\CodeHelper::formatDateTime($reviewtoHost->created_at) ?></b></p>
                            <p class="my-0">Published On:- <b>
                                    <?php
                                        if($reviewtoHost->publish_date!==null){
                                        ?>
                                    <?= \App\Helpers\CodeHelper::formatDateTime($reviewtoHost->publish_date) ?>
                                    <?php } else{
                                        echo "Pending for Approval";
                                }?>
                                </b>

                            </p>
                            <?php
                            }else{
                                ?>
                            <p>No Review Found</p>
                            <a href="javascript:;" data-toggle="modal"
                                data-target="#rateHostSingleBooking<?= $booking->id ?>">
                                <button class="btn btn-primary btn-lg mb-2">Rate Host</button>
                            </a>
                            <?php
                            }
                            ?>
                        </div>


                    </div>
                    <div class="view-btn text-center bottom-btn">
                        <a target="_blank" href="{{ $row->getDetailUrl($include_param ?? true) }}">
                            <button class="btn btn-primary btn-lg  mb-2">View Full Listing Details</button>
                        </a>

                    </div>
                </div>
            </div>
        </div>
        <!--row end booking-->
    </div>
    <!-- END CONTAINER FLUID -->
    <div class="container link-icon  d-flex justify-content-center pb-5">
        <div class="row mt-3 mb-5 text-center">
            <div class="col-xs-12 col-sm-12">
                <div class="btn-icon">
                    @include('User::frontend._add_to_calendar', [
                        'event' => 'Booking at ' . $booking->service->title,
                        'from' => $booking->start_date,
                        'to' => $booking->end_date,
                    ])
                </div>
                <div class="btn-icon">
                    <a style="text-decoration: none" href="{{ route('user.booking.email', $booking->id) }}"><span
                            class="material-icons">email</span>
                        <h4 class="mt-2">Email</h4>
                    </a>
                </div>
                <div class="btn-icon">
                    <a style="text-decoration: none"
                        href="{{ route('user.booking.invoice', $booking->code) }}?print=true" target="_blank"><span
                            class="material-icons">print</span>
                        <h4 class="mt-2">Print</h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container info-div d-none">
        <div class="row mt-5 mb-5">
            <div class="col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 mb-5">
                        <div class="second-div">
                            <div class="image"
                                style="background-image:url({{ asset('user_assets/img/grow-bussiness.jpg') }})">
                            </div>
                            <h3 class="mt-3 mb-3">Grow Your Business</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                libero. </p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 mb-5">
                        <div class="second-div">
                            <div class="image" style="background-image:url({{ asset('user_assets/img/learn.jpg') }})">
                            </div>
                            <h3 class="mt-3 mb-3">Learn</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                libero. </p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 mb-5">
                        <div class="second-div">
                            <div class="image"
                                style="background-image:url({{ asset('user_assets/img/take-brake.jpg') }})"></div>
                            <h3 class="mt-3 mb-3">Take a Break</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                libero. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('User::frontend._contact_host_guest', [
        'topic' => 'Contact Host',
        'subject' => 'Booking #' . $booking->id,
    ]);

    @include('User::frontend._rate_host')

@endsection
