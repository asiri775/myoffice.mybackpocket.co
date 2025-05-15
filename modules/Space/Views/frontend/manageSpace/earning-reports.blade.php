@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <?php
    
    use Modules\Core\Models\Attributes;
    
    $space_categories = Attributes::where('service', 'space')->get();
    ?>
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Earning By Space</li>
                </ol>
            </div>
        </div>

        <div class="container-fluid px-5 pt-4 pb-5">

            @include('admin.message')

            <div class="row">
                <div class="col-12">
                    <div class="card card-no-card card-bordered p-2 card-radious pt-0">
                        <div class="card-header-actions pt-0 pb-2">
                            <div class="card-title mb-0">
                                <h4 class="text-uppercase">
                                    <strong>
                                        <?= $row->title ?>
                                    </strong>
                                </h4>
                            </div>
                            <div class="card-actions">
                                <form id="spaceEarningForm" action="<?= route('space.vendor.spaceEarningReports') ?>"
                                    method="get">
                                    <select name="id" id="spaceEarningFormField" class="form-control" required>
                                        <option value="">Select Space</option>
                                        <?php
                                            foreach($userSpaces as $userSpacesItem){
                                                ?>
                                        <option <?php if ($userSpacesItem->id == $id) {
                                            echo 'selected';
                                        } ?> value="<?= $userSpacesItem->id ?>">
                                            <?= $userSpacesItem->title ?></option>
                                        <?php
                                            }
                                            ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-12">
                    <div class="card card-default card-bordered card-title-bg card-normal card-radious">
                        <div class="card-header ">
                            <div class="card-title">
                                <h4 class="text-uppercase">
                                    <strong>
                                        {{ __('Summary') }}
                                    </strong>
                                </h4>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="card-stats-items card-stats-items-hor">
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
                    </div>
                </div>
                <div class="col-md-9 col-12">
                    <div class="row">
                        <div class="col-md-5 col-12">
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
                                <div class="card-body py-2 analytics-data-sm-main">
                                    <div class="analytics-data analytics-data-sm">
                                        <h6>Customer Satisfaction</h6>
                                        <ul>
                                            <li>
                                                <p>Excellent</p>
                                                <div>
                                                    <span>{{ $stats['ratings'][5]['totalRatings'] }}</span>
                                                    <span>{{ $stats['ratings'][5]['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Very Good</p>
                                                <div>
                                                    <span>{{ $stats['ratings'][4]['totalRatings'] }}</span>
                                                    <span>{{ $stats['ratings'][4]['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Good</p>
                                                <div>
                                                    <span>{{ $stats['ratings'][3]['totalRatings'] }}</span>
                                                    <span>{{ $stats['ratings'][3]['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Need Improvements</p>
                                                <div>
                                                    <span>{{ $stats['ratings'][2]['totalRatings'] }}</span>
                                                    <span>{{ $stats['ratings'][2]['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Poor</p>
                                                <div>
                                                    <span>{{ $stats['ratings'][1]['totalRatings'] }}</span>
                                                    <span>{{ $stats['ratings'][1]['percentage'] }}%</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr class="analytics-divider" />
                                    <div class="analytics-data analytics-data-sm">
                                        <h6>Analytics</h6>
                                        <ul>
                                            <li>
                                                <p>Views</p>
                                                <div>
                                                    <span>{{ $stats['analytics']['views']['total'] }}</span>
                                                    <span>{{ $stats['analytics']['views']['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Clicks</p>
                                                <div>
                                                    <span>{{ $stats['analytics']['clicks']['total'] }}</span>
                                                    <span>{{ $stats['analytics']['clicks']['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Bookings</p>
                                                <div>
                                                    <span>{{ $stats['analytics']['bookings']['total'] }}</span>
                                                    <span>{{ $stats['analytics']['bookings']['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Cancellations</p>
                                                <div>
                                                    <span>{{ $stats['analytics']['cancellations']['total'] }}</span>
                                                    <span>{{ $stats['analytics']['cancellations']['percentage'] }}%</span>
                                                </div>
                                            </li>
                                            <li>
                                                <p>Repeat</p>
                                                <div>
                                                    <span>{{ $stats['analytics']['repeat']['total'] }}</span>
                                                    <span>{{ $stats['analytics']['repeat']['percentage'] }}%</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-12">
                            <div class="card card-default card-bordered card-title-bg card-normal card-radious">
                                <div class="card-header ">
                                    <div class="card-title">
                                        <h4 class="text-uppercase">
                                            <strong>
                                                {{ __('Chart') }}
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="card-body">
                                    <canvas id="earning_chart" style="min-height: 320px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <input type="hidden" class="filterField" value="" name="status" id="status">
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                    class="form-control filterField" name="search" id="filterSearch"
                                    placeholder="Enter ID or City">
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
                                <select class="full-width form-control filterField" id="date_options" name="date_option">
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
                            <button type="button" class="btn btn-primary w-100 new-padding form-control" id="doSearch"
                                style="padding: 0px;">
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
                            <div class="col col-mix d-none">
                                <div class="form-group">
                                    <label>Listing</label>
                                    <input type="text" value="<?= $id ?>" class="form-control filterField"
                                        name="space" id="filterSearch" placeholder="Enter Space ID or Name">
                                </div>
                            </div>
                            <div class="col col-mix">
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
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label for="booking_status" class="control-label">BOOKING STATUS</label>
                                    <select id="booking_status" class="full-width form-control filterField"
                                        name="booking_status">
                                        <option value="">Select Status</option>
                                        <option value="draft">DRAFT</option>
                                        <option value="complete">COMPLETE</option>
                                        <option value="processing">PROCESSING</option>
                                        <option value="confirmed">CONFIRMED</option>
                                        <option value="archived">ARCHIVED</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label for="sku" class="control-label">TRANSACTION STATUS</label>
                                    <select id="transaction_status" class="full-width form-control filterField"
                                        name="transaction_status">
                                        <option value="" selected>Select Status</option>
                                        <option value="paid">PAID</option>
                                        <option value="unpaid">UNPAID</option>
                                        <option value="fail">FAIL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" class="form-control filterField" id="amountFilter"
                                        name="amount" placeholder="Enter Amount">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- START card -->
            <div class="card card-default card-bordered card-padding-body-zero p-4 card-radious">
                <div class="card-header card-header-actions">
                    <div class="card-title">
                        <h4 class="pt-1 text-uppercase">
                            <strong>
                                Booking History
                            </strong>
                        </h4>
                    </div>
                    <div class="card-actions">
                        <div class="table-top-filter-length-main">
                            <span>Show</span>
                            <select class="table-top-filter-length">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">All</option>
                            </select>
                            <span>Records</span>
                        </div>
                    </div>
                </div>
                <div class="card-body table-no-page-options">
                    <table class="table demo-table-search table-responsive-block data-table" id="tableHistory">
                        <thead>
                            <tr>
                                <th style="width:5%;"></th>
                                <th style="width:5%;">ID#</th>
                                <th style="width:15%;">Listing Name</th>
                                <th style="width:10%;">Start Date</th>
                                <th style="width:10%;">End Date</th>
                                <th style="width:10%;">Guest</th>
                                <th style="width:10%;">Amount</th>
                                <th style="width:10%;">Book Status</th>
                                <th style="width:10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>


                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th>Quantity:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th colspan="5"></th>
                                <th>Page Total:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th colspan="5"></th>
                                <th>Grand Total:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>

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
        loadEarningStats("week");
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).on("click", "#modifyBookingBtn", function() {
            let selectIds = [];
            $.each($("input[name='checkbox[]']:checked"), function() {
                selectIds.push($(this).val());
            });
            if (selectIds.length > 0) {
                $("#modifyBookIds").val(selectIds.join(","));
                $("#updateBookingModal").modal("show");
            }
        });

        $(document).on("click", ".modifySingleBooking", function() {
            let obj = $(this);
            $("#singleUpdateBooking").val(obj.attr("data-value"));
            $("#updateSingleBookingModal").modal("show");
        });

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
                        data: 'title',
                        name: 'title',

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
                        data: 'guest',
                        name: 'guest'
                    },
                    {
                        data: 'totalFormatted',
                        name: 'total'
                    },
                    {
                        data: 'booking_status',
                        name: 'booking_status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    let quantity = pageTotal = grandTotal = 0;
                    if (data.length > 0) {
                        let additionalData = data[0].additionalData;
                        quantity = additionalData.quantity;
                        pageTotal = additionalData.pageTotal;
                        grandTotal = additionalData.grandTotal;
                    }
                    table.find("tfoot tr:eq(0) th:eq(2)").html(quantity);
                    table.find("tfoot tr:eq(1) th:eq(2)").html(pageTotal);
                    table.find("tfoot tr:eq(2) th:eq(2)").html(grandTotal);
                },
            });

            $(document).on("change", 'select.filterField', function() {
                datatable.draw();
            });

            $(document).on("keypress", 'input.filterField', function(e) {
                if (e.which == 13) {
                    datatable.draw();
                }
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

            $(document).on("change", '.table-top-filter-length', function() {
                var obj = $(this);
                var tableLength = obj.val();
                obj.closest(".card").find('select[name="tableHistory_length"]').val(
                    tableLength).trigger("change");
            });

        });

        $(document).on("change", "#spaceEarningFormField", function() {
            $("#spaceEarningForm").submit();
        });
    </script>


<script>
    function loadEarningStats(earningStatsDuration) {
        $.get("{{ route('space.vendor.spaceEarningReportStats') }}", {
            durationType: earningStatsDuration,
            spaceId: "{{$_GET['id']}}"
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
                    height:400,
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
    // $(document).on("change", "#earningStatsDuration", function() {
    //     loadEarningStats($(this).val());
    // });
    
    setTimeout((e) => {
        loadEarningStats("year");
    }, 1000);
</script>

@endsection
