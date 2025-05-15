@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <div class="content sm-gutter">

        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Spaces</li>
                </ol>

            </div>
        </div>

        <div class="container-fluid pt-3 px-5">

            @if (Auth::user()->hasPermissionTo('space_create') && empty($recovery))
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="{{ route('space.vendor.create') }}" class="btn btn-primary reverse ">
                            {{ __('Add New Space') }}
                        </a>
                    </div>
                </div>
            @endif

            <div class="card card-default card-bordered p-4 card-radious">

                <div class="table-filters p-0">
                    <div class="row data-search">
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>"
                                    class="form-control filterField" name="q" id="filterSearch"
                                    placeholder="Enter ID/Title/Alias">
                            </div>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" value="" class="form-control filterField" id="citySearchFilter"
                                    placeholder="Search City">
                                <input type="hidden" name="city" class="filterField" id="citySearchFilterVal">
                            </div>
                        </div>
                        <div class="col-sm-5 col-md-5">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>BOOKED FROM</label>
                                    <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                        <input type="text" class="form-control from filterField" name="from">
                                        <div class="input-group-append ">
                                            <span class="input-group-text"><i class="pg-icon">calendar</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>BOOKED TO</label>
                                    <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                        <input type="text" class="form-control to filterField" name="to">
                                        <div class="input-group-append ">
                                            <span class="input-group-text"><i class="pg-icon">calendar</i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
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
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <label for="ActivateAdvanceSerach" class="control-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100 new-padding form-control"
                                        id="doSearch" style="padding: 0px;">
                                        Search
                                    </button>
                                </div>
                                <div class="col-sm-8 col-md-8">
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
                        </div>
                    </div>
                    <div class="searchFilters m-b-10" id="AdvanceFilters" style="display: none;">
                        <div class="row">
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control filterField" name="address"
                                        id="filterSearch" placeholder="Enter Address">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Earnings From</label>
                                    <input type="text" class="form-control filterField" name="earnings_from"
                                        id="filterSearch" placeholder="Enter Earnings">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label>Earnings To</label>
                                    <input type="text" class="form-control filterField" name="earnings_to"
                                        id="filterSearch" placeholder="Enter Earnings">
                                </div>
                            </div>
                            <div class="col col-mix">
                                <div class="form-group">
                                    <label for="booking_status" class="control-label">Status</label>
                                    <select id="booking_status" class="full-width form-control filterField"
                                        name="status">
                                        <option value="">Select Status</option>
                                        <option value="draft">Draft</option>
                                        <option value="publish">Publish</option>
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
                                Spaces
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
                                <th style="width: 50px;">ID#</th>
                                <th>Title</th>
                                <th>Alias</th>
                                <th>Status</th>
                                <th>Last Booking</th>
                                <th>Bookings</th>
                                <th>Earnings</th>
                                <th>Actions</th>
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

<div id="availabilityCalendar" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id='availabilityTimeCalendar'></div>
            </div>
        </div>
    </div>
</div>

@section('footer')
    <script>
        let availabilityTimeCalendar = null;

        function showCalendarModal(spaceId) {
            $("#availabilityCalendar").modal("show");
            availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
                eventSources: [{
                    url: '{{ route('space.vendor.availability.calendarEvents') }}?id=' + spaceId,
                }],
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                dayMaxEvents: true,
                navLinks: true
            });
            availabilityTimeCalendar.render();
        }

        $(document).on("click", ".viewSpaceCalendar", function() {
            showCalendarModal($(this).attr("data-id"));
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
                ajax: {
                    "url": "{{ route('space.vendor.datatable') }}",
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
                "columns": [
                    // {
                    //     data: 'checkboxes',
                    //     name: 'checkboxes',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'idLink',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'alias',
                        name: 'alias'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'lastBooking',
                        name: 'lastBooking',
                        orderable: false,
                    },
                    {
                        data: 'totalBookings',
                        name: 'totalBookings',
                        orderable: false,
                    },
                    {
                        data: 'earnings',
                        name: 'earnings',
                        orderable: false
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

    <script>
        function initGoogleAutoCompleteField() {
            var input = document.getElementById('citySearchFilter');
            var options = {
                types: ['(cities)'],
                componentRestrictions: {country: ["us", "ca"]}
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                document.querySelector("#citySearchFilterVal").value = '';
                console.log(place.address_components);
                for (let addressComponent of place.address_components) {
                    if (addressComponent['types'].includes("locality")) {
                        document.querySelector("#citySearchFilterVal").value = addressComponent.long_name;
                    }
                }
            });
        }

        $(function() {
            initGoogleAutoCompleteField();
        });
    </script>
@endsection
