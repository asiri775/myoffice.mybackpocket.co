@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <?php
    $tType = 'bookings';
    if (isset($_GET['tType'])) {
        $tType = $_GET['tType'];
    }
    ?>

    <style>
        .new-align {
            padding-top: 5px;
        }

        .new-padding {
            padding-top: 5px;
        }

        .input-sm {
            padding-top: 0px;
        }

        .select2-selection {
            border: 1px solid rgb(206, 212, 218) !important;
            height: 38px !important;
        }
    </style>

    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Booking Transaction History</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5">
            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <input type="hidden" class="filterField" name="top_search"
                            value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">

                        <input type="hidden" class="filterField" value="<?= $tType ?>" name="tType" id="tType">
                        <input type="hidden" class="filterField"
                            value="<?= isset($_GET['subType']) ? $_GET['subType'] : '' ?>" name="booking_status"
                            id="subType">

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
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Listing</label>
                                    <input type="text" value="<?= isset($_GET['space']) ? $_GET['space'] : '' ?>"
                                        class="form-control filterField" name="space" id="filterSearch"
                                        placeholder="Enter Space ID or Name">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Guest</label>
                                    <input type="text" value="<?= isset($_GET['guest']) ? $_GET['guest'] : '' ?>"
                                        class="form-control filterField" name="guest" id="filterSearch"
                                        placeholder="Enter Guest Name">
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
                                Booking Transaction History
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
                                {{-- <th style="width:5%;"></th> --}}
                                <th style="width:15%;">Client Name</th>
                                <th style="width:15%;">Listing Name</th>
                                <th style="width:10%;">Order ID</th>
                                <th style="width:10%;">Order Date</th>
                                <th style="width:10%;">Amount</th>
                                <th style="width:10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4"></th>
                                <th>Quantity:</th>
                                <th>-</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>Page Total:</th>
                                <th>-</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>Grand Total:</th>
                                <th>-</th>
                            </tr>
                            <tr>
                                <th colspan="4"></th>
                                <th>Earnings Total:</th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row tab-view">
                        <div class="col-sm-12 mt-5 mb-3">
                            <div class="view-btn text-center">
                                <button class="btn btn-primary btn-lg mb-2 export-report" data-type="pdf">PDF
                                    Report</button>
                                <button class="btn btn-primary btn-lg mb-2 export-report" data-type="xls">XLS
                                    Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function getSearchFormData() {
            var search_query = {};
            $(".table-filters").find(".filterField").each(function() {
                let obj = $(this);
                search_query[obj.attr("name")] = obj.val();
            });
            return search_query;
        }

        function downloadExportFile(exportType) {
            const data = getSearchFormData();
            $.ajax("{{ route('user.bookings.datatable') }}?exportType=" + exportType, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    search_query: data
                },
                method: "POST",
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response, status, xhr) {
                    const disposition = xhr.getResponseHeader('Content-Disposition');
                    let filename = 'report';
                    if (disposition && disposition.includes('filename=')) {
                        const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }
                    const url = window.URL.createObjectURL(new Blob([response], {
                        type: response.type
                    }));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                },
                error: function(xhr, status, error) {
                    console.error("Error downloading Export File:", error);
                }
            });
        }

        $(document).ready(function(e) {
            var table = $('#tableHistory');
            $.fn.dataTable.ext.errMode = 'none';

            let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

            $(document).on("click", ".export-report", function() {
                const exportType = $(this).attr("data-type");
                downloadExportFile(exportType);
            });

            var datatable = table.DataTable({
                ...dataTableOptions,
                "ajax": {
                    "url": "{{ route('user.bookings.datatable') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    'data': function(data) {
                        data['search_query'] = getSearchFormData();
                    }
                },
                "order": [
                    [3, "desc"]
                ],
                "columns": [{
                        data: 'guest',
                        name: 'guest'
                    },
                    {
                        data: 'title',
                        name: 'title',

                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'totalFormatted',
                        name: 'total'
                    },
                    {
                        data: 'booking_status',
                        name: 'booking_status'
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
                        hostAmountTotal = additionalData.hostAmountTotal;
                    }
                    table.find("tfoot tr:eq(0) th:eq(2)").html(quantity);
                    table.find("tfoot tr:eq(1) th:eq(2)").html(pageTotal);
                    table.find("tfoot tr:eq(2) th:eq(2)").html(grandTotal);
                    table.find("tfoot tr:eq(3) th:eq(2)").html(hostAmountTotal);
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
    </script>
@endsection
