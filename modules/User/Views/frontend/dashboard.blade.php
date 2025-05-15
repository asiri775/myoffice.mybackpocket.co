@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <?php
    
    use Modules\Core\Models\Attributes;
    
    $space_categories = Attributes::where('service', 'space')->get();
    ?>
    <style>
        th {
            white-space: nowrap !important;
        }
    </style>
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Welcome to MyOffice</li>
                </ol>
            </div>
        </div>

        <div class="container-fluid p-5">

            @include('admin.message')

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card card-default card-bordered card-normal card-radious">
                        <div class="card-header card-header-actions">
                            <div class="card-title">
                                <h4 class="pt-1 text-uppercase">
                                    <strong>
                                        {{ __('Earnings') }}
                                    </strong>
                                </h4>
                            </div>
                            <div class="card-actions">
                                <select class="form-control" id="earningStatsDuration">
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <ul class="card-stats-items">
                                <li>
                                    <div class="card-stats-item">
                                        <span>Total</span>
                                        <h4 id="totalAmount">-</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="card-stats-item">
                                        <span>Bookings</span>
                                        <h4 id="totalBookings">-</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="card-stats-item">
                                        <span>Day Average</span>
                                        <h4 id="dayAverage">-</h4>
                                    </div>
                                </li>
                                <li>
                                    <div class="card-stats-item">
                                        <span>Year to Date</span>
                                        <h4 id="yearToDate">-</h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <canvas id="earning_chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card card-default card-bordered card-title-bg card-normal card-radious">
                                <div class="card-header ">
                                    <div class="card-title">
                                        <h4 class="text-uppercase">
                                            <strong>
                                                {{ __('Shortcuts') }}
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="card-body">
                                    <ul class="shortcut-menus">
                                        <li>
                                            <a href="{{ route('vendor.clients') }}">
                                                <span><i class="fa fa-users"></i></span>
                                                <span>Client</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="{{ route('user.inbox') }}">
                                                <span><i class="fa fa-envelope"></i></span>
                                                <span>Inbox</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.calendar') }}">
                                                <span><i class="fa fa-calendar"></i></span>
                                                <span>Calendar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('space.vendor.index') }}">
                                                <span><i class="fa fa-building"></i></span>
                                                <span>Spaces</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.bookings.details') }}?type=all">
                                                <span><i class="fa fa-clock-o"></i></span>
                                                <span>Bookings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('event.vendor.index') }}">
                                                <span><i class="fa fa-calendar-check-o"></i></span>
                                                <span>Events</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('vendor.reports') }}">
                                                <span><i class="fa fa-bar-chart"></i></span>
                                                <span>Reports</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.wallet.withdraw') }}">
                                                <span><i class="fa fa-paper-plane-o"></i></span>
                                                <span>Withdraw</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.wallet') }}">
                                                <span><i class="fa fa-money"></i></span>
                                                <span>Wallet</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <form class="normal-form mt-4 center" method="get"
                                        action="{{ route('user.bookings.details') }}?type=all">
                                        <div class="form-group">
                                            <input class="form-control" name="q" placeholder="Search for Booking #" />
                                        </div>
                                        <button class="btn btn-primary btn-sm" type="submit">Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card card-default card-bordered card-title-bg card-normal card-radious">
                                <div class="card-header ">
                                    <div class="card-title">
                                        <h4 class="text-uppercase">
                                            <strong>
                                                {{ __('Performance') }}
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="analytics-data">
                                        <h6>Customer Satisfaction</h6>
                                        <ul>
                                            <li>
                                                <p>Excellent</p>
                                                <div>
                                                    <span id="5StarRatingTotal">-</span>
                                                    <span id="5StarRatingTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Very Good</p>
                                                <div>
                                                    <span id="4StarRatingTotal">-</span>
                                                    <span id="4StarRatingTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Good</p>
                                                <div>
                                                    <span id="3StarRatingTotal">-</span>
                                                    <span id="3StarRatingTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Need Improvements</p>
                                                <div>
                                                    <span id="2StarRatingTotal">-</span>
                                                    <span id="2StarRatingTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Poor</p>
                                                <div>
                                                    <span id="1StarRatingTotal">-</span>
                                                    <span id="1StarRatingTotalPer">-</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr class="analytics-divider" />
                                    <div class="analytics-data">
                                        <h6>Analytics</h6>
                                        <ul>
                                            <li>
                                                <p>Views</p>
                                                <div>
                                                    <span id="viewsTotal">-</span>
                                                    <span id="viewsTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Clicks</p>
                                                <div>
                                                    <span id="clicksTotal">-</span>
                                                    <span id="clicksTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Bookings</p>
                                                <div>
                                                    <span id="bookingsTotal">-</span>
                                                    <span id="bookingsTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Cancellations</p>
                                                <div>
                                                    <span id="cancellationsTotal">-</span>
                                                    <span id="cancellationsTotalPer">-</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Repeat</p>
                                                <div>
                                                    <span id="repeatTotal">-</span>
                                                    <span id="repeatTotalPer">-</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="normal-form mt-4 center">
                                        <a href="{{ route('vendor.reports') }}" class="btn btn-primary btn-sm">View
                                            Reports</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card card-default card-bordered card-padding-zero card-radious">
                <div class="card-header card-header-actions">
                    <div class="card-title">
                        <ul class="card-in-rows-options bookingFilters">
                            <li><a href="javascript:;" data-value="" class="active">All</a></li>
                            <li><a href="javascript:;" data-value="booked" class="">Booked</a></li>
                            <li><a href="javascript:;" data-value="checked-in" class="">Checked In</a></li>
                            <li><a href="javascript:;" data-value="completed" class="">Completed</a></li>
                        </ul>
                    </div>
                    <div class="card-actions">
                        <div class="table-top-filter-length-main mr-3">
                            <span>Show</span>
                            <select class="table-top-filter-length">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">All</option>
                            </select>
                            <span>Records</span>
                        </div>
                        <a href="{{ route('user.bookings.details') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-filters">
                        <div class="row data-search">
                            <input type="hidden" name="booking_status" id="booking_status" class="filterField">
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text" class="form-control filterField" name="search"
                                        id="filterSearch" placeholder="Enter ID or City">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>FROM</label>
                                    <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                        <input type="text" class="form-control from filterField" name="from">
                                        <div class="input-group-append ">
                                            <span class="input-group-text"><i class="pg-icon">calendar</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>TO</label>
                                    <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                        <input type="text" class="form-control to filterField" name="to">
                                        <div class="input-group-append ">
                                            <span class="input-group-text"><i class="pg-icon">calendar</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>QUICK DATE</label>
                                    <select class="full-width form-control filterField" id="date_options"
                                        name="date_option">
                                        <option value="">Pick an option</option>
                                        <option value_from="{{ date('m/d/Y', strtotime('yesterday')) }}"
                                            value_to="{{ date('m/d/Y', strtotime('yesterday')) }}" value="yesterday">
                                            Yesterday
                                        </option>
                                        <option value_from="{{ date('m/d/Y') }}" value_to="{{ date('m/d/Y') }}"
                                            value="today">
                                            Today
                                        </option>
                                        <option value_from="{{ date('m/d/Y', strtotime('monday this week')) }}"
                                            value_to="{{ date('m/d/Y', strtotime('friday this week')) }}"
                                            value="this_weekdays">
                                            This Weekdays
                                        </option>
                                        <option value_from="{{ date('m/d/Y', strtotime('monday this week')) }}"
                                            value_to="{{ date('m/d/Y', strtotime('sunday this week')) }}"
                                            value="this_whole_week">
                                            This Whole Week
                                        </option>
                                        <option value_from="{{ date('m/d/Y', strtotime('first day of this month')) }}"
                                            value_to="{{ date('m/d/Y', strtotime('last day of this month')) }}"
                                            value="this_month">
                                            This Month
                                        </option>
                                        <option
                                            value_from="{{ date('m/d/Y', strtotime('first day of January ' . date('Y'))) }}"
                                            value_to="{{ date('m/d/Y', strtotime('last day of December ' . date('Y'))) }}"
                                            value="this_year">
                                            This Year
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                    id="doSearch" style="padding: 0px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                        id="ActivateAdvanceSerach" style="padding: 0px;">
                                        Advance Search
                                    </button>
                                    <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                        id="HideActivateAdvanceSerach" style="display: none; padding: 0px;">Hide Advance
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="searchFilters m-b-10" id="AdvanceFilters" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="full-width form-control filterField" id="booking_category"
                                            name="category">
                                            <option value="" selected>Select Category</option>
                                            @foreach ($space_categories[0]->terms as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="booking_status" class="control-label">BOOK STATUS</label>
                                        <select id="booking_status" class="full-width form-control filterField"
                                            name="booking_status">
                                            <option value="">Select Status</option>
                                            @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                                <option value="{{ $k }}">{{ $txt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control filterField" id="amountFilter"
                                            name="amount" placeholder="Enter Amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-no-page-options">
                        <div class="table-responsive">
                            <table class="table demo-table-search table-responsive-block data-table" id="tableHistory">
                                <thead>
                                    <tr>
                                        <th style="max-width:30px !important;"></th>
                                        <th style="max-width:50px !important;">ID#</th>
                                        <th style="width:15%;">Guest</th>
                                        <th style="width:15%;">Listing Name</th>
                                        <th style="width:15%;text-align: center;">Address</th>
                                        <th style="width:15%;text-align: center;">Categories</th>
                                        <th style="width:10%;">Start Date</th>
                                        <th style="width:10%;">End Date</th>
                                        <th style="width:10%;">Amount</th>
                                        <th style="width:25%;">Book Status</th>
                                        <th style="width:10%;" style="display: none;">Transaction Status</th>
                                        <th style="width:10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- END CONTAINER FLUID -->
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('libs/chart_js/Chart.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(function($) {

            $(".bravo-user-render-chart").each(function() {
                let ctx = $(this)[0].getContext('2d');
                window.myMixedChartForVendor = new Chart(ctx, {
                    type: 'bar', //line - bar
                    data: earning_chart_data,
                    options: {
                        min: 0,
                        responsive: true,
                        legend: {
                            display: true
                        },
                        scales: {
                            xAxes: [{
                                stacked: true,
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Timeline'
                                }
                            }],
                            yAxes: [{
                                stacked: true,
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'xyx'
                                },
                                ticks: {
                                    beginAtZero: true,
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label ||
                                        '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += tooltipItem.yLabel +
                                        " ({{ setting_item('currency_main') }})";
                                    return label;
                                }
                            }
                        }
                    }
                });
            });

            $(".bravo-user-chart form select").change(function() {
                $(this).closest("form").submit();
            });

            $(document).on("click", ".bookingFilters li a", function() {
                var obj = $(this);
                $(".bookingFilters li a").removeClass("active");
                obj.addClass("active");
                $("#booking_status").val(obj.attr("data-value"));
                $("#doSearch").click();
            });

            var start = moment().startOf('week');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                "alwaysShowCalendars": true,
                "opens": "left",
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1, 'month').endOf('month')
                    ],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'This Week': [moment().startOf('week'), end]
                }
            }, cb).on('apply.daterangepicker', function(ev, picker) {
                $.ajax({
                    url: '{{ url('user/reloadChart') }}',
                    data: {
                        chart: 'earning',
                        from: picker.startDate.format('YYYY-MM-DD'),
                        to: picker.endDate.format('YYYY-MM-DD'),
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(res) {
                        if (res.status) {
                            window.myMixedChartForVendor.data = res.data;
                            window.myMixedChartForVendor.update();
                        }
                    }
                })
            });
            cb(start, end);
        });
    </script>

    <script>
        function loadEarningStats(earningStatsDuration) {
            $.get("{{ route('vendor.earningStats') }}", {
                durationType: earningStatsDuration
            }, function(r) {

                $("#totalAmount").html(r?.totalAmount);
                $("#totalBookings").html(r?.totalBookings);
                $("#dayAverage").html(r?.dayAverage);
                $("#yearToDate").html(r?.yearToDate);

                var earning_chart_data = {
                    "labels": r?.chartData?.labels,
                    "datasets": [{
                        "label": "Earnings",
                        "data": r?.chartData?.amounts,
                        "backgroundColor": "#FFC106",
                        "stack": "group-total"
                    }]
                };

                var ctx = document.getElementById('earning_chart').getContext('2d');
                window.myMixedChart = new Chart(ctx, {
                    type: 'bar',
                    data: earning_chart_data,
                    options: {
                        responsive: true,
                        title: {
                            display: false,
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += tooltipItem.yLabel + " (usd)";
                                    return label;
                                }
                            }
                        }
                    }
                });

            });
        }
        $(document).on("change", "#earningStatsDuration", function() {
            loadEarningStats($(this).val());
        });

        function loadRatingAndAnalytics() {
            $.get("{{ route('user.reports.customSatisfcation') }}", {
                type: 'all'
            }, function(res) {
                const data = res['data'];
                $("#5StarRatingTotal").html(data[5]['totalRatings']);
                $("#5StarRatingTotalPer").html(data[5]['percentage']+"%");
                $("#4StarRatingTotal").html(data[4]['totalRatings']);
                $("#4StarRatingTotalPer").html(data[4]['percentage']+"%");
                $("#3StarRatingTotal").html(data[3]['totalRatings']);
                $("#3StarRatingTotalPer").html(data[3]['percentage']+"%");
                $("#2StarRatingTotal").html(data[2]['totalRatings']);
                $("#2StarRatingTotalPer").html(data[2]['percentage']+"%");
                $("#1StarRatingTotal").html(data[1]['totalRatings']);
                $("#1StarRatingTotalPer").html(data[1]['percentage']+"%");
            });
            $.get("{{ route('user.reports.bookingAnalytics') }}", {
                type: 'all'
            }, function(response) {
                $("#viewsTotal").html(response['views']['total']);
                $("#viewsTotalPer").html(response['views']['percentage']+"%");
                $("#clicksTotal").html(response['clicks']['total']);
                $("#clicksTotalPer").html(response['clicks']['percentage']+"%");
                $("#bookingsTotal").html(response['bookings']['total']);
                $("#bookingsTotalPer").html(response['bookings']['percentage']+"%");
                $("#cancellationsTotal").html(response['cancellations']['total']);
                $("#cancellationsTotalPer").html(response['cancellations']['percentage']+"%");
                $("#repeatTotal").html(response['repeat']['total']);
                $("#repeatTotalPer").html(response['repeat']['percentage']+"%");
            });
        }

        setTimeout((e) => {
            loadEarningStats("week");
            loadRatingAndAnalytics();
        }, 500);
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function(e) {
            var table = $('#tableHistory');
            $.fn.dataTable.ext.errMode = 'none';

            let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

            var datatable = table.DataTable({
                ...dataTableOptions,
                "ajax": {
                    "url": "{{ route('user.bookings.datatable') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    'data': function(data) {
                        data['search_query'] = {};
                        $(".table-filters").find(".filterField").each(function() {
                            let obj = $(this);
                            data['search_query'][obj.attr("name")] = obj.val();
                        });
                    }
                },
                "columns": [{
                        data: 'checkboxes',
                        name: 'checkboxes',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'guest',
                        name: 'guest'
                    },
                    {
                        data: 'title',
                        name: 'title',

                    },
                    {
                        data: 'address',
                        name: 'address',

                    },
                    {
                        data: 'categories',
                        name: 'categories',

                    },
                    {
                        data: 'start_date',
                        name: 'start_date'

                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'booking_status',
                        name: 'booking_status'
                    },
                    {
                        data: 'transaction_status',
                        name: 'transaction_status',
                        visible: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $(document).on("change", 'select.filterField', function() {
                datatable.draw();
            });

            $(document).on("keypress", 'input.filterField', function(e) {
                if (e.which == 13) {
                    datatable.draw();
                }
            });

            $(document).on("change", '.table-top-filter-length', function() {
                var obj = $(this);
                var tableLength = obj.val();
                obj.closest(".card").find('select[name="tableHistory_length"]').val(
                    tableLength).trigger("change");
            });

            $('#date_options').change(function() {
                var from_date = $('#date_options option:selected').attr('value_from');
                var to_date = $('#date_options option:selected').attr('value_to');
                $('.from').val(from_date);
                $('.to').val(to_date);
                datatable.draw();
            });

            $(document).on("click", "#doSearch", function() {
                datatable.draw();
            });

        });
    </script>
@endsection
