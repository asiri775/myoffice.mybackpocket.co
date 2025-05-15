<div class="modal fade" id="rateHostSingleBooking<?= $booking->id ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('review.store') }}" class="review-form needs-validation" novalidate method="post">
                @csrf
                <input type="hidden" name="review_service_type" value="space">
                <input type="hidden" name="review_service_id" value="<?= $booking->object_id ?>">
                <input type="hidden" name="reference_id" value="<?= $booking->id ?>">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="status-single-booking-update-title">{{ __('Rate Host') }}</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body pt-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" required class="form-control" name="review_title"
                                    placeholder="{{ __('Title') }}">
                                <div class="invalid-feedback">{{ __('Review title is required') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-8">
                            <div class="form-group">
                                <textarea name="review_content" rows="10" required class="form-control" placeholder="{{ __('Review content') }}"
                                    minlength="10"></textarea>
                                <div class="invalid-feedback">
                                    {{ __('Review content has at least 10 character') }}
                                </div>
                            </div>
                        </div>
                        @if ($tour_review_stats = setting_item($row->type . '_review_stats'))
                            <?php
                            $tour_review_stats = [['title' => 'Cleanliness'], ['title' => 'Amenities'], ['title' => 'Service'], ['title' => 'Price']];
                            $tour_review_stats = json_decode(json_encode($tour_review_stats));
                            ?>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group review-items">
                                    @foreach ($tour_review_stats as $item)
                                        <div class="item">
                                            <label>{{ $item->title }}</label>
                                            <input class="review_stats" type="hidden"
                                                name="review_stats[{{ $item->title }}]">
                                            <div class="rates">
                                                <i class="fa fa-star-o grey"></i>
                                                <i class="fa fa-star-o grey"></i>
                                                <i class="fa fa-star-o grey"></i>
                                                <i class="fa fa-star-o grey"></i>
                                                <i class="fa fa-star-o grey"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group review-items">
                                    <div class="item">
                                        <label>{{ __('Review rate') }}</label>
                                        <input class="review_stats" type="hidden" name="review_rate">
                                        <div class="rates">
                                            <i class="fa fa-star-o grey"></i>
                                            <i class="fa fa-star-o grey"></i>
                                            <i class="fa fa-star-o grey"></i>
                                            <i class="fa fa-star-o grey"></i>
                                            <i class="fa fa-star-o grey"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (setting_item('review_upload_picture'))
                        <div class="review_upload_wrap">
                            <div class="mb-3"><i class="fa fa-camera"></i> {{ __('Add photo') }}</div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="review_upload_btn">
                                        <span class="helpText" id="helpText"></span>
                                        <input type="file" id="file" multiple data-name="review_upload"
                                            data-multiple="1" accept="image/*" class="review_upload_file">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="review_upload_photo_list row"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button class="btn btn btn-primary" id="singleUpdateBookingBtn"
                        type="submit">{{ __('Submit Review') }}</button>
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
