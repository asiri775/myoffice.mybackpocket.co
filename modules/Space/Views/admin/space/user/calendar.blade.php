<?php
if ($row->available_from == null) {
    $row->available_from = '09:00';
}
if ($row->available_to == null) {
    $row->available_to = '17:00';
}
if ($row->first_working_day == null) {
    $row->first_working_day = 'Monday';
}
if ($row->last_working_day == null) {
    $row->last_working_day = 'Friday';
}
?>

<input type="hidden" id="timeZone" name="timezone">
<div class="panel">
    <div class="panel-title">
        <div id="accordion">
            <div class="accordin-card">
                <div class="accordin-card-header" id="headingOne">
                    <a href="javascript:;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false"
                        aria-controls="collapseOne">
                        {{ __('Availability Settings') }}
                    </a>
                </div>
                <div id="collapseOne" class="collapse accordin-card-body" aria-labelledby="headingOne"
                    data-parent="#accordion">
                    <div class="collapse-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="switch-toggle-inline">
                                    <div class="form-group switch-toggle">
                                        <label class="switch">
                                            <input type="checkbox"
                                                {{ old('long_term_rental', $row->long_term_rental) == 1 || old('long_term_rental', $row->long_term_rental) == 'on' ? 'checked' : '' }}
                                                type="checkbox" id="long_term_rental" name="long_term_rental">
                                            <span class="slider round"></span>
                                        </label>
                                        <span for="long_term_rental">Allow Long Term Rental</span>
                                    </div>
                                    <div class="form-group switch-toggle d-none">
                                        <label class="switch">
                                            <input type="checkbox"
                                                {{ old('rapidbook', $row->rapidbook) == 1 || old('rapidbook', $row->rapidbook) == 'on' ? 'checked' : '' }}
                                                type="checkbox" id="rapidbook" name="rapidbook">
                                            <span class="slider round"></span>
                                        </label>
                                        <span for="rapidbook">Rapidbook</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="available_from">Available Hour From</label>
                                    <input value="{{ old('available_from', $row->available_from) }}" step="3600"
                                        type="time" id="available_from" name="available_from"
                                        placeholder="Select Available From" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="available_to">Available Hour To</label>
                                    <input value="{{ old('available_to', $row->available_to) }}" step="3600"
                                        type="time" id="available_to" name="available_to"
                                        placeholder="Select Available To" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first_working_day">First Working Day</label>
                                    <select name="first_working_day" id="first_working_day" class="form-control">
                                        <option value="">Select First Working Day</option>
                                        <?php
                                        foreach (\App\Helpers\Constants::DAYS as $day){
                                        ?>
                                        <option @if (old('first_working_day', $row->first_working_day) == $day) selected="selected" @endif
                                            value="{{ $day }}">{{ $day }}</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="last_working_day">Last Working Day</label>
                                    <select name="last_working_day" id="last_working_day" class="form-control">
                                        <option value="">Select Last Working Day</option>
                                        <?php
                                        foreach (\App\Helpers\Constants::DAYS as $day){
                                        ?>
                                        <option @if (old('last_working_day', $row->last_working_day) == $day) selected="selected" @endif
                                            value="{{ $day }}">{{ $day }}</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12" id="dayStayReq">
                                <div class="form-group">
                                    <label class="control-label">{{ __('Minimum day stay requirements') }}</label>
                                    <input type="number" step="1" name="min_day_stays" class="form-control"
                                        value="{{ old('min_day_stays', $row->min_day_stays) }}"
                                        placeholder="{{ __('Ex: 2') }}">
                                    <i>{{ __('Leave blank if you dont need to set minimum day stay option') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-12" id="hourStayReq">
                                <div class="form-group">
                                    <label class="control-label">{{ __('Minimum hour stay requirements') }}</label>
                                    <input type="number" step="1" name="min_hour_stays" class="form-control"
                                        value="{{ old('min_hour_stays', $row->min_hour_stays) }}"
                                        placeholder="{{ __('Ex: 2') }}">
                                    <i>{{ __('Leave blank if you dont need to set minimum hour stay option') }}</i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="panel">
    <div class="panel-title"><strong>{{ __('Manage Availability') }}</strong></div>
    <div class="panel-body">
        <div style="display: none;">
            <textarea name="block_timings" id="block_timings"></textarea>
        </div>
        <div id='availabilityCalendar'></div>
    </div>
</div>


<div class="modal fade" id="blockTimesModal" tabindex="-1" role="dialog" aria-labelledby="blockTimesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockTimesModalLabel">Confirm Block Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="startTime">Start</label>
                            <input type="text" class="form-control" id="startTime">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="toTime">To</label>
                            <input type="text" class="form-control" id="toTime">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="markAsBlocked" type="button" class="btn btn-primary">Mark as Block</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fc-button {
        text-transform: capitalize !important;
    }

    .fc-event-time {
        display: none;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc-daygrid-event-dot {
        display: none;
    }

    .fc-event {
        background: #ed5959;
        word-break: break-all;
        white-space: normal;
        color: #fff;
        padding: 0 5px !important;
        font-weight: normal !important;
    }

    .fc-blockFullDay-button {
        background: #ed5959 !important;
        border-color: #ed5959 !important;
    }
</style>

<script>
    function toggleDayReq() {
        $("#hourStayReq").hide();
        $("#dayStayReq").hide();
        var longTermRental = $("#long_term_rental:checked").val();
        if (longTermRental != undefined) {
            $("#dayStayReq").show();
        } else {
            $("#hourStayReq").show();
        }
    }
    let blockedTimes = <?= json_encode($blockTimings) ?>;

    let availabilityCalendar = null;
    let startTimeInstance = null;
    let toTimeInstance = null;

    let calendarStart = null;
    let calendarEnd = null;

    function updateBlockTimes() {
        $("#block_timings").html(JSON.stringify(blockedTimes));
    }

    function blockConfirmTimes(startTime, endTime, showModal = true) {
        startTime = new Date(startTime);
        endTime = new Date(endTime);
        if (showModal) {
            $("#blockTimesModal").modal("show");
        }
        if (startTimeInstance != null) {
            $('#startTime').datetimepicker('destroy')
        }
        if (toTimeInstance != null) {
            $('#toTime').datetimepicker('destroy')
        }
        startTimeInstance = $('#startTime').datetimepicker({
            value: startTime,
            format: 'm/d/Y H:i:s',
        });
        toTimeInstance = $('#toTime').datetimepicker({
            value: endTime,
            format: 'm/d/Y H:i:s',
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        availabilityCalendar = new FullCalendar.Calendar(document.getElementById('availabilityCalendar'), {
            events: blockedTimes,
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'blockFullDay dayGridMonth,timeGridWeek,timeGridDay'
            },
            customButtons: {
                blockFullDay: {
                    text: 'Block Entire Day',
                    click: function() {
                        blockConfirmTimes(calendarStart, calendarEnd, false);
                        $("#markAsBlocked").click();
                    }
                },
            },
            datesSet: function(log) {
                calendarStart = log.start;
                calendarEnd = log.end;
                $(".fc-blockFullDay-button").hide();
                if (log.view.type == 'timeGridDay') {
                    $(".fc-blockFullDay-button").show();
                }
            },
            initialView: 'dayGridMonth',
            dayMaxEvents: true,
            navLinks: true,
            selectable: true,
            select: function(selectionInfo) {
                blockConfirmTimes(selectionInfo.start, selectionInfo.end);
            },
            eventClick: function(eventInfo) {
                let eventId = eventInfo.event.id;
                removeEvent(eventInfo.event, eventId);
            }
        });
        availabilityCalendar.render();
    });

    function removeEvent(event, eventId) {
        if (confirm('Are you sure you want to remove this block time?') === true) {
            event.remove();
            blockedTimes = blockedTimes.filter(function(e) {
                return e.id.toString() !== eventId.toString()
            });
            updateBlockTimes();
        }
    }

    function jqueryLoaded() {

        $("#timeZone").val(Intl.DateTimeFormat().resolvedOptions().timeZone);

        toggleDayReq();
        $(document).on("click", "#long_term_rental", function() {
            setTimeout(toggleDayReq, 200);
        });

        updateBlockTimes();

        $(document).on("click", "#markAsBlocked", function() {
            let startTime = $('#startTime').datetimepicker('getValue');
            let endTime = $('#toTime').datetimepicker('getValue');
            if (startTime != null && endTime != null) {
                $("#markAsBlocked").html('Checking Date...');
                var startTimeFormat = moment(startTime).format("YYYY-MM-DD HH:mm:ss");
                var endTimeFormat = moment(endTime).format("YYYY-MM-DD HH:mm:ss");
                $.post('{{ route('space.vendor.availability.confirmBlockDate', $row->id == null ? -1 : $row->id) }}', {
                    start: startTimeFormat,
                    end: endTimeFormat,
                }, function(data) {
                    if (data.status == 'ok') {
                        $("#blockTimesModal").modal("hide");
                        let eventId = 0;
                        if (blockedTimes.length > 0) {
                            eventId = blockedTimes.length;
                        }
                        eventId = eventId.toString();
                        //let eventTitle = moment().format('lll') + " - " + moment().format('lll');
                        let startDateHour = moment(startTime).format("HH:mm");
                        let endDateHour = moment(endTime).format("HH:mm");
                        let eventTitle = startDateHour + ' - ' + endDateHour + ": Blocked";
                        let eventObj = {
                            id: eventId,
                            title: eventTitle,
                            start: startTime,
                            end: endTime,
                        };
                        blockedTimes.push(eventObj);
                        availabilityCalendar.addEvent(eventObj);
                        updateBlockTimes();
                    } else {
                        alert(data.message);
                    }
                    $("#markAsBlocked").html('Mark as Block');
                });
            } else {
                alert("Start and End time both are required");
            }
        });


    }


    let jqueryCheckTimer = null;

    jqueryCheckTimer = setInterval(e => {
        if (window.jQuery) {
            clearInterval(jqueryCheckTimer);
            jqueryLoaded();
        }
    }, 500);
</script>
