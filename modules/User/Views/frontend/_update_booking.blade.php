<div class="modal fade" id="updateSingleBookingModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('booking.updateSingleBooking') }}" method="post">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="single-booking-update-title">{{ __('Update Booking') }}</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body pt-3">
                    <input type="hidden" name="id" id="singlebooking-id">
                    <input type="hidden" id="singlebooking-spaceid">

                    <div class="alert alert-danger" style="display: none;" id="updateSingleAlert"></div>

                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="">Booking From</label>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div
                                                    class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                                    <input type="text"
                                                        class="form-control from filterField chksignleupdateprice2"
                                                        name="from" id="singlebooking-fromdate">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text"><i
                                                                class="pg-icon">calendar</i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="time" name="from_time" id="singlebooking-fromtime"
                                                    class="form-control chksignleupdateprice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="">Booking To</label>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div
                                                    class="input-group date col-md-12 p-l-0 date-picker-single-component">
                                                    <input type="text"
                                                        class="form-control chksignleupdateprice2 from filterField"
                                                        name="to" id="singlebooking-todate">
                                                    <div class="input-group-append ">
                                                        <span class="input-group-text"><i
                                                                class="pg-icon">calendar</i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="time" name="to_time" id="singlebooking-totime"
                                                    class="form-control chksignleupdateprice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" id="singlebooking-status"
                                            class="form-control chksignleupdateprice">
                                            <option value="">Select Status</option>
                                            @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                                <option value="{{ $k }}">{{ $txt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="">Guest</label>
                                        <input type="number" name="guest" min="1"
                                            class="form-control chksignleupdateprice" id="singlebooking-guest"
                                            placeholder="Enter number of guests">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3 col-xs-12 col-12">
                            <h5>Pricing</h5>
                            <div id="singleUpdatePricing">
                                <p>Change any value to view updated pricing</p>
                                <div id="singleBookingPriceInfo"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button class="btn btn btn-primary" id="singleUpdateBookingBtn"
                        type="submit">{{ __('Update') }}</button>
                    <span class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let SEARCH_AJAX_REQUEST = null;

    function fetchSingleBookingUpdatePricings() {
        var submitBtn = $("#singleUpdateBookingBtn");
        submitBtn.attr("disabled", true).html("Please wait...");

        let checkAvailability = true;

        let startDate = $('#singlebooking-fromdate').val().toString().trim();
        if (startDate === '') {
            checkAvailability = false;
        }

        let endDate = $('#singlebooking-todate').val().toString().trim();
        if (endDate === '') {
            checkAvailability = false;
        }

        let startHour = $('#singlebooking-fromtime').val().toString().trim();
        if (startHour === '') {
            checkAvailability = false;
        }

        let endHour = $('#singlebooking-totime').val().toString().trim();
        if (endHour === '') {
            checkAvailability = false;
        }

        let spaceId = $("#singlebooking-spaceid").val();
        let bookingId = $("#singlebooking-id").val();

        let startDateMoment = moment(startDate + " " + startHour);
        let endDateMoment = moment(endDate + " " + endHour);

        startDate = startDateMoment.format("MM/DD/YYYY");
        startHour = startDateMoment.format("HH:mm");
        let startAmpPm = startDateMoment.format("A");

        endDate = endDateMoment.format("MM/DD/YYYY");
        endHour = endDateMoment.format("HH:mm");
        let endAmpPm = endDateMoment.format("A");

        console.table({
            startDate,
            endDate,
            startHour,
            endHour,
            startDateMoment,
            endDateMoment
        });

        if (checkAvailability) {

            if (SEARCH_AJAX_REQUEST != null) {
                SEARCH_AJAX_REQUEST.abort();
                SEARCH_AJAX_REQUEST = null;
            }

            SEARCH_AJAX_REQUEST = $.post("{{ route('space.vendor.availability.verifySelectedTimes') }}", {
                id: spaceId,
                bookingId: bookingId,
                start_date: startDate,
                end_date: endDate,
                start_hour: startHour,
                end_hour: endHour,
                start_ampm: startAmpPm,
                end_ampm: endAmpPm,
            }, function(response) {
                if (response.status == 'error') {
                    $('#loading-image').hide();
                    $("#updateSingleAlert").show().html(response.message);
                    submitBtn.attr("disabled", true).html("Update");
                } else if (response.status == 'success') {
                    $("#updateSingleAlert").hide();
                    submitBtn.attr("disabled", false).html("Update");
                    // $("#singlebooking-hours").html(response.total_hours);
                    // $("#singlebooking-amount").html(response.priceFormatted);
                    if (response.priceInfo === undefined) {
                        $("#singleBookingPriceInfo").html('');
                    } else {
                        $("#singleBookingPriceInfo").html(response.priceInfo.itemInfoHtml + response.priceInfo
                            .priceInfoHtml);
                    }
                }
                if (response.bookings && response.bookings.length > 0) {
                    submitBtn.attr("disabled", true).html("Update");
                    $("#updateSingleAlert").show().html("There are already bookings for selected dates");
                }
            });

        } else {
            submitBtn.attr("disabled", true).html("Please select all required fields");
        }

    }

    $(document).on("change", ".chksignleupdateprice", function() {
        fetchSingleBookingUpdatePricings();
    });

    setTimeout(() => {
        $(document).on("change", ".chksignleupdateprice2", function() {
            fetchSingleBookingUpdatePricings();
        });
    }, 2000);

    $(document).on("click", ".modifySingleBooking", function() {
        let obj = $(this);
        $("#singlebooking-id").val(obj.attr("data-value"));
        let bookingDetails = JSON.parse(obj.attr("data-details"));

        let startDate = moment(bookingDetails?.start_date);
        let endDate = moment(bookingDetails?.end_date);

        $("#single-booking-update-title").html("Update Booking #" + bookingDetails?.id);

        $("#singlebooking-fromdate").val(startDate.format("MM/DD/YYYY"));
        $("#singlebooking-fromtime").val(startDate.format("HH:mm"));

        $("#singlebooking-todate").val(endDate.format("MM/DD/YYYY"));
        $("#singlebooking-totime").val(endDate.format("HH:mm"));

        $("#singlebooking-status").val(bookingDetails?.status);
        $("#singlebooking-guest").val(bookingDetails?.total_guests);

        $("#singlebooking-spaceid").val(bookingDetails?.object_id);

        $("#updateSingleBookingModal").modal("show");
    });
</script>
