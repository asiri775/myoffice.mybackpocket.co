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
                    <li class="breadcrumb-item active">Coupon Transactions</li>
                </ol>

            </div>
        </div>

        <div class="container-fluid pt-3 px-5">

            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <input type="hidden" value="10000" class="form-control filterField" name="limit" id="limit">
                    <div class="row data-search">
                        <div class="col-sm-2 col-md-2">
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
                                    <label>Guest</label>
                                    <input type="text" value="<?= isset($_GET['guest']) ? $_GET['guest'] : '' ?>"
                                        class="form-control filterField" name="guest" id="filterSearch"
                                        placeholder="Enter Guest Name">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Order ID</label>
                                    <input type="text" value="<?= isset($_GET['orderId']) ? $_GET['orderId'] : '' ?>"
                                        class="form-control filterField" name="orderId" id="filterSearch"
                                        placeholder="Enter Order Id">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group bravo-range-filter pr-3">
                                    <label>Gross Sale Value</label>
                                    <div class="iri-filter">
                                        <input type="hidden" class="filter-price irs-hidden-input"
                                            data-min="0" data-max="10000" data-symbol="$" data-from="0" data-to="10000"
                                            readonly="">
                                    </div>
                                    <input type="hidden"
                                        value="<?= isset($_GET['grossSaleFrom']) ? $_GET['grossSaleFrom'] : '' ?>"
                                        class="form-control from filterField" name="grossSaleFrom" id="filterSearch"
                                        placeholder="Enter Gross Sale From">
                                    <input type="hidden"
                                        value="<?= isset($_GET['grossSaleTo']) ? $_GET['grossSaleTo'] : '' ?>"
                                        class="form-control to filterField" name="grossSaleTo" id="filterSearch"
                                        placeholder="Enter Gross Sale To">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group bravo-range-filter">
                                    <label>Discount Amount Value</label>
                                    <div class="iri-filter">
                                        <input type="hidden" class="filter-price irs-hidden-input"
                                            data-min="0" data-max="10000" data-symbol="$" data-from="0" data-to="10000"
                                            readonly="">
                                    </div>
                                    <input type="hidden"
                                        value="<?= isset($_GET['discountFrom']) ? $_GET['discountFrom'] : '' ?>"
                                        class="form-control from filterField" name="discountFrom" id="filterSearch"
                                        placeholder="Enter Discount From">
                                    <input type="hidden"
                                        value="<?= isset($_GET['discountTo']) ? $_GET['discountTo'] : '' ?>"
                                        class="form-control to filterField" name="discountTo" id="filterSearch"
                                        placeholder="Enter Discount To">
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
                                Coupon Transactions
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
                                <th style="width: 50px;">Client Name</th>
                                <th style="width: 50px;">Code</th>
                                <th style="width: 50px;">Order ID</th>
                                <th style="width: 100px;">Order Date</th>
                                <th style="width: 100px;">Gross Sale</th>
                                <th style="width: 100px;">Discount Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th>Quantity:</th>
                                <th>-</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th>Gross Total:</th>
                                <th>-</th>
                                <th>-</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th>Discount Amount:</th>
                                <th>-</th>
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
            $.ajax("{{ route('user.reports.promoCodesDataTable') }}?exportType=" + exportType, {
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

        $(document).on("click", ".export-report", function() {
            const exportType = $(this).attr("data-type");
            downloadExportFile(exportType);
        });

        $(document).ready(function(e) {
            var table = $('#tableHistory');
            $.fn.dataTable.ext.errMode = 'none';

            let dataTableOptions = {!! json_encode(\App\Helpers\CodeHelper::dataTableOptions()) !!};

            var datatable = table.DataTable({
                ...dataTableOptions,
                ajax: {
                    "url": "{{ route('user.reports.promoCodesDataTable') }}",
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
                        data: 'clientName',
                        name: 'clientName',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'coupon_code',
                        name: 'coupon_code'
                    },
                    {
                        data: 'orderId',
                        name: 'orderId',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'grossSaleFormatted',
                        name: 'grossSale',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'coupon_amount_formatted',
                        name: 'coupon_amount',
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    let quantity = pageGrossSaleTotal = pageDiscountTotal = grossSaleTotal =
                        discountTotal = 0;
                    if (data.length > 0) {
                        let additionalData = data[0].additionalData;
                        quantity = additionalData.quantity;
                        pageGrossSaleTotal = additionalData.pageGrossSaleTotal;
                        pageDiscountTotal = additionalData.pageDiscountTotal;
                        grossSaleTotal = additionalData.grossSaleTotal;
                        discountTotal = additionalData.discountTotal;
                    }
                    table.find("tfoot tr:eq(0) th:eq(2)").html(quantity);
                    table.find("tfoot tr:eq(1) th:eq(2)").html(pageGrossSaleTotal);
                    table.find("tfoot tr:eq(1) th:eq(3)").html(grossSaleTotal);
                    table.find("tfoot tr:eq(2) th:eq(2)").html(pageDiscountTotal);
                    table.find("tfoot tr:eq(2) th:eq(3)").html(discountTotal);
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
