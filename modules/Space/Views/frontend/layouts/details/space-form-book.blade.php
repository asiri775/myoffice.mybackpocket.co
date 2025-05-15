<?php
//dd($row);

$startTime = null;
$endTime = null;

$showTimingOption = false;

if ($row->available_from == null) {
    $row->available_from = '00:00';
}

if ($row->available_to == null) {
    $row->available_to = '23:59';
}

if ($row->long_term_rental == 1) {
    // $showTimingOption = false;
    $startTime = $row->available_from;
    $endTime = $row->available_to;
}
//below was before in else condition now move into true so always worked
if (true) {
    if (isset($_GET['start_hour']) && trim($_GET['start_hour']) != null && trim($_GET['start_hour'])) {
        $startTime = trim($_GET['start_hour']);
    }

    if (isset($_GET['to_hour']) && trim($_GET['to_hour']) != null && trim($_GET['to_hour'])) {
        $endTime = trim($_GET['to_hour']);
    }

    if (isset($_GET['start']) && $_GET['start'] != null) {
        $startDate = trim($_GET['start']);
        if ($startDate == date('Y-m-d')) {
            $startTimeData = date('H:i');
            if ($startTimeData > $startTime) {
                $startTimeDataExploded = explode(':', $startTimeData);
                if ($startTimeDataExploded[1] > 0) {
                    $startTimeHR = $startTimeDataExploded[0];
                    $startTimeHR = $startTimeHR + 1;
                    if (strlen($startTimeHR) == 1) {
                        $startTimeHR = '0' . $startTimeHR;
                    }
                    $nextNearestHour = $startTimeHR . ':00';
                    if ($nextNearestHour < $row->available_to) {
                        $startTime = $nextNearestHour;
                    }
                } else {
                    $startTime = $startTimeData;
                }
            }
        }
    }
}

if ($row->min_hour_stays != null && $startTime != null && $endTime == null) {
    $startTimeExploded = explode(':', $startTime);
    $startTimeHR = trim($startTimeExploded[0]);
    if ($startTimeHR > 0) {
        $startTimeHR = $startTimeHR + $row->min_hour_stays;
        $endTime = $startTimeHR . ':00';
    }
}

if ($startTime != null) {
    if ($startTime < $row->available_from) {
        $startTime = $row->available_from;
    }
    if ($startTime > $row->available_to) {
        $startTime = $row->available_from;
    }
}

if ($endTime != null) {
    if ($endTime < $row->available_from) {
        $endTime = $row->available_to;
    }
    if ($endTime > $row->available_to) {
        $endTime = $row->available_to;
    }
}

if ($startTime != null && $endTime != null) {
    $startTimeExploded = explode(':', $startTime);
    $endTimeExploded = explode(':', $endTime);
    $startTimeHR = trim($startTimeExploded[0]);
    $endTimeHR = trim($endTimeExploded[0]);
    if ($startTimeHR > 0 && $endTimeHR > 0) {
        $diffHour = $endTimeHR - $startTimeHR;
        if ($diffHour < $row->min_hour_stays) {
            $startTimeHR = $startTimeHR + $row->min_hour_stays;
            $endTime = $startTimeHR . ':00';
        }
    }
}

$timesNotAvailable = $row->getTimesNotAvailable();
$allDayTimeSlots = \App\Helpers\Constants::getTimeSlots();

$startEndDate = '';

$startDate = date('m/d/Y');
$toDate = date('m/d/Y');

if (isset($_GET['start']) && isset($_GET['end'])) {
    if (!empty(trim($_GET['start'])) && !empty(trim($_GET['end']))) {
        $startDate = $_GET['start'];
        $toDate = $_GET['end'];
        $startEndDate = date('m/d/Y', strtotime(trim($_GET['start']))) . ' - ' . date('m/d/Y', strtotime(trim($_GET['end'])));
    }
}

$start_hour_state = null;
$end_hour_state = null;

if ($startTime != null) {
    $start_hour_state = \App\Helpers\CodeHelper::getAMPMFromHourTime($startTime);
    $startTime = \App\Helpers\CodeHelper::getSmallMinTimeFromHourTime($startTime);
}

