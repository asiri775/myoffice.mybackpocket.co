<div class="modal fade" id="updateSingleBookingStatusModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('booking.updateSingleBooking') }}" method="post">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="status-single-booking-update-title">{{ __('Update Booking') }}</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body pt-3">
                    <input type="hidden" name="id" id="status-singlebooking-id">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-12">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" id="status-singlebooking-status"
                                            class="form-control">
                                            <option value="">Select Status</option>
                                            @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                                <option value="{{ $k }}">{{ $txt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

    $(document).on("click", ".modifySingleBookingStatus", function() {
        let obj = $(this);
        $("#status-singlebooking-id").val(obj.attr("data-value"));
        let bookingDetails = JSON.parse(obj.attr("data-details"));

        $("#status-single-booking-update-title").html("Update Booking Status #" + bookingDetails?.id);

        $("#status-singlebooking-status").val(bookingDetails?.status);

        $("#updateSingleBookingStatusModal").modal("show");
    });
</script>
