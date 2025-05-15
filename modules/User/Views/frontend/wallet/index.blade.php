@extends('layouts.yellow_user')
@section('head')
@endsection
@section('content')
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ __('Wallet') }}</li>
                </ol>



            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="bravo-user-dashboard">

                <div class="row">
                    <div class="col-md-7 col-12">

                        <div class="card card-no-card card-bordered p-2 card-radious pt-0">
                            <div class="card-header card-header-actions ">
                                <div class="card-title">
                                    <h4 class="text-uppercase">
                                        <strong>
                                            Wallet
                                        </strong>
                                    </h4>
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('user.wallet.buy') }}"
                                        class="btn btn-primary">{{ __('Buy Credits') }}</a>
                                    <a href="{{ route('user.wallet.withdraw') }}"
                                        class="btn btn-primary ms-5px">{{ __('Withdraw') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="card card-default card-bordered p-4 card-radious">

                            <div class="table-filters p-0">
                                <div class="row data-search">

                                    <div class="col col-mix">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="form-control filterField" name="amount"
                                                id="filterSearch" placeholder="Enter Amount">
                                        </div>
                                    </div>

                                    <div class="col col-mix">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="type" class="form-control filterField">
                                                <option value="">Select</option>
                                                <option value="earnings">Earnings</option>
                                                <option value="withdrawal">Withdrawal</option>
                                                <option value="deposit">Deposits</option>
                                                <option value="promo">Promo</option>
                                                <option value="refund">Refund</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col col-mix">
                                        <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                        <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                            id="doSearch" style="padding: 0px;">
                                            Search
                                        </button>
                                    </div>

                                    <div class="col col-mix">
                                        <div class="form-group">
                                            <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                            <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                                id="ActivateAdvanceSerach" style="padding: 0px;">
                                                Advance Search
                                            </button>
                                            <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                                id="HideActivateAdvanceSerach" style="display: none; padding: 0px;">Hide
                                                Advance
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="searchFilters m-b-10" id="AdvanceFilters" style="display: none;">
                                    <div class="row">

                                        <div class="col col-mix">
                                            <div class="form-group">
                                                <label>ID</label>
                                                <input type="text" class="form-control filterField" name="id"
                                                    id="filterSearch" placeholder="Enter ID">
                                            </div>
                                        </div>

                                        <div class="col col-mix">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" id="status" class="form-control filterField">
                                                    <option value="">Select Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="processing">Processing</option>
                                                    <option value="confirmed">Confirmed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col col-mix">
                                            <div class="form-group">
                                                <label>From</label>
                                                <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                                    <input type="text" class="form-control from filterField"
                                                        name="from">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text"><i
                                                                class="pg-icon">calendar</i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-mix">
                                            <div class="form-group">
                                                <label>To</label>
                                                <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                                    <input type="text" class="form-control to filterField"
                                                        name="to">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text"><i
                                                                class="pg-icon">calendar</i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-mix">
                                            <div class="form-group">
                                                <label>QUICK DATE</label>
                                                <select class="full-width form-control filterField" id="date_options"
                                                    name="date_option">
                                                    <option value="">Pick an option</option>
                                                    <option value_from="{{ date('m/d/Y', strtotime('yesterday')) }}"
                                                        value_to="{{ date('m/d/Y', strtotime('yesterday')) }}"
                                                        value="yesterday">
                                                        Yesterday
                                                    </option>
                                                    <option value_from="{{ date('m/d/Y') }}"
                                                        value_to="{{ date('m/d/Y') }}" value="today">
                                                        Today
                                                    </option>
                                                    <option
                                                        value_from="{{ date('m/d/Y', strtotime('monday this week')) }}"
                                                        value_to="{{ date('m/d/Y', strtotime('friday this week')) }}"
                                                        value="this_weekdays">
                                                        This Weekdays
                                                    </option>
                                                    <option
                                                        value_from="{{ date('m/d/Y', strtotime('monday this week')) }}"
                                                        value_to="{{ date('m/d/Y', strtotime('sunday this week')) }}"
                                                        value="this_whole_week">
                                                        This Whole Week
                                                    </option>
                                                    <option
                                                        value_from="{{ date('m/d/Y', strtotime('first day of this month')) }}"
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
                                            LATEST TRANSACTIONS
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
                                <div class="table-responsive">
                                    <table class="table demo-table-search table-responsive-block data-table"
                                        id="tableHistory">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;"></th>
                                                <th style="width: 50px;">ID#</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table>
                                        <div class="row tab-view">
                                                <div class="col-sm-12 col-12 mt-5 mb-3">
                                                    <div class="view-btn text-center">
                                                        <button class="btn btn-primary btn-lg mb-2" id="selectAll">Select All</button>
                                                        <button class="btn btn-primary btn-lg mb-2 disabled" id="deselectAll">De-Select
                                                            All
                                                        </button>
                                                        <form method="post" style="display:inline;" id="pdf_report"
                                                            action="{{ route('transaction.export.pdf') }}">
                                                            @csrf
                                                            <input name="pdf_ids" value="" type="hidden" id="pdf_ids">
                                                            <button class="btn btn-primary btn-lg mb-2" type="submit">PDF
                                                                Report
                                                            </button>
                                                        </form>
                                                        <form method="post" style="display:inline;" id="xls_report"
                                                            action="{{ route('transaction.export.xls') }}">
                                                            @csrf
                                                            <input name="xls_ids" value="" type="hidden" id="xls_ids">
                                                            <button class="btn btn-primary btn-lg mb-2" type="submit">XLS
                                                                Report
                                                            </button>
                                                        </form>
                                                        <!-- <button class="btn btn-primary btn-lg mb-2" id="modifyBookingBtn">Modify</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-5 col-12">
                        @include('User::frontend.wallet._sidebar')
                    </div>
                </div>

            </div>



        </div>


    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="transactionDetailsModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content enquiry_form_modal_form" style="width: 600px;">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Transaction Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-3">

                </div>
            </div>
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
                    "url": "{{ route('user.transactionHistory') }}",
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
                "order": [
                    [1, "desc"]
                ],
                "columns": [
                    {
                        data: 'checkboxes',
                        name: 'checkboxes',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'idLink',
                        name: 'id'
                    },
                    {
                        data: 'date',
                        name: 'created_at',
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'status',
                        name: 'status_name',
                        orderable: false
                    },
                    // {
                    //     data: 'description',
                    //     name: 'description',
                    //     visible: false
                    // },

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

        $(document).ready(function() {
            var array = [];
            $("#selectAll").on("click", function(e) {
                var table = $("#tableHistory");
                var boxes = $('input:checkbox', table);
                $.each($('input:checkbox', table), function() {

                    $(this).parent().addClass('checked');
                    $(this).prop('checked', 'checked');

                });

                $('#selectAll').addClass('disabled');
                $('#deselectAll').removeClass('disabled');
            });

            $("#deselectAll").on("click", function(e) {
                var table = $("#tableHistory");
                var boxes = $('input:checkbox', table);
                $.each($('input:checkbox', table), function() {

                    $(this).parent().removeClass('checked');
                    $(this).prop('checked', false);

                });
                $('#deselectAll').addClass('disabled');
                $('#selectAll').removeClass('disabled');
            });

        });

        $('#pdf_report').submit(function() {
            let select_pdf_values = [];
            $.each($("input[name='checkbox[]']:checked"), function() {
                select_pdf_values.push($(this).val());
            });

            if (select_pdf_values.length > 0) {
                $('#pdf_ids').val(select_pdf_values);
            } else {
                alert('Please select at least one booking.');
                return false;
            }
        });

        $('#xls_report').submit(function() {
            let select_xls_values = [];
            $.each($("input[name='checkbox[]']:checked"), function() {
                select_xls_values.push($(this).val());
            });

            if (select_xls_values.length > 0) {
                $('#xls_ids').val(select_xls_values);
            } else {
                alert('Please select at least one booking.');
                return false;
            }
        });

        $(document).on("click", ".showTransactionDetails", function(e) {
            e.preventDefault();
            $("#transactionDetailsModal").modal("show").addClass("loading");
            $("#transactionDetailsModal .modal-body").html("Loading...");
            $.get("{{ route('user.transactionDetails', ['']) }}/" + $(this).attr("data-id"), function(r) {
                $("#transactionDetailsModal").removeClass("loading");
                $("#transactionDetailsModal .modal-body").html(r);
            });
        });
    </script>
@endsection