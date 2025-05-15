@extends('layouts.new_user')
@section('content')
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
                    <li class="breadcrumb-item active">Booking History</li>
                </ol>
            </div>
        </div>

        <div class="container-fluid p-5">
            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <input type="hidden" class="filterField" name="top_search"
                            value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        <input type="hidden" class="filterField" value="{{ $type }}" name="status" id="status">
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
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label for="sku" class="control-label">TRANSACTION STATUS</label>
                                    <select id="transaction_status" class="full-width form-control filterField"
                                        name="transaction_status">
                                        <option value="" selected>Select Status</option>
                                        @foreach (\App\Helpers\Constants::BOOKING_PAYMENT_STATUSES as $k => $txt)
                                            <option value="{{ $k }}">{{ $txt }}</option>
                                        @endforeach
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
                                <th style="width:10%;">Payment Status</th>
                                <th style="width:10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>


                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6"></th>
                                <th>Quantity:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th colspan="6"></th>
                                <th>Page Total:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th colspan="6"></th>
                                <th>Grand Total:</th>
                                <th>-</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row tab-view">
                        <div class="col-sm-12 mt-5 mb-3">
                            <div class="view-btn text-center">
                                <button class="btn btn-primary btn-lg mb-2" id="selectAll">Select All</button>
                                <button class="btn btn-primary btn-lg mb-2 disabled" id="deselectAll">De-Select
                                    All
                                </button>
                                <button class="btn btn-primary btn-lg mb-2 export-report" data-type="pdf">PDF
                                    Report</button>
                                <button class="btn btn-primary btn-lg mb-2 export-report" data-type="xls">XLS
                                    Report</button>
                                {{-- <form method="post" style="display:inline;" id="pdf_report"
                                    action="{{ route('user.booking.bulk.invoice') }}">
                                    @csrf
                                    <input name="pdf_ids" value="" type="hidden" id="pdf_ids">
                                    <button class="btn btn-primary btn-lg mb-2" type="submit">PDF
                                        Report
                                    </button>
                                </form>
                                <form method="post" style="display:inline;" id="xls_report"
                                    action="{{ route('user.booking.export') }}">
                                    @csrf
                                    <input name="xls_ids" value="" type="hidden" id="xls_ids">
                                    <button class="btn btn-primary btn-lg mb-2" type="submit">XLS
                                        Report
                                    </button>
                                </form> --}}
                                <button class="btn btn-primary btn-lg mb-2" id="modifyBookingBtn">Modify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('User::frontend._update_booking')

    <div class="modal fade" id="updateBookingModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('booking.updateMassBooking') }}" method="post">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Update Bookings') }}</h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="booking-review">
                            <div class="booking-review-content">
                                <div class="review-section total-review mt-3">
                                    <input type="hidden" name="ids" id="modifyBookIds">
                                    <select name="status" id="maxUpdateBookStatus" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                            <option value="{{ $k }}">{{ $txt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button class="btn btn btn-primary" id="updateBookings"
                            type="submit">{{ __('Save') }}</button>
                        <span class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</span>
                    </div>
                </form>
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
    </script>
@endsection
