@extends('layouts.new_user')
@section('content')
    <style>
        .week-dragger {
            /* display: none; */
        }

        .months-drager {
            /* display: none; */
        }

        .fresha-calendar-card {
            background: #fff;
            padding-bottom: 60px;
        }

        .calendar .options {
            margin-top: 0;
            padding-top: 25px;
        }

        .calendar .calendar-container .view .tble .trow .tcell {
            padding: 0;
        }

        .calendar .calendar-container .view .tble .thead .tcell .weekdate {
            font-size: 24px;
        }

        .calendar .calendar-container .view .tble .thead .tcell:before {
            width: 100%;
        }

        .calendar .calendar-container .view .tble .thead .tcell {
            padding: 10px 0;
        }

        .fc-header-toolbar.fc-toolbar.fc-toolbar-ltr {
            padding: 0 15px;
            padding-top: 15px;
            display: none;
        }

        .calendar-header {
            display: flex;
            align-content: center;
            justify-content: center;
            padding: 15px;
        }

        .calendar-header .left {
            margin-left: 0;
            margin-right: auto;
        }

        .calendar-header .center {
            margin-left: auto;
            margin-right: auto;
            display: flex;
            align-content: center;
            justify-content: center;
            border: 1px solid #dedede;
            border-radius: 50px;
            overflow: hidden;
        }

        .calendar-header .center h5,
        .calendar-header .center a {
            padding: 5px 15px;
            margin: 0;
            border-right: 1px solid #dedede;
            font-size: 14px;
            color: #333;
            line-height: 28px;
        }

        .calendar-header .right {
            margin-left: auto;
            margin-right: 0;
        }

        #runningDate {
            position: relative;
        }

        .datepicker-cal {
            opacity: 0;
            ;
            position: absolute;
            top: 0;
            z-index: -1;
            left: 0;
        }
    </style>

    <div class="fresha-calendar-card">
        <div class="calendar-header">
            <div class="left">
                <form id="spaceEarningForm" action="<?= route('user.calendar') ?>" method="get">
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
            <div class="center">
                <a href="javascript:;" id="goPrev">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <a href="javascript:;" id="goToday">Today</a>
                <h5 id="runningDate">
                    <span class="dateThing">-</span>
                    <div class="datepicker-cal">
                        <div class="input-group date col-md-12 p-l-0 date-picker-single-component">
                            <input autocomplete="off" type="text"
                                class="form-control datepicker-cal-select from filterField" name="to"
                                id="singlebooking-todate">
                            <div class="input-group-append ">
                                <span class="input-group-text"><i class="pg-icon">calendar</i></span>
                            </div>
                        </div>
                    </div>
                </h5>
                <a href="javascript:;" id="goNext">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
            <div class="right">
                <select name="id" id="calendarView" class="form-control" required>
                    <option value="week">Week</option>
                    <option value="day">Day</option>
                    <option value="month">Month</option>
                </select>
            </div>
        </div>
        <div id='availabilityTimeCalendar'></div>
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

@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/condition.js?_ver=' . config('app.version')) }}"></script>
    <script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.version')) }}"></script>

    {!! App\Helpers\MapEngine::scripts() !!}

    <script>
        let availabilityTimeCalendar = null;
        var eventData = null;

        function showCalendarModal() {
            $("#availabilityCalendar").modal("show");
            availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
                eventSources: [{
                    url: '{{ route('space.vendor.availability.calendarAppointments') }}?id={{ $id }}',
                }],
                initialView: 'timeGridWeek',
                dayMaxEvents: true,
                navLinks: true,
                editable: true,
                headerToolbar: {
                    left: '',
                    center: 'prev,today,title,next',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                datesSet: printCalendarDate,
                eventDrop: function(eventDropInfo) {
                    event = eventDropInfo;
                    const oldData = eventDropInfo.oldEvent._def.extendedProps;
                    const eventData = eventDropInfo.event;
                    $("#scheduleConfirmModal").modal("show");
                    let startMomentDateReal = moment(oldData.other.startDate);
                    let endMomentDateReal = moment(oldData.other.endDate);

                    console.log(eventData);
                    console.log(eventData.start);
                    console.log(eventData.end);

                    let startMomentDate = moment(eventData.start);
                    let endMomentDate = moment(eventData.end);

                    console.log(startMomentDate);
                    console.log(endMomentDate);

                    let startDate = startMomentDate.format("MM/DD/YYYY");
                    let startHour = startMomentDate.format("HH:mm");
                    let startAmpPm = startMomentDate.format("A");

                    let endDate = endMomentDate.format("MM/DD/YYYY");
                    let endHour = endMomentDate.format("HH:mm");
                    let endAmpPm = endMomentDate.format("A");

                    let spaceId = oldData.other.spaceId;

                    $(".bookingId").html("#" + spaceId);

                    $(".scheduleConfirmModalStartDate").html(startDate + ' ' + startHour + ' ' +
                        startAmpPm);
                    $(".scheduleConfirmModalEndDate").html(endDate + ' ' + endHour + ' ' + endAmpPm);
                }
            });
            availabilityTimeCalendar.render();
        }

        showCalendarModal();

        $(document).on("change", "#spaceEarningFormField", function() {
            $("#spaceEarningForm").submit();
        });

        function printCalendarDate() {
            $("#runningDate span.dateThing").html(availabilityTimeCalendar.currentData.viewTitle);
        }

        $(document).on("click", "#goNext", function() {
            availabilityTimeCalendar.next();
        });

        $(document).on("click", "#goToday", function() {
            availabilityTimeCalendar.today();
        });

        $(document).on("click", "#goPrev", function() {
            availabilityTimeCalendar.c();
        });

        $(document).on("change", "#calendarView", function() {
            switch ($(this).val()) {
                case 'week':
                    availabilityTimeCalendar.changeView('timeGridWeek');
                    break;
                case 'day':
                    availabilityTimeCalendar.changeView('timeGridDay');
                    break;
                case 'month':
                    availabilityTimeCalendar.changeView('dayGridMonth');
                    break;
            }
        });

        setTimeout(() => {

            $(document).on("change", ".datepicker-cal-select", function() {
                var obj = $(this);
                var date = moment(obj.val()).format("YYYY-MM-DD");
                availabilityTimeCalendar.gotoDate(date);
            });

            $(document).on("click", "#runningDate span.dateThing", function() {
                console.log("fsdf");
                $(".datepicker-cal-select").datepicker("show");
            });

        }, 1000);

        function reloadCalendarEvents(){
            availabilityTimeCalendar.next();
            availabilityTimeCalendar.prev();
        }

        let SEARCH_AJAX_REQUEST = null;

        $(document).on("click", "#rescheduleYes", function() {
            const oldData = event.oldEvent._def.extendedProps;
            const eventData = event.event;

            let startMomentDateReal = moment(oldData.other.startDate);
            let endMomentDateReal = moment(oldData.other.endDate);

            let startMomentDate = moment(eventData.start);
            let endMomentDate = moment(eventData.end);

            let startDate = startMomentDate.format("MM/DD/YYYY");
            let startHour = startMomentDate.format("HH:mm");
            let startAmpPm = startMomentDate.format("A");

            let endDate = endMomentDate.format("MM/DD/YYYY");
            let endHour = endMomentDate.format("HH:mm");
            let endAmpPm = endMomentDate.format("A");

            let spaceId = oldData.other.spaceId;
            let bookingId = oldData.other.id;

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

        $('#scheduleConfirmModal').on('hidden.bs.modal', function(){
            reloadCalendarEvents();
        });


    </script>
@endsection