if ($endTime != null) {
    $end_hour_state = \App\Helpers\CodeHelper::getAMPMFromHourTime($endTime);
    $endTime = \App\Helpers\CodeHelper::getSmallMinTimeFromHourTime($endTime);
}

//$showTimingOption = true;
$spacePriceData = \App\Helpers\CodeHelper::spacePriceData($row);

if (array_key_exists('hourly', $spacePriceData['prices'])) {
    $showTimingOption = true;
} else {
    if ($startTime == null) {
        $startTime = '00:00';
    }
    if ($endTime == null) {
        $endTime = '23:59';
    }
}

//not using PHP, as it take server time not local

// $currentTime = date('H:i');

// $addNextTime = false;
// if ($startTime == null && $endTime == null) {
//     $addNextTime = true;
// } else {
//     $startDateTime = new DateTime($startTime);
//     $currentDateTime = new DateTime($currentTime);
//     if ($currentDateTime > $startDateTime) {
//         $addNextTime = true;
//     }
// }

// if ($addNextTime) {
//     $startDateTime = new DateTime($currentTime);
//     $startDateTime->modify('+1 hour');
//     $startTime = $startDateTime->format('H:00');
// }

// $startDateTime = new DateTime($startTime);
// $endDateTime = new DateTime($endTime);

// if ($startDateTime > $endDateTime || $endTime == null) {
//     $endDateTime = new DateTime($startTime);
//     $endDateTime->modify('+1 hour');
//     $endTime = $endDateTime->format('H:00');
// }

// echo $currentTime;die;

