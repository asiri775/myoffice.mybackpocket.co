@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    @php $user = \Modules\User\Models\User::where('id', \Illuminate\Support\Facades\Auth::id())->first() @endphp

    <link id="style" href="{{ asset('report/bootstrap.min.css') }}" rel="stylesheet">
    <link id="style" href="{{ asset('report/styles.min.css') }}" rel="stylesheet">

    <style>
        th {
            text-align: center !important;
        }

        .dataTables_length {
            display: none;
        }

        .card-btn-filter-options button.active {
            background: var(--primary-color);
            color: #fff;
        }

        .flatpickr-input-group button {
            background: #000 !important;
        }

        .flatpickr-input-group input {
            padding: 8px 12px;
            border: 1px solid #eee;
            border-radius: 5px 0 0 5px;
            min-width: 225px;
        }

        .card-s-he {
            height: 100%;
            margin-bottom: 0;
        }

        table.dataTable>thead .sorting_asc:before,
        table.dataTable>thead .sorting_asc:after,
        table.dataTable>thead .sorting_desc:before,
        table.dataTable>thead .sorting_desc:after {
            display: none;
        }

        #topClientsTable th,
        #saleBySpaceTable th,
        #reviewsTable th {
            text-align: center !important;
        }

        table.table-bordered.dataTable tbody th,
        table.table-bordered.dataTable tbody td {
            border-bottom: 1px solid rgba(222, 222, 222, 0.7);
        }

        #customSatisfcationChart path:hover {
            fill: #FFC106 !important;
            color: #000 !important;
            filter: unset !important;
        }

        .center-data-t {
            text-align: center;
        }

        .vacancy-text-col {
            color: #FB0202 !important;
        }
    </style>

    <div class="content reports-layout sm-gutter">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-3 col-xl-12">
                    <div class="card custom-card overflow-hidden profile-card">
                        <div class="card-body">
                            <div class="row gap-3 gap-sm-0">
                                <div class="col-sm-8 col-12">
                                    <div class="">
                                        <h4 class="fw-semibold mb-2">Hi
                                            {{ $user->first_name }},</h4>
                                        <p style="color: #000 !important;" class="mb-4 fs-14 op-7"> Welcome Back !</p>
                                        <p>Here's What's happening with your business today</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-auto my-auto">
                                    <div class="featured-nft" style="background-image: url('{{ $user->getAvatarUrl() }}')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-with-loader loading custom-card table-booking-view">
                        <div class="card-header justify-content-between flex-wrap border-bottom m-l-2">
                            <div class="card-title"> SUMMARY </div>
                            <div class="dropdown-wrapper">
                                <input type="hidden" id="summaryType" value="all">
                                <div class="btn-group m-r-5"> <button
                                        class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-expanded="false">View All</button>
                                    <ul class="dropdown-menu">
                                        <li><a data-value="all" class="dropdown-item" href="javascript:void(0);">ALL</a>
                                        </li>
                                        <li><a data-value="week" class="dropdown-item"
                                            href="javascript:void(0);">This Week</a></li>
                                        <li><a data-value="1m" class="dropdown-item" href="javascript:void(0);">1M</a></li>
                                        <li><a data-value="6m" class="dropdown-item" href="javascript:void(0);">6M</a></li>
                                        <li><a data-value="1y" class="dropdown-item" href="javascript:void(0);">1Y</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-loader-area">
                            <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>
                            <div class="card-content-area">
                                <div class="row item-table">
                                    <div class="col-sm-12 p-3 m-t-15 m-b-10">
                                        <div class="card">
                                            <table class="table text-nowrap table-borderless">
                                                <thead>
                                                    <tr class="no-style">
                                                        <th style="width:50%">Earnings</th>
                                                        <th style="width:35%;text-align: center;"></th>
                                                        <th style="width:15%;text-align: right;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Gross Revenue</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr class="border-bottom">
                                                        <td>Less: Site Fees</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bold">Net Earnings</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right bold">-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card">
                                            <table class="table text-nowrap table-borderless">
                                                <!--h3 class="tr-title p-l-10">Bookings</h3-->
                                                <thead>
                                                    <tr class="no-style">
                                                        <th style="width:50%">Bookings</th>
                                                        <th style="width:35%;text-align: center;">QTY</th>
                                                        <th style="width:15%;text-align: right;">Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Completed</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Checked In</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Checked Out</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cancelled</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card">
                                            <table class="table text-nowrap table-borderless">
                                                <thead>
                                                    <tr class="no-style">
                                                        <th style="width:50%"></th>
                                                        <th style="width:35%;text-align: center;">Days</th>
                                                        <th style="width:15%;text-align: right;">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Vacancy</td>
                                                        <td class="text-center">-</td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card">
                                            <table class="table text-nowrap table-borderless">
                                                <!--h3 class="tr-title p-l-10">Clients</h3-->
                                                <thead>
                                                    <tr class="no-style">
                                                        <th style="width:50%">Clients</th>
                                                        <th style="width:35%;text-align: center;">QTY</th>
                                                        <th style="width:15%;text-align: right;">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>New Clients</td>
                                                        <td class="text-center">-</td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Repeat Clients</td>
                                                        <td class="text-center">-</td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-12 col-xl-12">
                            <div class="card loading custom-card">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title">Promo Code</div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="promoType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                            <div class="dropdown card-drop"> <a aria-label="anchor"
                                                    href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                    data-toggle="dropdown" aria-expanded="false"> <i
                                                        class="fa fa-ellipsis-v "></i> </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                            Report</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                            XLS</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                            PDF</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-loader-area">
                                        <div class="card-content-area">
                                            <div class="card-body card-inline-0">
                                                <div class="table-responsive">
                                                    <table id="promoCodeTable"
                                                        class="table text-nowrap table-hover border table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" style="text-align: center !important;">
                                                                    CODE</th>
                                                                <th scope="col" style="text-align: center !important;">
                                                                    AMOUNT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--End Row-->
                </div>
                <div class="col-xxl-9 col-xl-12">
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12 snap-div">
                            <div class="card card-with-loader loading  custom-card overflow-hidden">
                                <div class="card-header  justify-content-between border-bottom">
                                    <div class="card-title"> Snap Shots </div>
                                    <div class="form-group">
                                        <div class="input-group flatpickr-input-group">
                                            <input type="text" class="flatpickr-input active" id="daterangeinput"
                                                placeholder="<?= date('M d, Y', strtotime('-1 year')) ?> to <?= date('M d, Y') ?>"
                                                readonly="readonly">
                                            <button class="input-group-text input-group-append text-muted bg-primary"
                                                id="daterange"> <i class="fa fa-calendar text-white"></i> </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--div class="row">

                                                                                                                                              </div-->
                                    <div class="row">
                                        <div class="col-xxl-3 col-xl-12">
                                            <div class="card card-s-he overflow-hidden">
                                                <div class="card-body">
                                                    <div class="card-header  justify-content-between no-border no-padding">
                                                        <div class="card-title"> Earnings </div>
                                                        <div class="dropdown"> <a aria-label="anchor"
                                                                href="javascript:void(0);"
                                                                class="btn btn-icon btn-sm btn-light"
                                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                                    class="fa fa-ellipsis-v "></i> </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">View Full Report</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download XLS</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download PDF</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <a id="earningLink"
                                                            href="{{ route('vendor.transactions', ['tType' => 'earnings']) }}"
                                                            target="_blank">
                                                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold"><span
                                                                    class="counter-value overviewVal count"
                                                                    data-target="28.05" id="overviewEarningVal">-</span>
                                                            </h2>
                                                            <h4 class="mb-0 text-muted text-truncate"><span
                                                                    class="badge bg-light text-success mb-0"> <i
                                                                        class="ri-arrow-up-line align-middle"></i>
                                                                    <span class="overviewVal"
                                                                        id="overviewEarningPercentage">-</span>
                                                                </span></h4>
                                                        </a>
                                                        <div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span
                                                                    class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                                    <img src="https://myoffice.mybackpocket.co/public/backend-new/assets/img/tileimage_earnings.png"
                                                                        alt="Earnings">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-12">
                                            <div class="card card-s-he overflow-hidden">
                                                <div class="card-body">
                                                    <div class="card-header  justify-content-between no-border no-padding">
                                                        <div class="card-title"> Bookings </div>
                                                        <div class="dropdown"> <a aria-label="anchor"
                                                                href="javascript:void(0);"
                                                                class="btn btn-icon btn-sm btn-light"
                                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                                    class="fa fa-ellipsis-v "></i> </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">View Full Report</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download XLS</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download PDF</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <a id="bookingLink"
                                                            href="{{ route('vendor.transactions', ['tType' => 'bookings']) }}"
                                                            target="_blank" class="center-data-t">
                                                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold count">
                                                                <span class="counter-value overviewVal"
                                                                    data-target="28.05" id="overviewBookingsVal">-</span>
                                                            </h2>
                                                            <h4 class="mb-0 text-muted text-truncate text-danger"><span
                                                                    class="badge bg-light text-success mb-0 text-danger">
                                                                    <i
                                                                        class="ri-arrow-down-line align-middle text-danger"></i>
                                                                    <span class="overviewVal"
                                                                        id="overviewBookingsPercentage">-</span>
                                                                </span></h4>
                                                        </a>
                                                        <div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span
                                                                    class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                                    <img src="https://myoffice.mybackpocket.co/public/backend-new/assets/img/tileimage_bookings.png"
                                                                        alt="Booking">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-12">
                                            <div class="card card-s-he overflow-hidden">
                                                <div class="card-body">
                                                    <div class="card-header  justify-content-between no-border no-padding">
                                                        <div class="card-title"> Clients </div>
                                                        <div class="dropdown"> <a aria-label="anchor"
                                                                href="javascript:void(0);"
                                                                class="btn btn-icon btn-sm btn-light"
                                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                                    class="fa fa-ellipsis-v "></i> </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">View Full Report</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download XLS</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download PDF</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="w-50 d-flex">
                                                            <div>
                                                                <a id="topClientsNewLink"
                                                                    href="{{ route('vendor.topClients', ['type' => 'new']) }}"
                                                                    target="_blank"
                                                                    class="p-2 border-end border-inline-end-dashed"
                                                                    style="margin-left: -12px;">
                                                                    <div
                                                                        class="text-muted fs-16 text-center mb-1 semi-bold">
                                                                        New </div>
                                                                    <div class="text-center align-items-center">
                                                                        <div class="fs-26 fw-semibold count overviewVal"
                                                                            id="overviewNewClientsVal">-</div>
                                                                        <div
                                                                            class="text-success fw-semibold badge bg-light text-success mb-0 fs-14">
                                                                            <i class="ri-arrow-up-line align-middle"></i>
                                                                            <span class="overviewVal"
                                                                                id="overviewNewClientsPercentage">-</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <a id="topClientsRepeatLink"
                                                                    href="{{ route('vendor.topClients', ['type' => 'repeat']) }}"
                                                                    target="_blank" class="p-2">
                                                                    <div
                                                                        class="text-muted fs-16 text-center mb-1 semi-bold">
                                                                        Repeat </div>
                                                                    <div class="text-center align-items-center">
                                                                        <div class="fs-26 fw-semibold text-center overviewVal count"
                                                                            id="overviewRepeatClientsVal">
                                                                            -
                                                                        </div>
                                                                        <div
                                                                            class="text-success text-center fw-semibold badge bg-light text-success mb-0 fs-14">
                                                                            <i class="ri-arrow-up-line align-middle"></i>
                                                                            <span class="overviewVal"
                                                                                id="overviewRepeatClientsPercentage">-</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="w-100 text-right">
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span
                                                                    class="avatar-title bg-info-subtle rounded-circle m-l-30">
                                                                    <img src="https://myoffice.mybackpocket.co/public/backend-new/assets/img/tileimage_clients.png"
                                                                        alt="Clients">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-12">
                                            <div class="card card-s-he overflow-hidden">
                                                <div class="card-body">
                                                    <div
                                                        class="card-header  justify-content-between no-border no-padding no-bo">
                                                        <div class="card-title"> Vacancy </div>
                                                        <div class="dropdown"> <a aria-label="anchor"
                                                                href="javascript:void(0);"
                                                                class="btn btn-icon btn-sm btn-light"
                                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                                    class="fa fa-ellipsis-v "></i> </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">View Full Report</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download XLS</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0);">Download PDF</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <h2 class="mt-4 ff-secondary cfs-22 fw-semibold text-danger">
                                                                <span
                                                                    class="counter-value vacancy-text-col overviewVal count"
                                                                    data-target="28.05" id="overviewVacancyVal">-</span>
                                                            </h2>
                                                            <h4 class="mb-0 text-muted text-truncate text-danger"><span
                                                                    class="badge bg-light text-success mb-0 text-danger">
                                                                    <i
                                                                        class="ri-arrow-down-line align-middle text-danger"></i>
                                                                    <span class="overviewVal  vacancy-text-col"
                                                                        id="overviewVacancyPercentage">-</span></span></h4>
                                                        </div>
                                                        <div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span
                                                                    class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                                    <img src="https://myoffice.mybackpocket.co/public/backend-new/assets/img/tileimage_vacancy.png"
                                                                        alt="Vacancy">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--end col-3-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-8 col-xl-12">
                            <div class="card custom-card card-with-loader loading custom-card ">
                                <div class="card-header justify-content-between flex-wrap">
                                    <div class="card-title"> Revenue Analytics </div>
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <div class="card-btn-filter-options-wrapper">
                                                <input type="hidden" id="revenueAnalyticsType" value="all">
                                                <div class="btn-group card-btn-filter-options justify-content-end"
                                                    role="group" aria-label="Basic example">
                                                    <button data-value="all" type="button"
                                                        class="btn btn-primary-light btn-sm btn-wave active waves-effect waves-light">ALL</button>
                                                        <button data-value="week" type="button"
                                                        class="btn btn-primary-light btn-sm btn-wave waves-effect waves-light">This Week</button>
                                                    <button data-value="1m" type="button"
                                                        class="btn btn-primary-light btn-sm btn-wave waves-effect waves-light">1M</button>
                                                    <button data-value="6m" type="button"
                                                        class="btn btn-primary-light btn-sm btn-wave waves-effect waves-light">6M</button>
                                                    <button data-value="1y" type="button"
                                                        class="btn btn-primary-light btn-sm btn-wave waves-effect waves-light">1Y</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="d-flex flex-wrap justify-content-sm-evenly flex-fill border-top border-bottom">
                                        <div class="p-2">
                                            <div class="p-2">
                                                <div class="text-muted fs-16 text-center mb-1 semi-bold"
                                                    id="bookingRevenue">-</div>
                                                <div class="text-center align-items-center">
                                                    <div class="fs-12">Bookings</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="p-2">
                                                <div class="text-muted fs-16 text-center mb-1 semi-bold"
                                                    id="earningRevenue">-</div>
                                                <div class="text-center align-items-center">
                                                    <div class="fs-12 text-center">Earnings</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="p-2">
                                                <div class="text-muted fs-16 text-center mb-1 semi-bold"
                                                    id="vacancyRevenue">-</div>
                                                <div class="text-center align-items-center">
                                                    <div class="fs-12 text-center">Vacancy</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>
                                    <div class="card-content-area">
                                        <div class="card-body py-3 px-0">
                                            <div class="chart-wrapper">
                                                <div id="revenueAnalyticsChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end Card-->
                        </div>
                        <div class="col-xxl-4 col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between flex-wrap border-bottom">
                                    <div class="card-title"> Alerts</div>
                                    <div class="d-flex">
                                        <div class="btn-group m-r-5"> <button
                                                class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-expanded="false"> View All </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">ALL</a></li>
                                                <li><a data-value="week" class="dropdown-item"
                                                    href="javascript:void(0);">This Week</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">1M</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">6M</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">1Y</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="news-bar">
                                        <ul class="list-unstyled mb-0 crm-recent-activity">
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-primary-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span class="fw-semibold">Update of
                                                            calendar events
                                                            &amp;</span><span><a href="javascript:void(0);"
                                                                class="text-primary fw-semibold">
                                                                Added new events in next week.</a></span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">4:45PM</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-secondary-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>New theme for <span
                                                                class="fw-semibold">Spruko
                                                                Website</span> completed</span> <span
                                                            class="d-block fs-12 text-muted">Lorem ipsum,
                                                            dolor sit amet.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">3 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-success-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>Created a <span
                                                                class="text-success fw-semibold">New Task</span> today
                                                            <span
                                                                class="avatar avatar-xs bg-purple-transparent avatar-rounded ms-1"><i
                                                                    class="ri-add-fill text-purple fs-12"></i></span></span>
                                                    </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">22 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-pink-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>New member <span
                                                                class="badge bg-pink-transparent">@andreas
                                                                gurrero</span>
                                                            added today to AI
                                                            Summit.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">Today</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-warning-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>32 New people joined
                                                            summit.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">22 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-info-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>Neon Tarly added <span
                                                                class="text-info fw-semibold">Robert Bright</span> to
                                                            AI
                                                            summit project.</span>
                                                    </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">12 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-dark-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>Replied to new support
                                                            request
                                                            <i
                                                                class="ri-checkbox-circle-line text-success fs-16 align-middle"></i></span>
                                                    </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">4 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-purple-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>Completed documentation of
                                                            <a href="javascript:void(0);"
                                                                class="text-purple text-decoration-underline fw-semibold">AI
                                                                Summit.</a></span>
                                                    </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">4 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-secondary-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>New theme for <span
                                                                class="fw-semibold">Spruko
                                                                Website</span> completed</span> <span
                                                            class="d-block fs-12 text-muted">Lorem ipsum,
                                                            dolor sit amet.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">3 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-success-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>Created a <span
                                                                class="text-success fw-semibold">New Task</span> today
                                                            <span
                                                                class="avatar avatar-xs bg-purple-transparent avatar-rounded ms-1"><i
                                                                    class="ri-add-fill text-purple fs-12"></i></span></span>
                                                    </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">22 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-pink-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>New member <span
                                                                class="badge bg-pink-transparent">@andreas
                                                                gurrero</span>
                                                            added today to AI
                                                            Summit.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">Today</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="crm-recent-activity-content">
                                                <div class="d-flex align-items-top">
                                                    <div class="me-3"> <span
                                                            class="avatar avatar-xs bg-warning-transparent avatar-rounded">
                                                            <i class="bi bi-circle-fill fs-8"></i> </span> </div>
                                                    <div class="crm-timeline-content"> <span>32 New people joined
                                                            summit.</span> </div>
                                                    <div class="flex-fill text-end"> <span
                                                            class="d-block text-muted fs-11 op-7">22 hrs</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title">Top Clients </div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="topClientsType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="dropdown card-drop"> <a aria-label="anchor"
                                                href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                    class="fa fa-ellipsis-v "></i> </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                        Report</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        XLS</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        PDF</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="card-content-area">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="topClientsTable"
                                                    class="table text-nowrap table-hover border table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">CLIENT NAME</th>
                                                            <th scope="col" class="text-center">BOOKINGS</th>
                                                            <th scope="col" class="text-center">REVENUE</th>
                                                            <th scope="col" class="text-center">LAST BOOKING</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><!--End Row-->
                    <div class="row">
                        <div class="col-xxl-6 col-xl-12 mb-3">
                            <div class="card card-with-loader loading  custom-card h-100 pb-0 mb-0">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title"> Customer Satisfaction </div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="customSatisfcationType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="dropdown card-drop"> <a aria-label="anchor"
                                                href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                    class="fa fa-ellipsis-v "></i> </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                        Report</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        XLS</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        PDF</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>
                                    <div class="no-data-box">
                                        <div class="no-data-box-content">
                                            <p>No Reviews during this period</p>
                                        </div>
                                    </div>
                                    <div class="card-content-area">
                                        <div class="card-body py-3 px-0">
                                            <div class="chart-wrapper">
                                                <div id="customSatisfcationChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-12 mb-3">
                            <div class="card card-with-loader loading  custom-card h-100 pb-0 mb-0">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title">Booking Analytics </div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="bookingAnalyticsType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="dropdown card-drop"> <a aria-label="anchor"
                                                href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                    class="fa fa-ellipsis-v "></i> </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                        Report</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        XLS</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        PDF</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>
                                    <div class="card-content-area">
                                        <div class="card-body py-4 px-0">
                                            <div class="chart-wrapper">
                                                <div id="bookingAnalyticsChart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--End Row-->
                    <div class="row">
                        <div class="col-xxl-6 col-xl-12 mb-3">
                            <div class="card card-with-loader loading  custom-card h-100 pb-0 mb-0">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title">Sales By Space </div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="saleBySpaceType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="dropdown card-drop"> <a aria-label="anchor"
                                                href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                    class="fa fa-ellipsis-v "></i> </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                        Report</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        XLS</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        PDF</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="card-content-area">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="saleBySpaceTable"
                                                    class="table text-nowrap table-hover border table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">Name</th>
                                                            <th scope="col">Bookings</th>
                                                            <th scope="col" class="text-right">EARNINGS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-12 mb-3">
                            <div class="card card-with-loader loading  custom-card h-100 pb-0 mb-0">
                                <div class="card-header justify-content-between border-bottom">
                                    <div class="card-title">Latest Reviews </div>
                                    <div class="d-flex">
                                        <div class="dropdown-wrapper">
                                            <input type="hidden" id="latestReviewType" value="all">
                                            <div class="btn-group m-r-5"> <button
                                                    class="btn btn-primary toogleTypeBox btn-sm dropdown-toggle"
                                                    type="button" data-toggle="dropdown" aria-expanded="false">View
                                                    All</button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-value="all" class="dropdown-item"
                                                            href="javascript:void(0);">ALL</a>
                                                    </li>
                                                    <li><a data-value="week" class="dropdown-item"
                                                        href="javascript:void(0);">This Week</a></li>
                                                    <li><a data-value="1m" class="dropdown-item"
                                                            href="javascript:void(0);">1M</a></li>
                                                    <li><a data-value="6m" class="dropdown-item"
                                                            href="javascript:void(0);">6M</a></li>
                                                    <li><a data-value="1y" class="dropdown-item"
                                                            href="javascript:void(0);">1Y</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="dropdown card-drop"> <a aria-label="anchor"
                                                href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                                                data-toggle="dropdown" aria-expanded="false"> <i
                                                    class="fa fa-ellipsis-v "></i> </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);">View Full
                                                        Report</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        XLS</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);">Download
                                                        PDF</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-loader-area">
                                    <div class="card-content-area">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="reviewsTable"
                                                    class="table text-nowrap table-hover border table-bordered">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th scope="col">ID</th>
                                                            <th scope="col">Booking</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Rating</th>
                                                            <th scope="col">Review By</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-6-->
            </div>
        </div>



    </div>
    <!-- END PAGE CONTENT -->
    <!-- START COPYRIGHT -->
    <!-- START CONTAINER FLUID -->
    <!-- START CONTAINER FLUID -->
    <div class="container-fluid footer">
        <div class="copyright sm-text-center">
            <p class="small-text text-black m-0">
                Copyright © <?=date('Y')?> <b>My Office Inc.</b>All Rights Reserved.
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END COPYRIGHT -->
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!--START QUICKVIEW -->
    <div id="quickview" class="quickview-wrapper" data-pages="quickview">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="">
                <a href="#quickview-notes" data-target="#quickview-notes" data-toggle="tab" role="tab">Notes</a>
            </li>
            <li>
                <a href="#quickview-alerts" data-target="#quickview-alerts" data-toggle="tab" role="tab">Alerts</a>
            </li>
        </ul>
        <a class="btn-icon-link invert quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"><i
                class="pg-icon">close</i></a>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- BEGIN Notes !-->
            <div class="tab-pane no-padding" id="quickview-notes">
                <div class="view-port clearfix quickview-notes" id="note-views">
                    <!-- BEGIN Note List !-->
                    <div class="view list" id="quick-note-list">
                        <div class="toolbar clearfix">
                            <ul class="pull-right ">
                                <li>
                                    <a href="#" class="delete-note-link"><i class="pg-icon">trash_alt</i></a>
                                </li>
                                <li>
                                    <a href="#" class="new-note-link" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">add</i></a>
                                </li>
                            </ul>
                            <button aria-label="" class="btn-remove-notes btn btn-xs btn-block hide"><i
                                    class="pg-icon">close</i>Delete</button>
                        </div>
                        <ul>
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="1" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox1" type="checkbox" value="1">
                                        <label for="qncheckbox1"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                        do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push">
                                        <i class="pg-icon">chevron_right</i>
                                    </a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="2" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox2" type="checkbox" value="1">
                                        <label for="qncheckbox2"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                        do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="2" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox3" type="checkbox" value="1">
                                        <label for="qncheckbox3"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                        do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="3" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox4" type="checkbox" value="1">
                                        <label for="qncheckbox4"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                        do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="4" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox5" type="checkbox" value="1">
                                        <label for="qncheckbox5"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                        do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                        </ul>
                    </div>
                    <!-- END Note List !-->
                    <div class="view note" id="quick-note">
                        <div>
                            <ul class="toolbar">
                                <li><a href="#" class="close-note-link"><i class="pg-icon">chevron_left</i></a>
                                </li>
                                <li><a href="#" data-action="Bold" class="fs-12"><i
                                            class="pg-icon">format_bold</i></a>
                                </li>
                                <li><a href="#" data-action="Italic" class="fs-12"><i
                                            class="pg-icon">format_italics</i></a>
                                </li>
                                <li><a href="#" class="fs-12"><i class="pg-icon">link</i></a>
                                </li>
                            </ul>
                            <div class="body">
                                <div>
                                    <div class="top">
                                        <span>21st april 2020 2:13am</span>
                                    </div>
                                    <div class="content">
                                        <div class="quick-note-editor full-width full-height js-input"
                                            contenteditable="true"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Notes !-->
            <!-- BEGIN Alerts !-->
            <div class="tab-pane no-padding" id="quickview-alerts">
                <div class="view-port clearfix" id="alerts">
                    <!-- BEGIN Alerts View !-->
                    <div class="view bg-white">
                        <!-- BEGIN View Header !-->
                        <div class="navbar navbar-default navbar-sm">
                            <div class="navbar-inner">
                                <!-- BEGIN Header Controler !-->
                                <a href="javascript:;" class="action p-l-10 link text-color" data-navigate="view"
                                    data-view-port="#chat" data-view-animation="push-parrallax">
                                    <i class="pg-icon">more_horizontal</i>
                                </a>
                                <!-- END Header Controler !-->
                                <div class="view-heading">
                                    Notications
                                </div>
                                <!-- BEGIN Header Controler !-->
                                <a href="#" class="action p-r-10 pull-right link text-color">
                                    <i class="pg-icon">search</i>
                                </a>
                                <!-- END Header Controler !-->
                            </div>
                        </div>
                        <!-- END View Header !-->
                        <!-- BEGIN Alert List !-->
                        <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
                            <!-- BEGIN List Group !-->
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Calendar
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="align-items-center" data-navigate="view"
                                            data-view-port="#chat" data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-warning fs-10"><i class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="p-l-10 overflow-ellipsis fs-12">
                                                <span class="text-color">David Nester Birthday</span>
                                            </p>
                                            <p class="p-r-10 ml-auto fs-12 text-right">
                                                <span class="text-warning">Today <br></span>
                                                <span class="text-color">All Day</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                        <!-- BEGIN List Group Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="#" class="align-items-center" data-navigate="view"
                                            data-view-port="#chat" data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-warning fs-10"><i class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="p-l-10 overflow-ellipsis fs-12">
                                                <span class="text-color">Meeting at 2:30</span>
                                            </p>
                                            <p class="p-r-10 ml-auto fs-12 text-right">
                                                <span class="text-warning">Today</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                            <!-- END List Group !-->
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Social
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-complete fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">Jame Smith commented on your
                                                    status<br></span>
                                                <span class="text-color">“Perfection Simplified - Company Revox"</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-complete fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">Jame Smith commented on your
                                                    status<br></span>
                                                <span class="text-color">“Perfection Simplified - Company Revox"</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Sever Status
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="#" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-danger fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">12:13AM GTM, 10230, ID:WR174s<br></span>
                                                <span class="text-color">Server Load Exceeted. Take action</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                        </div>
                        <!-- END Alert List !-->
                    </div>
                    <!-- EEND Alerts View !-->
                </div>
            </div>
        @endsection

        @section('footer')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

            <script>
                function abbreviateNumber(number) {
                    if (number < 1000) {
                        return number.toString();
                    } else if (number < 1000000) {
                        return (number / 1000).toFixed(2) + 'k';
                    } else {
                        return (number / 1000000).toFixed(2) + 'M';
                    }
                }

                $(document).ready(function(e) {

                    let revenueAnalyticsChart = null;
                    let customSatisfcationChart = null;
                    let bookingAnalyticsChart = null;

                    $.fn.dataTable.ext.errMode = 'none';
                    let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

                    function loadOverview() {
                        const cardElem = $("#summaryType").closest(".card-with-loader");
                        cardElem.addClass("loading");
                        $.get("{{ route('user.reports.summary') }}", {
                            type: $("#summaryType").val()
                        }, function(r) {
                            cardElem.find(".card-content-area").html(r);
                            cardElem.removeClass("loading");
                        });
                    }

                    $(document).on("change", "#summaryType", function() {
                        loadOverview();
                    });

                    const promoCodeTable = $('#promoCodeTable').DataTable({
                        ...dataTableOptions,
                        ajax: {
                            "url": "{{ route('user.reports.promoCodes') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            "method": "POST",
                            'data': function(data) {
                                data['type'] = $("#promoType").val();
                            }
                        },
                        "columns": [{
                                data: 'coupon_code',
                                name: 'coupon_code',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'couponAmount',
                                name: 'couponAmount',
                                orderable: false,
                                searchable: false,
                            },
                        ],
                    });

                    function loadPromoCodes() {
                        promoCodeTable.draw();
                    }

                    $(document).on("change", "#promoType", function() {
                        loadPromoCodes();
                    });

                    const saleBySpaceTable = $('#saleBySpaceTable').DataTable({
                        ...dataTableOptions,
                        ajax: {
                            "url": "{{ route('user.reports.saleBySpace') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            "method": "POST",
                            'data': function(data) {
                                data['type'] = $("#saleBySpaceType").val();
                            }
                        },
                        "columns": [{
                                data: 'title',
                                name: 'title',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'bookings',
                                name: 'bookings',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'earnings',
                                name: 'earnings',
                                orderable: false,
                                searchable: false,
                            },
                        ],
                    });

                    function loadSalesBySpaces() {
                        saleBySpaceTable.draw();
                    }

                    $(document).on("change", "#saleBySpaceType", function() {
                        loadSalesBySpaces();
                    });

                    const reviewsTable = $('#reviewsTable').DataTable({
                        ...dataTableOptions,
                        ajax: {
                            "url": "{{ route('user.reports.reviews') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            "method": "POST",
                            'data': function(data) {
                                data['type'] = $("#latestReviewType").val();
                            }
                        },
                        "columns": [{
                                data: 'idLink',
                                name: 'id',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'booking',
                                name: 'booking',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'date',
                                name: 'date',
                                orderable: false,
                                searchable: false,
                            }, {
                                data: 'rating',
                                name: 'rating',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'reviewByUser',
                                name: 'reviewByUser',
                                orderable: false,
                                searchable: false,
                            },
                        ],
                    });

                    function loadReviews() {
                        reviewsTable.draw();
                    }

                    $(document).on("change", "#latestReviewType", function() {
                        loadReviews();
                    });

                    const topClientsTable = $('#topClientsTable').DataTable({
                        ...dataTableOptions,
                        ajax: {
                            "url": "{{ route('user.reports.topClients') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            "method": "POST",
                            'data': function(data) {
                                data['type'] = $("#topClientsType").val();
                            }
                        },
                        "columns": [{
                                data: 'clientName',
                                name: 'clientName',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'bookings',
                                name: 'bookings',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'revenue',
                                name: 'revenue',
                                orderable: false,
                                searchable: false,
                            }, {
                                data: 'lastBooking',
                                name: 'lastBooking',
                                orderable: false,
                                searchable: false,
                            },
                        ],
                    });

                    function loadTopClients() {
                        topClientsTable.draw();
                    }

                    $(document).on("change", "#topClientsType", function() {
                        loadTopClients();
                    });

                    function loadRevenueAnalyticsChartChart() {
                        const cardElement = $("#revenueAnalyticsChart").closest(".card-with-loader");
                        const chartWapper = cardElement.closest(".chart-wrapper");
                        chartWapper.html('<div id="revenueAnalyticsChart"></div>');
                        cardElement.addClass("loading");
                        $.get("{{ route('user.reports.revenueAnalytics') }}", {
                            type: $("#revenueAnalyticsType").val()
                        }, function(r) {
                            cardElement.removeClass("loading");
                            $("#bookingRevenue").html(r.totalBookings);
                            $("#earningRevenue").html(r.earnings);
                            $("#vacancyRevenue").html(r.vacancy);
                            const options = {
                                series: [{
                                    name: 'Bookings',
                                    data: r.chart.bookings
                                }, {
                                    name: 'Earnings',
                                    data: r.chart.earnings
                                }, {
                                    name: 'Vacancy',
                                    data: r.chart.vacancy
                                }],
                                chart: {
                                    height: 350,
                                    type: 'area'
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'smooth'
                                },
                                xaxis: {
                                    type: r.chart.type,
                                    categories: r.chart.categories
                                },
                                tooltip: {
                                    x: {
                                        format: 'MM'
                                    },
                                },
                            };
                            if (revenueAnalyticsChart != null) {
                                revenueAnalyticsChart.destroy();
                            }
                            revenueAnalyticsChart = new ApexCharts(document.querySelector("#revenueAnalyticsChart"),
                                options);
                            revenueAnalyticsChart.render();
                        });
                    }

                    $(document).on("change", "#revenueAnalyticsType", function() {
                        loadRevenueAnalyticsChartChart();
                    });

                    function loadCustomSatisfcationChart() {
                        const cardElement = $("#customSatisfcationChart").closest(".card-with-loader");
                        const chartWapper = cardElement.closest(".chart-wrapper");
                        chartWapper.html('<div id="customSatisfcationChart"></div>');
                        cardElement.addClass("loading");
                        $.get("{{ route('user.reports.customSatisfcation') }}", {
                            type: $("#customSatisfcationType").val()
                        }, function(rres) {
                            const totalRatings = rres['totalRatings'];
                            const r = rres['data'];
                            cardElement.removeClass("loading");
                            let options = {
                                series: [
                                    r[5]['totalRatings'],
                                    r[4]['totalRatings'],
                                    r[3]['totalRatings'],
                                    r[2]['totalRatings'],
                                    r[1]['totalRatings']
                                ],
                                labels: ['Excellent', 'Very Good', 'Good', 'Need Improvement', 'Poor'],
                                chart: {
                                    type: 'donut',
                                    width: 590,
                                },
                                responsive: [{
                                    breakpoint: 320,
                                    options: {
                                        chart: {
                                            width: 200
                                        },
                                        legend: {
                                            show: false
                                        }
                                    }
                                }]
                            };
                            if (totalRatings <= 0) {
                                cardElement.addClass("no-data");
                            } else {
                                cardElement.removeClass("no-data");
                            }
                            if (customSatisfcationChart != null) {
                                customSatisfcationChart.destroy();
                            }
                            customSatisfcationChart = new ApexCharts(document.querySelector(
                                    "#customSatisfcationChart"),
                                options);
                            customSatisfcationChart.render();
                        });
                    }

                    $(document).on("change", "#customSatisfcationType", function() {
                        loadCustomSatisfcationChart();
                    });

                    function loadBookingAnalyticsChart() {
                        const cardElement = $("#bookingAnalyticsChart").closest(".card-with-loader");
                        const chartWapper = cardElement.closest(".chart-wrapper");
                        chartWapper.html('<div id="bookingAnalyticsChart"></div>');
                        cardElement.addClass("loading");
                        $.get("{{ route('user.reports.bookingAnalytics') }}", {
                            type: $("#bookingAnalyticsType").val()
                        }, function(r) {
                            cardElement.removeClass("loading");
                            const options = {
                                series: [{
                                    data: [
                                        r['views']['total'],
                                        r['clicks']['total'],
                                        r['bookings']['total'],
                                        r['cancellations']['total'],
                                        r['repeat']['total'],
                                    ]
                                }],
                                chart: {
                                    type: 'bar',
                                    height: 325
                                },
                                plotOptions: {
                                    bar: {
                                        barHeight: '100%',
                                        distributed: true,
                                        horizontal: true,
                                        dataLabels: {
                                            position: 'top'
                                        },
                                    }
                                },
                                colors: ['#003f5c', '#58508d', '#bc5090', '#ff6361', '#ffa600'],
                                dataLabels: {
                                    enabled: true,
                                    style: {
                                        colors: ['#000']
                                    },
                                    formatter: function(val, opt) {
                                        return abbreviateNumber(val);
                                        // return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                                    },
                                    offsetX: 25,
                                    textAnchor: "start",
                                    dropShadow: {
                                        enabled: false
                                    }
                                },
                                stroke: {
                                    width: 1,
                                    colors: ['#fff']
                                },
                                xaxis: {
                                    categories: ['Views', 'Clicks', 'Bookings', 'Cancellations', 'Repeat'],
                                },
                                yaxis: {
                                    labels: {
                                        show: false
                                    }
                                },
                                tooltip: {
                                    theme: 'dark',
                                    x: {
                                        show: false
                                    },
                                    y: {
                                        title: {
                                            formatter: function() {
                                                return ''
                                            }
                                        }
                                    }
                                }
                            };
                            if (bookingAnalyticsChart != null) {
                                bookingAnalyticsChart.destroy();
                            }
                            bookingAnalyticsChart = new ApexCharts(document.querySelector("#bookingAnalyticsChart"),
                                options);
                            bookingAnalyticsChart.render();
                        });
                    }

                    $(document).on("change", "#bookingAnalyticsType", function() {
                        loadBookingAnalyticsChart();
                    });

                    function loadMainOverview(startDate, toDate) {
                        $(".overviewVal").html("-");
                        $.get("{{ route('user.reports.overview') }}", {
                            from: startDate,
                            to: toDate
                        }, function(r) {
                            console.log(r);
                            $("#overviewNewClientsVal").html(r.newClients);
                            $("#overviewNewClientsPercentage").html(r.newClientsPer);
                            $("#overviewRepeatClientsVal").html(r.repeatClients);
                            $("#overviewRepeatClientsPercentage").html(r.repeatClientsPer);
                            $("#overviewVacancyVal").html(r.vacancy);
                            $("#overviewVacancyPercentage").html(r.vacancyPercentage);
                            $("#overviewBookingsVal").html(r.bookings);
                            $("#overviewBookingsPercentage").html(r.bookingsPercentage);
                            $("#overviewEarningVal").html(r.earnings);
                            $("#overviewEarningPercentage").html(r.earningsPercentage);

                            $("#earningLink").attr("href", r.earningLink);
                            $("#bookingLink").attr("href", r.bookingLink);
                            $("#topClientsNewLink").attr("href", r.topClientsNewLink);
                            $("#topClientsRepeatLink").attr("href", r.topClientsRepeatLink);

                        });
                    }

                    setTimeout((e) => {
                        loadOverview();
                        loadRevenueAnalyticsChartChart();
                        loadCustomSatisfcationChart();
                        loadBookingAnalyticsChart();
                        loadMainOverview(
                            moment().subtract(1, 'years').format("YYYY-MM-DD"),
                            moment().format("YYYY-MM-DD"),
                        );
                    }, 500);

                    $(document).on("click", ".dropdown-wrapper li a", function() {
                        const val = $(this).attr("data-value");
                        $(this).closest(".dropdown-wrapper").find("input").val(val).trigger("change");
                        $(this).closest(".dropdown-wrapper").find("button").html($(this).html());
                    });

                    $(document).on("click", ".card-btn-filter-options button", function() {
                        const val = $(this).attr("data-value");
                        const mainWrapper = $(this).closest(".card-btn-filter-options-wrapper");
                        mainWrapper.find("input").val(val).trigger("change");
                        mainWrapper.find("button").removeClass("active");
                        $(this).addClass("active");
                    });

                    setTimeout((e) => {
                        flatpickr("#daterange", {
                            mode: "range",
                            dateFormat: "M d, Y",
                            defaultDate: [
                                moment().subtract(1, 'years').format("MMM DD, YYYY"),
                                moment().format("MMM DD, YYYY"),
                            ],
                            onChange: function(selectedDates, dateStr, instance) {
                                if (selectedDates.length > 1) {
                                    $("#daterangeinput").val(
                                        `${moment(new Date(selectedDates[0])).format("MMM DD, YYYY")} to ${moment(new Date(selectedDates[1])).format("MMM DD, YYYY")}`
                                    );
                                    loadMainOverview(
                                        moment(new Date(selectedDates[0])).format("YYYY-MM-DD"),
                                        moment(new Date(selectedDates[1])).format("YYYY-MM-DD"),
                                    );
                                }
                            },
                        });
                    }, 200);

                    $(document).on("click", "#daterangeinput", function() {
                        $("#daterange").click();
                    });

                });
            </script>
        @endsection
