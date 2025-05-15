<?php
$clientTitle = 'Client Reports';
if (isset($_GET['type'])) {
    switch ($_GET['type']) {
        case 'new':
            $clientTitle = 'New Clients';
            break;
        case 'repeat':
            $clientTitle = 'Repeat Clients';
            break;
    }
}
$page_title = $clientTitle;
?>
@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <style>
        .avatar.avatar-sm {
            width: 1.75rem;
            height: 1.75rem;
            line-height: 1.75rem;
            font-size: .65rem;
            margin-right: 10px
        }

        .client-title-bx {
            padding-left: 10px;
            display: flex;
            align-items: center;
            color: #000;
        }
    </style>

    <div class="content sm-gutter">

        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?= $clientTitle ?></li>
                </ol>

            </div>
        </div>

        <div class="container-fluid pt-3 px-5">

            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <input type="hidden" value="<?= isset($_GET['type']) ? $_GET['type'] : '' ?>"
                        class="form-control filterField" name="customerType" id="filterType">
                    <input type="hidden" value="10000" class="form-control filterField" name="limit" id="limit">
                    <div class="row data-search">
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                    class="form-control filterField" name="q" id="filterSearch"
                                    placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>FROM</label>
                                <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                    <input value="<?= isset($_GET['start']) ? $_GET['start'] : '' ?>" type="text"
                                        class="form-control from filterField" name="from">
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
                                    <input value="<?= isset($_GET['end']) ? $_GET['end'] : '' ?>" type="text"
                                        class="form-control to filterField" name="to">
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
                                    <option value_from="{{ date('m/d/Y') }}" value_to="{{ date('m/d/Y') }}" value="today">
                                        Today
                                    </option>
                                    <option value_from="{{ date('m/d/Y', strtotime('monday this week')) }}"
                                        value_to="{{ date('m/d/Y', strtotime('friday this week')) }}" value="this_weekdays">
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
                        <div class="col-sm-3 col-md-3">
                            <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100 new-padding form-control" id="doSearch"
                                style="padding: 0px;">
                                Search
                            </button>
                        </div>
                    </div>
                </div>

            </div>


            <!-- START card -->
            <div class="card card-default card-bordered card-padding-body-zero p-4 mb-100 card-radious">
                <div class="card-header card-header-actions">
                    <div class="card-title">
                        <h4 class="pt-1 text-uppercase">
                            <strong>
                                <?= $clientTitle ?>
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
                                {{-- <th>&nbsp;</th> --}}
                                <th style="width: 150px;">Client Name</th>
                                <th style="width: 50px;">Bookings</th>
                                <th style="width: 50px;">Revenue</th>
                                <th style="width: 100px;">Last Booking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- END card -->
        </div>



    </div>
@endsection


@section('footer')
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
                ajax: {
                    "url": "{{ route('user.reports.topClients') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    'data': function(data) {
                        data['search_query'] = {};
                        $(".filterField").each(function() {
                            let obj = $(this);
                            data['search_query'][obj.attr("name")] = obj.val();
                        });
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
    </script>
@endsection