?>
<style>
    #viewAvailabilityCalendar {
        text-align: center;
        display: block;
        width: 100%;
        color: rgb(81, 145, 250);
        margin-bottom: 15px;
        font-weight: 600;
    }

    #viewAvailabilityCalendar:hover {
        color: rgb(25, 93, 250);
        text-decoration: none;
    }

    .fc-button {
        text-transform: capitalize !important;
    }

    .fc-event-time {
        display: none;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc-event-title {
        word-break: break-all;
        white-space: normal;
    }

    #alreadyBookedFor {
        display: none;
    }

    #alreadyBookedFor label {
        color: #333;
        font-size: 13px;
    }

    #alreadyBookedFor ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    #alreadyBookedFor ul li {
        font-size: 13px;
        font-weight: 400;
    }

    #availabilityTimeCalendar .fc-v-event,
    #availabilityTimeCalendar .fc-event {
        background: #ed5959;
        border-color: #ed5959;
        text-align: center;
        color: #fff;
    }

    #availabilityTimeCalendar .fc-daygrid-event-dot {
        display: none;
    }

    #availabilityTimeCalendar .fc-more-link {
        background: #ed5959;
        word-break: break-all;
        white-space: normal;
        color: #fff;
        padding: 5px;
        border-radius: 5px;
    }

    #availabilityTimeCalendar .fc-event {
        background: #ed5959;
        word-break: break-all;
        white-space: normal;
        color: #fff;
        padding: 0 5px !important;
        font-weight: normal !important;
    }

    #spaceBookBtn.disabled {
        opacity: 0.3;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        right: 10px;
    }

    .detailcalndericon {
        left: 10px;
    }

    .promotion-pro-box * {
        font-family: 'Montserrat', sans-serif !important;
    }

    .inputs-book-form-guests {
        text-align: center;
        display: block;
        align-items: center;
        justify-content: center;
    }

    .inputs-book-form-guests input {
        max-width: 65px;
    }

    .inputs-book-form-guests .form-group.flex {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="promotion promotion-pro-box mt-5">
    <div class="bg-overlay"></div>
    <div class="pro-card promotion-pro-head">
        <div class="left">
            <div class="pro-card">
                <h1>${{ \App\Helpers\CodeHelper::getNumValueOrDefault($spacePriceData['discountedPrice'], $spacePriceData['price']) }}/{{ \App\Helpers\CodeHelper::shortNameForPriceType($spacePriceData['priceType']) }}
                </h1>
                @if (\App\Helpers\CodeHelper::checkIfNumValNotNull($spacePriceData['discountRate']))
                    <h2>${{ $spacePriceData['price'] }}/{{ \App\Helpers\CodeHelper::shortNameForPriceType($spacePriceData['priceType']) }}
                    </h2>
                @endif
            </div>
        </div>
        @if (\App\Helpers\CodeHelper::checkIfNumValNotNull($spacePriceData['discountRate']))
            <div class="right">
                <span class="arrow right"
                    style="font-family: 'Montserrat">{{ round($spacePriceData['discountRate'], 0) }}%
                    OFF!</span>
            </div>
        @endif
    </div>
    <div class="promotion-pro-body">
        @if (count($spacePriceData['prices']) >= 2)
            <h3>Book Longer and Save More</h3>
        @else
            <h3>Book Now</h3>
        @endif
        <div class="row pro-list">
            <?php
                    foreach ($spacePriceData['prices'] as $key => $priceData) {
                        if($key != $spacePriceData['priceType']){
                        ?>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 mb-3">
                <div class="pro-div">
                    <h2>${{ \App\Helpers\CodeHelper::getNumValueOrDefault($priceData['discountedPrice'], $priceData['price']) }}
                    </h2>
                    <p>{{ $key }}</p>
                </div>
            </div>
            <?php
                        }
                    }
                    ?>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="date-select mt-2" style="background: url('../images/booking.png')" ;="">
    <div class="book-overlay">
        <div class="detailsbooking fulwidthm left">
            <span class="bookingspan">booking date and time</span>
            <div class="input-daterange" id="datepicker">
                <div class="detailformrow inputs-book-form-items mgnB15 left fulwidthm flex justifycenter ">
                    <div class="bookingmark">
                        <img alt="from date" src="{{ asset('') }}/images/icons/svg/015-planedown.svg"
                            class="planesvg">
                    </div>
                    <div class="elements">
                        <div
                            class="detailformrow mgnB15 left fulwidthm @if (!$showTimingOption) non-time @endif">
                            <div class="dateInputC">
                                <span class="detailcalndericon"><i class=" fa fa-calendar"></i></span>
                                <input id="dpd1x1" class="start_date fulwidthm text-left whitebg detailsinput"
                                    name="start" placeholder="Check In" width="120" type="text"
                                    value="{{ $startDate }}" readonly>
                            </div>
                            <div class="timeInputC"
                                style="@if (!$showTimingOption) display:none !important; @endif">
                                <select class="selectsearch" style="width:100%;" name="start_hour" id="start_hour">
                                    <option value="">Time</option>
                                    @foreach ($allDayTimeSlots as $slot)
                                        @if ($slot >= $row->available_from && $slot <= $row->available_to)
                                            <option @if ($startTime == $slot) selected @endif
                                                value="{{ $slot }}">
                                                {{ $slot }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="whenInputC"
                                style="@if (!$showTimingOption) display:none !important; @endif">
                                <select class="selectsearch"
                                    style="width:100%; @if (!$showTimingOption) display:none !important; @endif"
                                    name="start_ampm" id="start_ampm">
                                    <option value="AM" {{ $start_hour_state == 'AM' ? 'selected' : '' }}>AM
                                    </option>
                                    <option value="PM" {{ $start_hour_state == 'PM' ? 'selected' : '' }}>PM
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detailformrow inputs-book-form-items mgnB15 left fulwidthm flex justifycenter ">
                    <div class="bookingmark">
                        <img alt="to date" src="{{ asset('') }}/images/icons/svg/016-planeup.svg"
                            class="planesvg planeborder">
                    </div>
                    <div class="elements">
                        <div
                            class="detailformrow mgnB15 left fulwidthm  @if (!$showTimingOption) non-time @endif">
                            <div class="dateInputC">
                                <span class="detailcalndericon"><i class=" fa fa-calendar"></i></span>
                                <input id="dpd2x2" width="120"
                                    class="end_date fulwidthm  text-left whitebg detailsinput" name="end"
                                    placeholder="Check Out" type="text" value="{{ $toDate }}" readonly>
                            </div>
                            <div class="timeInputC"
                                style="@if (!$showTimingOption) display:none !important; @endif">
                                <select class="selectsearch" style="width:100%;" name="end_hour" id="end_hour">
                                    <option value="">Time</option>
                                    @foreach ($allDayTimeSlots as $slot)
                                        @if ($slot >= $row->available_from && $slot <= $row->available_to)
                                            <option @if ($endTime == $slot) selected @endif
                                                value="{{ $slot }}">
                                                {{ $slot }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="whenInputC"
                                style="@if (!$showTimingOption) display:none !important; @endif">
                                <select class="selectsearch"
                                    style="width:100%; @if (!$showTimingOption) display:none !important; @endif"
                                    name="end_ampm" id="end_ampm">
                                    <option value="AM" {{ $end_hour_state == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ $end_hour_state == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detailformrow left fulwidthm avl-cal" style="text-align: center">
                    <a href="javascript:;" id="openCalendar">View Full Availability Calendar</a>
                </div>
            </div>
            <div class="clearfix"></div>

            <div id="msgx-error" align="left" class="alert alert-danger" style="display:none;"></div>
            <div id="msgx-success" align="left" class="alert alert-success" style="display:none;"></div>
            <div id="loading-image" align="center" style="display:none;">
                <img src="{{ asset('') }}/images/loading.gif" width="100px">
            </div>

        </div>

        <div class="extra-box">
            @if ($booking_data['extra_price'])
                <div class="form-section-group form-group d-none" v-if="extra_price.length">
                    <h4 class="form-section-title">{{ __('Extra prices:') }}</h4>
                    <div class="form-section-group form-group">
                        @foreach ($booking_data['extra_price'] as $extra_price)
                            <div class="extra-price-wrap d-flex justify-content-between">
                                <label>
                                    <input type="checkbox" name="extra_price" value="{{ $extra_price['price'] }}"
                                        true-value="1" false-value="0" v-model="type.enable">
                                    {{ $extra_price['name'] }}
                                    <i data-toggle="tooltip" data-placement="top" title=""
                                        class="icofont-info-circle"
                                        data-original-title="This helps us run our platform and offer services like 24/7 support on your trip."></i>
                                </label>
                                <div class="flex-shrink-0">{{ $extra_price['price_html'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if ($booking_data['buyer_fees'])
                <div class="form-section-group form-group d-none">
                    @foreach ($booking_data['buyer_fees'] as $buyer_fees)
                        <div class="extra-price-wrap d-flex justify-content-between">
                            <label>{{ $buyer_fees['type_name'] }}
                                <i data-toggle="tooltip" data-placement="top" title=""
                                    class="icofont-info-circle"
                                    data-original-title="This helps us run our platform and offer services like 24/7 support on your trip."></i>
                            </label>
                            <div class="flex-shrink-0">${{ $buyer_fees['price'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="form-section-group form-group">
                <div class="form-section-group inputs-book-form-guests form-group" style="padding-bottom: 0;">
                    <span class="bookingspan" style="margin-bottom: 0;">NO. OF GUESTS:</span>

                    <div class="form-group flex">
                        <img alt="guest" src="{{ asset('') }}/icon/man.png" style="width: 40px;">
                        <input type="text"
                            placeholder="@if ($row->max_guests <= 1) {{ __('Maximum Capacity: :count Guest', ['count' => $row->max_guests]) }} @else {{ __('Maximum Capacity: :count Guests', ['count' => $row->max_guests]) }} @endif"
                            id="adultsFieldItem" class="form-control" required name="adults" value="1">
                    </div>
                </div>
                <div class="submit-group">
                    <a href="javascript:;" id="spaceBookBtn" name="submit" style="color: #fff !important;"
                        class="btn btn-large"><span>BOOK
                            NOW</span></a>
                </div>
            </div>
        </div>

    </div>
</div>




<div id="availabilityCalendar" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Availability Calendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;padding: 15px;font-size: 17px;font-weight: 600;">Click on the Date to see
                    the full availability for that date</p>
                <div id='availabilityTimeCalendar'></div>
            </div>
        </div>
    </div>
</div>

<script>
    let blockedTimes = <?= json_encode($timesNotAvailable) ?>;

    let availabilityTimeCalendar = null;

    function updateTimeToCurrent() {
        const minHourTime = "{{ $row->available_from }}";
        const maxHourTime = "{{ $row->available_to }}";

        let startHour = $("#start_hour").val();
        if (startHour == '') {
            $("#start_hour").val(minHourTime);
            $("#end_hour").val(maxHourTime);
        }


        const [maxHourTimeHour, maxHourTimeMin] = maxHourTime.split(':');
        startHour = $("#start_hour").val();
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinutes = now.getMinutes();
        const [startHourValue, startMinuteValue] = startHour.split(':');
        const startHourInt = parseInt(startHourValue, 10);
        if (currentHour >= startHourInt) {
            if ((currentHour + 1) < maxHourTimeHour) {
                startHour = (currentHour + 1) + ":00";
            } else {
                var startDate = new Date($("#dpd1x1").val()).getTime();
                var todayDate = new Date().setHours(0, 0, 0, 0);
                if (startDate <= todayDate) {
                    $("#dpd1x1").val("<?= date('m/d/Y', strtotime('+1 day')) ?>").trigger("change");
                    $("#dpd2x2").val("<?= date('m/d/Y', strtotime('+1 day')) ?>").trigger("change");
                }
            }
        }
        $("#start_hour").val(startHour);
    }

    updateTimeToCurrent();

    function showAvailabilityCalendarModal() {
        $("#availabilityCalendar").modal("show");
        availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
            eventSources: [{
                url: '{{ route('space.vendor.availability.availableDates') }}?id={{ $row->id }}',
            }],
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            dayMaxEvents: true,
            navLinks: true,
            eventClick: function(eventInfo) {
                let eventId = eventInfo.event.id;
            },
            eventContent: function(arg) {
                return {
                    html: `<div class="fc-event-title">${arg.event.title.replace(/<\/br>/g, '<br>')}</div>`
                };
            }
        });
        availabilityTimeCalendar.render();
    }

    function showNotification(message, type = "error") {
        window.webAlerts.push({
            type: type,
            message: message
        });
        // switch (type) {
        //     case "error":
        //         toastr.error(message);
        //         $("#msgx-error").html(message);
        //         break;
        //     case "success":
        //         toastr.success(message);
        //         break;
        //     default:
        //         toastr.info(message);
        //         break;
        // }
    }

    let SEARCH_AJAX_REQUEST = null;

    function checkTimeAvailability(showAlerts = true, processAddToCart = false) {
        let checkAvailability = true;

        let startDate = $('#dpd1x1').val().toString().trim();
        if (startDate === '') {
            checkAvailability = false;
        }

        let endDate = $('#dpd2x2').val().toString().trim();
        if (endDate === '') {
            checkAvailability = false;
            if (startDate !== '') {
                $('#dpd2x2').val(startDate);
            }
        }

        let startHour = $('select[name="start_hour"]').val().toString().trim();
        if (startHour === '') {
            checkAvailability = false;
        }

        let endHour = $('select[name="end_hour"]').val().toString().trim();
        if (endHour === '') {
            checkAvailability = false;
        }

        let startAmpPm = $("#start_ampm option:selected").val().toString().trim();
        if (startAmpPm === '') {
            checkAvailability = false;
        }

        let endAmpPm = $("#end_ampm option:selected").val().toString().trim();
        if (endAmpPm === '') {
            checkAvailability = false;
        }

        if (checkAvailability) {

            if (SEARCH_AJAX_REQUEST != null) {
                SEARCH_AJAX_REQUEST.abort();
                SEARCH_AJAX_REQUEST = null;
            }


            $("#alreadyBookedFor").hide();
            $("#msgx-error").hide();
            $("#msgx-success").hide();
            $('#loading-image').hide();
            // $('#spaceBookBtn').addClass('disabled');
            $("#start_hour option:selected").val();

            $("#spaceCalPrice").hide();

            $("#spaceBookBtn").html("Book Now");

            SEARCH_AJAX_REQUEST = $.post("{{ route('space.vendor.availability.verifySelectedTimes') }}", {
                id: {{ $row->id }},
                start_date: startDate,
                end_date: endDate,
                start_hour: startHour,
                end_hour: endHour,
                start_ampm: startAmpPm,
                end_ampm: endAmpPm,
            }, function(response) {
                if (response.status == 'error') {
                    $('#loading-image').hide();
                    if (showAlerts) {
                        showNotification(response.message);
                        $("#msgx-error").html(response.message).show();

                    }
                } else if (response.status == 'success') {
                    $('#loading-image').hide();
                    if (showAlerts) {
                        // showNotification('Space is available', 'success');
                    }
                    $('#spaceBookBtn').removeClass('disabled');
                    $("#spaceCalPrice").show().find("span").html(response.priceFormatted);
                    $("#spaceBookBtn").html("Book Now - " + response.priceFormatted);
                    if (processAddToCart) {
                        addToCart();
                    }

                }
                if (response.bookings && response.bookings.length > 0) {
                    $('#loading-image').hide();
                    $("#alreadyBookedFor").show();
                    $("#alreadyBookedFor ul").html('');
                    for (let alreadyBooked of response.bookings) {
                        $("#alreadyBookedFor ul").append('<li>' + alreadyBooked + '</li>');
                    }
                }
            });

        }

    }

    $(document).on("click", "#spaceBookBtn", function() {
        checkTimeAvailability(true, true);
    });

    function addToCart(showAlerts = true) {
        var extraPrices = [];

        $('input[name=extra_price]:checked').map(function() {
            extraPrices.push($(this).val());
        });

        $("#msgx-error").hide();

        let startDate = $('#dpd1x1').val().toString().trim();
        let endDate = $('#dpd2x2').val().toString().trim();
        let startHour = $('select[name="start_hour"]').val().toString().trim();
        let endHour = $('select[name="end_hour"]').val().toString().trim();
        let startAmpPm = $("#start_ampm option:selected").val().toString().trim();
        let endAmpPm = $("#end_ampm option:selected").val().toString().trim();

        @if (Auth::check())
        @else
            let currentUrl = "{{ Request::url() }}";
            let loginRedirectUrl = "{{ route('auth.redirectLogin') }}";
            let queryData = {
                start_hour: startHour,
                to_hour: endHour,
                start: startDate,
                end: endDate,
            };
            let queryParams = "";
            for (let queryKey in queryData) {
                queryParams += queryKey + "=" + queryData[queryKey] + "&";
            }
            queryParams = queryParams.slice(0, -1);
            let currentUrlPath = encodeURIComponent((currentUrl + '?' + queryParams));
            //console.log(currentUrlPath);
            loginRedirectUrl = loginRedirectUrl + '?redirect=' + currentUrlPath;
            //console.log(loginRedirectUrl);
            // window.location.href = loginRedirectUrl;
        @endif

        var totalAduts = $("#adultsFieldItem").val().toString().trim();
        if (totalAduts == '') {
            showNotification("Enter number of Guests");
            return false;
        }

        totalAduts = totalAduts * 1;

        $.post("{{ route('booking.addToCart') }}", {
            service_id: {{ $row->id }},
            service_type: "space",
            start_date: startDate,
            end_date: endDate,
            start_ampm: startAmpPm,
            end_ampm: endAmpPm,
            start_hour: startHour,
            end_hour: endHour,
            extra_price: extraPrices,
            adults: totalAduts
        }, function(response) {
            console.log(response);
            if (response.status == 0) {
                if (showAlerts) {
                    showNotification(response.message);
                }
            } else if (response.status == 1) {
                window.location.href = response.url;
            }
        }).fail(function(response) {
            response = response.responseJSON;
            showNotification(response.message);
        });

    }

    function jqueryLoaded() {
        $('.submit-group a[name="submit"]').attr("id", "spaceBookBtn");

        $(document).on("click", "#openCalendar", function() {
            showAvailabilityCalendarModal();
        });

        $("#alreadyBookedFor").hide();

        $(document).on("change", 'input[name="start"]', function() {
            checkTimeAvailability(false);
        });

        $(document).on("change", 'input[name="end"]', function() {
            checkTimeAvailability(false);
        });

        $(document).on("change", 'select[name="start_hour"]', function() {
            checkTimeAvailability(false);
        });

        $(document).on("change", 'select[name="end_hour"]', function() {
            checkTimeAvailability(false);
        });

        $(document).on("change", 'select[name="start_ampm"]', function() {
            checkTimeAvailability(false);
        });

        $(document).on("change", 'select[name="end_ampm"]', function() {
            checkTimeAvailability(false);
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

<script type="text/javascript">
    $(document).ready(function(e) {

        $(".formselect").select2();

        $(".selectsearch, .footerselect").select2();

        wow = new WOW({
            animateClass: 'animated',
            mobile: false,

            offset: 100
        });
        wow.init();
        // slect style

    });
</script>

<script type="text/javascript" src="{{ asset('js/datepikernew.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var userloginstat = '0';

        if (userloginstat == 0) {
            $(".signinbtn").click();

        }

        $('#tempsend').hide();
        $('#tempmsg').show();
        $('#tempeff').hide();
        $('#temperror').show();
        var today = new Date();
        var dd = today.getDate() + 1;
        var mm = today.getMonth() + 1;
        //January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        today = mm + '/' + dd + '/' + yyyy;
        var temp = '';
        // alert(temp);

        var blockedarray = temp.split('#');

        // alert(blockedarray);
        $('.input-daterange input').datepicker({
            clearBtn: true,
            autoclose: true,
            startDate: '+0d'
        });

        $("#accounmentationGuests").on("keyup", function() {
            getPricingOfListing();
        });

        function getPricingOfListing() {

            $("#temperror").show().html("");
            //validate guest count
            var maxiGuestAllo = 1;
            maxiGuestAllo = maxiGuestAllo * 1;


            var fromval = $('#dpd1x1').val();
            var toval = $('#dpd2x2').val();
            $('#listselprice').hide();
            if (fromval == toval && fromval != "" && toval != "") {
                //$('#dpd2x2').focus();

            }

            var flag = 0;
            if (fromval != "") {

                var fromtimestamp = new Date(fromval).getTime();

            } else {

                flag = 4;
            }
            if (toval != "") {

                var totimestamp = new Date(toval).getTime();

            } else {

                flag = 4;
            }
            var tdaytimestamp = new Date(today).getTime();

            /*if (totimestamp == fromtimestamp) {
                flag = 2;

            }*/

            var fromvalnew = $('#dpd1x1').val();
            var tovalnew = $('#dpd2x2').val();
            var newtimestampfrom = new Date(fromvalnew).getTime();
            var newtimestampto = new Date(tovalnew).getTime();
            var requestGutestor = $("#accounmentationGuests").val();
            requestGutestor = requestGutestor * 1;
            var guestselect = requestGutestor;
            if (requestGutestor > maxiGuestAllo) {
                $("#tempeff").hide();
                $("#temperror").show().html("Maximum number of guests allowed are " + maxiGuestAllo + ".");
                return false;
            } else if (flag == 1) {
                $('#tempeff').hide();
                $('#msgx').html('Those dates are not available');
                //alert('Those dates are not available');
                $('#temperror').show();
                //$('#dpd1x1').focus();
            } else if (flag == 2) {
                $('#tempeff').hide();
                $('#msgx').html('Check In and Check Out Cannot be on Same Day');
                $('#temperror').show();
                $('#dpd2x2').trigger("click");
            } else if (flag == 4) {
                $('#tempeff').hide();
                $('#msgx').html('Select a Check In and Check out date');
                $('#temperror').show();
            } else {
                var start_time = $("#start_time").val();
                var start_ampm = $("#start_ampm").val();
                var end_time = $("#end_time").val();
                var end_ampm = $("#end_ampm").val();
                var time_array = [{
                    url: window.location.href,
                    'indate': fromvalnew,
                    'outdate': tovalnew,
                    'in_start_time': start_time,
                    'in_start_ampm': start_ampm,
                    'out_start_time': end_time,
                    'out_start_ampm': end_ampm,
                    'guestselect': guestselect
                }];
                localStorage.setItem('time_array', JSON.stringify(time_array));
            }
        }


        $(document).on("change", "#start_time,#start_ampm,#end_time,#end_ampm", function() {
            getPricingOfListing();
        })


        $('.input-daterange input').each(function() {
            $(this).on('changeDate', function() {
                getPricingOfListing();
            });

            $(this).on('clearDate', function() {
                var id = $(this).parent('div').parent('div').attr('id');
                if (id == "datepicker") {
                    $('#listselprice').hide();
                    $('#tempeff').hide();
                    $('#msgx').html('Select a Check In and Check out date');
                    $('#temperror').show();
                }

                //$('#dpd2x2').focus();
                if (id == "datepickercontact") {
                    $('#tempsend').hide();
                    $('#msgy').html(
                        'Choose a Check In and Check Out date & type in the Message you want to Send! '
                    );
                    $('#tempmsg').show();
                }

            });

        });

        //check local storage for already present values
        if (localStorage.getItem("time_array") !== null) {
            timesArray = JSON.parse(localStorage["time_array"]);
            timeArrayKey = timesArray[0];
            console.log(timesArray);
            if (timeArrayKey['url'] == window.location.href) {
                if (timeArrayKey['indate'] != null) {
                    $("#dpd1x1").val(timeArrayKey['indate']);
                }
                if (timeArrayKey['outdate'] != null) {
                    $("#dpd2x2").val(timeArrayKey['outdate']);
                }

                if (timeArrayKey['in_start_time'] != '') {
                    var vald = timeArrayKey['in_start_time'];
                    $("#start_time").val(vald);
                    $("#select2-start_time-container").html(vald).attr("title", vald);
                } else {
                    $("#start_time").val("12:00");
                    $("#select2-start_time-container").html("12:00").attr("title", "12:00");
                }

                if (timeArrayKey['out_start_time'] != '') {
                    var vald = timeArrayKey['out_start_time'];
                    $("#end_time").val(vald);
                    $("#select2-end_time-container").html(vald).attr("title", vald);
                } else {
                    $("#end_time").val("11:30");
                    $("#select2-end_time-container").html("11:30").attr("title", "11:30");
                }

                if (timeArrayKey['in_start_ampm'] != '') {
                    var vald = timeArrayKey['in_start_ampm'];
                    $("#start_ampm").val(vald);
                    $("#select2-start_ampm-container").html(vald).attr("title", vald);
                } else {
                    $("#start_ampm").val("AM");
                    $("#select2-start_ampm-container").html("AM").attr("title", "AM");
                }

                if (timeArrayKey['out_start_ampm'] != '') {
                    var vald = timeArrayKey['out_start_ampm'];
                    $("#end_ampm").val(vald);
                    $("#select2-end_ampm-container").html(vald).attr("title", vald);
                } else {
                    $("#end_ampm").val("PM");
                    $("#select2-end_ampm-container").html("PM").attr("title", "PM");
                }

                if (timeArrayKey['guestselect'] != '') {
                    var vald = timeArrayKey['guestselect'];
                    $("#accounmentationGuests").val(vald);
                }


                getPricingOfListing();
            } else {
                //console.log("Fsdf");
                $("#start_time").val('10:00');
                $("#select2-start_time-container").html('10:00').attr("title", '10:00');
                $("#end_time").val('1:00');
                $("#select2-end_time-container").html('1:00').attr("title", '1:00');
                $("#start_ampm").val('AM');
                $("#select2-start_ampm-container").html('AM').attr("title", 'AM');
                $("#end_ampm").val('PM');
                $("#select2-end_ampm-container").html('PM').attr("title", 'PM');
            }

        }

    });
</script>
