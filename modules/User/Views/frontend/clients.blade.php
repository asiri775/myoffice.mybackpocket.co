@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <?php
    
    use Modules\Core\Models\Attributes;
    ?>

    <style>
        tr td:nth-child(5) {
    text-align: right !important;
    padding-right: 35px !important;
}
    </style>
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Clients</li>
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
                                    <strong>Clients</strong>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <input type="hidden" class="filterField" value="" name="status" id="status">
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                    class="form-control filterField" name="search" id="filterSearch"
                                    placeholder="Enter Name or Email">
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
                        <div class="col-sm-3 col-md-3">
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
                        {{-- <div class="col-sm-2 col-md-2">
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
                        </div> --}}
                    </div>
                    {{-- <div class="searchFilters m-b-10" id="AdvanceFilters" style="display: none;">
                        <div class="row">
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Listing</label>
                                    <input type="text" class="form-control filterField" name="space"
                                        id="filterSearch" placeholder="Enter Space ID or Name">
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div> 

            </div>
            <!-- START card -->
            <div class="card card-default card-bordered card-padding-body-zero p-4 card-radious">
                <div class="card-header card-header-actions">
                    <div class="card-title">
                        <h4 class="pt-1 text-uppercase">
                            <strong>
                                All Clients
                            </strong>
                        </h4>
                    </div>
                    <div class="card-actions">
                        <div class="table-top-filter-length-main">
                            <span>Show</span>
                            <select class="table-top-filter-length" id="table-top-filter-length">
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
                                <th style="width:15%;">Name</th>
                                <th style="width:10%;">Last Booking</th>
                                <th style="width:10%;"># of Bookings</th>
                                <th style="width:10%;" style="text-align: center;">Revenue</th>
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
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('libs/chart_js/Chart.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(function($) {

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
                    "url": "{{ route('user.bookings.client.datatable') }}",
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
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'first_name',
                    },
                    {
                        data: 'last_booking',
                        name: 'last_booking',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'total_bookings',
                        name: 'total_bookings',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'total_revenue',
                        name: 'total_revenue',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        visible: false
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

            $(document).on("change", '#table-top-filter-length', function() {
                var obj = $(this);
                var tableLength = obj.val();
                obj.closest(".card").find('select[name="tableHistory_length"]').val(
                    tableLength).trigger("change");
            });

            $(document).on("change", '#filter-by-space-id', function() {
                var obj = $(this);
                var val = obj.val();
                $('.filterField[name="space"]').val(val);
                datatable.draw();
            });

        });

        $(document).on("change", "#spaceEarningFormField", function() {
            $("#spaceEarningForm").submit();
        });
    </script>
@endsection
