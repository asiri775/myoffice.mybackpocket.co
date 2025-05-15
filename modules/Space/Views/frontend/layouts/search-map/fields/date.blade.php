<?php
$startDate = Request::query('start');
if ($startDate == null) {
    $startDate = display_date(strtotime("today"));
}
$endDate = Request::query('end');
if ($endDate == null) {
    $endDate = display_date(strtotime("today"));
}
$fromHour = Request::query('from_hour');
if ($fromHour == null) {
    $fromHour = "00:00";
}
$toHour = Request::query('to_hour');
if ($toHour == null) {
    $toHour = "23:59";
}
$startDate = $startDate . " " . $fromHour . ":00";
$endDate = $endDate . " " . $toHour . ":59";
?>
<div class="filter-item">
    <div class="filterinp">
        <div class="form-group form-date-field form-date-search clearfix  has-icon">
            <i class="field-icon icofont-wall-clock"></i>
            <div class="date-wrapper clearfix">

                <div id="reportrange" class="check-in-wrapper d-flex align-items-center">
                    <span>Select Your Dates</span>
                </div>


                <input type="hidden" class="check-in-input"
                       value="{{date('m/d/Y', strtotime($startDate))}}" name="start"
                       id="startDateVal">
                <input type="hidden" class="check-out-input"
                       value="{{date('m/d/Y', strtotime($endDate))}}" name="end" id="endDateVal">

                <input type="hidden" id="startTimeVal" name="from_hour" value="{{date('H:i', strtotime($startDate))}}"/>
                <input type="hidden" id="endTimeVal" name="to_hour" value="{{date('H:i', strtotime($endDate))}}"/>

                <input type="hidden" id="startTimeFull" name="start_time"
                       value="{{date('Y-m-d H:i', strtotime($startDate))}}"/>
                <input type="hidden" id="endTimeFull" name="end_time"
                       value="{{date('Y-m-d H:i', strtotime($endDate))}}"/>

            </div>
        </div>
    </div>
</div>
