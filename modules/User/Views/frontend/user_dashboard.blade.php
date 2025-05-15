@extends('layouts.new_user')
@section('content')
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS
                                                                                                                                                                                                                                                  <div class="bg-white">
                                                                                                                                                                                                                                                    <div class="container">
                                                                                                                                                                                                                                                      <ol class="breadcrumb breadcrumb-alt">
                                                                                                                                                                                                                                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                                                                                                                                                                                                                                        <li class="breadcrumb-item active">Dashboard</li>
                                                                                                                                                                                                                                                      </ol>
                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                  </div-->
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5">
            <div class="row top-btn user-welcome-banner mb-5">
                <div class="bg-overlay"></div>
                <div class="text-banner">
                    <div class="column_container col-md-6">
                        <div class="st-become-local pull-left">
                            <div class="wpb_wrapper st-become-local">
                                <h2><span class="f48">Hi {{ Auth::user()->first_name }},</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="column_container col-md-6">
                        <div class="vc_column-inner">
                            <div class="wpb_wrapper">
                                <div class="vc_btn3-container  pull-right">
                                    <a href="{{ route('space.search', ['_layout' => 'map']) }}"
                                        class="st-become-local-btn  btn btn-lg btn-larger">Book Your Next
                                        Space
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="title title-fonts sub-title">
                        <h3>Upcoming Bookings</h3>
                    </div>
                    @foreach ($bookings as $booking)
                        <?php
                        $space = \Modules\Space\Models\Space::where('id', $booking->object_id)->first();
                        ?>
                        <div class="card card-default card-bordered p-4 card-radious">
                            <div class="row">
                                <div class="w-3 relative">
                                    @php
                                        $service = $booking->service;
                                        $translation = $service->translateOrOrigin(app()->getLocale());
                                    @endphp
                                    <div class="image_feature" style="background-image: url({{ $service->image_url }})">
                                        <div class="host-img text-center">
                                            <span class="thumbnail-wrapper circular inline">
                                                <img src="{{ $booking->vendor->getAvatarUrl() }}" alt=""
                                                    data-src="{{ $booking->vendor->getAvatarUrl() }}"
                                                    data-src-retina="{{ $booking->vendor->getAvatarUrl() }}" width="45"
                                                    height="45" data-toggle="tooltip" data-placement="top"
                                                    title="{{ $booking->vendor->getPublicName() }}">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-5 pl-3 pr-2">


                                    <div class="info-title">

                                        <h3><a style="color:#333" target="_blank"
                                                href="{{ $space->getDetailUrl($include_param ?? true) }}">{{ clean($translation->title) }}</a>
                                        </h3>
                                    </div>
                                    <div class="info-location">
                                        <span class="event-icon">
                                            <span class="material-icons">fmd_good</span>
                                        </span>
                                        <span class="loca-add">
                                            <a style="color:#333;"
                                                href="https://www.google.com/maps/place/{{ urlencode($translation->address) }}"
                                                target="_blank">{{ $translation->address }}</a>
                                        </span>
                                    </div>
                                    @php
                                        $date = $booking->start_date;
                                        $end_date = $booking->end_date;
                                    @endphp

                                    <div class="event-time">
                                        <span class="event-icon">
                                            <span class="material-icons">access_time</span>
                                        </span>
                                        <span class="loca-add">{{ date('D, F d, Y, H:i a', strtotime($date)) }} </span>
                                    </div>
                                    <div class="event-time">
                                        <span class="event-icon">
                                            <span class="material-icons">update</span>
                                        </span>
                                        <span class="loca-add">{{ date('D, F d, Y, H:i a', strtotime($end_date)) }}</span>
                                    </div>
                                    <div class="event-geust">
                                        <span class="event-icon">
                                            <span class="material-icons">person</span>
                                        </span>
                                        <span class="loca-add">{{ $booking->total_guests }}</span>
                                    </div>

                                    <div class="event-time icon-tooltip">
                                        <a target="_blank" href="{{ route('user.single.booking.detail', $booking->id) }}">
                                            <div class="event-icon">
                                                <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="View Booking">
                                                    visibility
                                                </span>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="event-icon">
                                                <span class="icon-tooltip material-icons" data-toggle="tooltip"
                                                    data-placement="top" title=""
                                                    data-original-title="Add to Calendar">
                                                    share
                                                </span>
                                            </div>
                                        </a>
                                        <a href="#">
                                            <div class="event-icon">
                                                <span class="material-icons" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="Manage Booking">
                                                    calendar_month
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="w-2">
                                    <div class="date-start text-center mb-2">
                                        <div class="calendar-day">
                                            <a href="{{ route('user.single.booking.detail', $booking->id) }}">
                                                <div class="day-name">{{ date('d', strtotime($date)) }}</div>
                                                <div class="m-name">{{ date('F', strtotime($date)) }}</div>
                                                <?php
                                                $book_status = $booking->statusText();
                                                $book_class = $booking->statusClass();
                                                ?>
                                                <div class="status-btn <?=$book_class?>"><?=$book_status?></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="b-id">ID#{{ $booking->id }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!--card-->
                    <div class="view-btn text-center">
                        <a href="{{ route('user.bookings.details') . '?type=all' }} ">
                            <button class="btn btn-primary py-2 mt-4" style="font-weight: 500">View All Bookings</button>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 cal-plugin">
                    <div class="title title-fonts sub-title">
                        <h3>Plan Ahead</h3>
                    </div>
                    <div class="card card-default full-height card-bordered p-4 card-radious" style="min-height: 800px">
                        <!-- START CALENDAR -->
                        <div id="booking_calander" class="full-height cal-home"></div>
                        <!-- END CALENDAR -->
                        <!-- START EVENT MANAGER -->
                        <!-- START Calendar Events Form -->
                        <div class="quickview-wrapper event-side-quick-view-wrapper calendar-event" id="calendar-event">

                        </div>
                        <!-- END Calendar Events Form -->
                        <!-- START EVENT MANAGER -->
                    </div>

                </div>
            </div>
            <!--row end booking-->
        </div>
        <!-- END CONTAINER FLUID -->
        <div class="container info-div">
            <div class="row mt-5 mb-5">
                <div class="col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 mb-5">
                            <div class="second-div">
                                <img src="{{ asset('user_assets/img/grow-bussiness.jpg') }}">
                                <h3 class="mt-3 mb-3">Grow Your Business</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                    nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                    libero. </p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 mb-5">
                            <div class="second-div">
                                <img src="{{ asset('user_assets/img/learn.jpg') }}">
                                <h3 class="mt-3 mb-3">Learn</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                    nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                    libero. </p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 mb-5">
                            <div class="second-div">
                                <img src="{{ asset('user_assets/img/take-brake.jpg') }}">
                                <h3 class="mt-3 mb-3">Take a Break</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus tempus leo
                                    nec interdum. Vivamus id lorem eget sapien consequat euismod id eget
                                    libero. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end container-->
    </div>

    <div class="modal fade" id="scheduleConfirmModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="padding-bottom:15px;">
                <div class="modal-header text-center justify-content-between">
                    <h5 style="font-family:Montserrat;font-size:16pt;font-weight:900;"
                        class="modal-title tex-center w-100"><img width="30" height="30"
                            src="<?php echo url('/icon/mo_calendar.svg'); ?>" />&nbsp;&nbsp;BOOKING <span class="bookingId"></span> SCHEDULE
                        <hr>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <hr>
                <div class="modal-body text-center" style="margin-top:-13%;">
                    <p id="msgbox" style="margin-top:10%;"></p>
                    <div class="scheduleConfirmModalData">
                        <p>Your Booking <span class="bookingId"></span> will be moved to</p>
                        <div class="modifydatesthings">
                            <h5><span>Start Date:</span> <span class="scheduleConfirmModalStartDate"></span></h5>
                            <h5><span>Depart Date:</span> <span class="scheduleConfirmModalEndDate"></span></h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;justify-content:center;">
                    <button type="submit" id="rescheduleYes" class="btn btn-primary modalbtn">Confirm</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modifydatesthings h5 {
            font-size: 15px;
            margin-top: 10px;
            color: #000;
        }
    </style>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://fullcalendar.io/js/fullcalendar-3.5.1/fullcalendar.min.js'></script>
    <script src='https://fullcalendar.io/js/home.js?3.5.1-1.7.1-1'></script>

    <script>
        $(document).ready(function() {
            var selectedEvent;

            var eventData = null;

            let calendarEvents = [];
            let SEARCH_AJAX_REQUEST = null;

            $(document).on("click", "#rescheduleYes", function() {
                var event = eventData;
                let startMomentDateReal = moment(event.other.startDate);
                let endMomentDateReal = moment(event.other.endDate);

                let startMomentDate = moment(event.start);
                let endMomentDate = moment(event.start);

                let startDate = startMomentDate.format("MM/DD/YYYY");
                let startHour = startMomentDateReal.format("HH:mm");
                let startAmpPm = startMomentDateReal.format("A");

                let endDate = endMomentDate.format("MM/DD/YYYY");
                let endHour = endMomentDateReal.format("HH:mm");
                let endAmpPm = endMomentDateReal.format("A");

                let spaceId = event.other.spaceId;
                let bookingId = event.other.id;

                if (SEARCH_AJAX_REQUEST != null) {
                    SEARCH_AJAX_REQUEST.abort();
                    SEARCH_AJAX_REQUEST = null;
                }

                SEARCH_AJAX_REQUEST = $.post(
                    "{{ route('space.vendor.availability.verifySelectedTimes') }}", {
                        id: spaceId,
                        start_date: startDate,
                        end_date: endDate,
                        start_hour: startHour,
                        end_hour: endHour,
                        start_ampm: startAmpPm,
                        end_ampm: endAmpPm,
                    },
                    function(response) {
                        if (response.status == 'error') {
                            // toastr.error(response.message);
                            window.webAlerts.push({
                                type: "error",
                                message: response.message
                            });
                            reloadCalendarEvents();
                        } else if (response.status == 'success') {
                            //actual move
                            $.post(
                                "{{ route('user.moveBooking') }}", {
                                    id: spaceId,
                                    startTime: startDate + " " + startHour,
                                    endTime: endDate + " " + endHour,
                                    bookingId: bookingId
                                },
                                function(response) {
                                    if (response.status == 'error') {
                                        // toastr.error(response.message);
                                        window.webAlerts.push({
                                            type: "error",
                                            message: response.message
                                        });
                                    } else if (response.status == 'success') {
                                        window.webAlerts.push({
                                            type: "success",
                                            message: "Booking has been rescheduled"
                                        });
                                    }
                                    $('#scheduleConfirmModal').modal("hide");
                                });
                        }
                    });
            });

            $('#scheduleConfirmModal').on('hidden.bs.modal', function() {
                reloadCalendarEvents();
            });

            $('#booking_calander').pagescalendar({
                disableDragging: true,
                ui: {
                    dateHeader: {
                        format: 'MMMM D, YYYY | dddd',
                        visible: true,
                    },
                },
                events: calendarEvents,
                view: "month",
                onEventClick: function(event) {
                    $.ajax({
                        "url": "{{ route('user.bookings.get.detail') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        "method": "POST",
                        data: {
                            id: event.other.id,
                        },
                        success: function(data) {
                            $('#calendar-event').addClass('open');
                            $("#calendar-event").html(data.html);
                        }
                    });
                },
                onEventDragComplete: function(event) {
                    // reloadCalendarEvents();
                    eventData = event;
                    $("#scheduleConfirmModal").modal("show");
                    let startMomentDateReal = moment(event.other.startDate);
                    let endMomentDateReal = moment(event.other.endDate);

                    let startMomentDate = moment(event.start);
                    let endMomentDate = moment(event.start);

                    let startDate = startMomentDate.format("MM/DD/YYYY");
                    let startHour = startMomentDateReal.format("HH:mm");
                    let startAmpPm = startMomentDateReal.format("A");

                    let endDate = endMomentDate.format("MM/DD/YYYY");
                    let endHour = endMomentDateReal.format("HH:mm");
                    let endAmpPm = endMomentDateReal.format("A");

                    let spaceId = event.other.spaceId;

                    $(".bookingId").html("#" + spaceId);

                    $(".scheduleConfirmModalStartDate").html(startDate + ' ' + startHour + ' ' +
                        startAmpPm);
                    $(".scheduleConfirmModalEndDate").html(endDate + ' ' + endHour + ' ' + endAmpPm);
                },
            });

            function reloadCalendarEvents() {
                $('#booking_calander').pagescalendar('removeAllEvents');
                $('#booking_calander').pagescalendar('rebuild');
                $.get("{{ route('user.dashboardData') }}", function(response) {
                    let bookings = response.bookings;
                    for (let booking of bookings) {
                        let serviceId = '-';
                        if (booking.service !== undefined) {
                            serviceId = booking.service?.id;
                        }
                        let eventObj = {
                            title: '<span class="eventBookingHover" data-id="' + booking.id +
                                '">#' + serviceId + '</span>',
                            class: 'bg-success-lighter',
                            start: new Date(booking.start_date).toISOString(),
                            end: new Date(booking.end_date).toISOString(),
                            other: {
                                id: booking.id,
                                spaceId: serviceId,
                                startDate: booking.start_date,
                                endDate: booking.end_date,
                            }
                        };
                        $('#booking_calander').pagescalendar('addEvent', eventObj);
                    }
                    $('#booking_calander').pagescalendar('rebuild');
                });
            }

            reloadCalendarEvents();

            function setEventDetailsToForm(event) {
                $('#eventIndex').val();
                $('#txtEventName').val();
                $('#txtEventCode').val();
                $('#txtEventLocation').val();
                //Show Event date

                $("#b_id").innerHTML.replace(moment(event.other.id));
                $('#event-date').html(moment(event.start).format('MMM, D dddd'));
                $('#lblfromTime').html(moment(event.start).format('h:mm A'));
                $('#lbltoTime').html(moment(event.end).format('H:mm A'));

                //Load Event Data To Text Field

                $('#eventIndex').val(event.index);
                $('#txtEventName').val(event.title);
                $('#txtEventCode').val(event.other.code);
                $('#txtEventLocation').val(event.other.location);
            }
        });

        $(document).on("mouseover", ".eventBookingHover", function() {
            let bookingId = $(this).attr("data-id");
            $.ajax({
                "url": "{{ route('user.bookings.get.detail') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "method": "POST",
                data: {
                    id: bookingId,
                },
                success: function(data) {
                    $('#calendar-event').addClass('openHover');
                    $("#calendar-event").html(data.html);
                }
            });
        });

        $(document).on("mouseleave", ".eventBookingHover", function() {
            $('#calendar-event').removeClass('openHover');
        });
    </script>

    <style>
        .event-inner {
            padding: 0 !important;
        }

        .event-title {
            margin: 0 !important;
        }

        .event-title .eventBookingHover {
            display: block;
            width: 100%;
            padding: 7px;
        }

        #calendar-event.openHover {
            transition: all 0.3s;
            -webkit-transform: translate3d(-100%, 0, 0);
            transform: translate3d(-100%, 0, 0);
        }
    </style>
@endsection
