<div class="modal fade" tabindex="-1" role="dialog" id="contactBookModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <form action="{{ route('user.booking.contactHost') }}" method="post">
                @csrf
                <div class="modal-header">
      <h5 style="font-family:Montserrat;font-size:16pt;font-weight:900;" class="modal-title text-center w-100"><img width="30" height="30" src="<?php echo url('/icon/mo_calendar.svg');?>" />&nbsp;&nbsp;BOOKING #{{$booking->id}}: CONTACT HOST<hr></h5>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-4">
                    <h6>RE: {{ $subject }}</h6>
                   <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                   	<div class="form-group d-none">
                        <label for="">Topic</label>
                        <input type="text" name="topic" value="{{$topic}}">
                    </div>
                    <div class="form-group d-none">
                        <label for="">Subject</label>
                        <input type="text" name="subject" value="{{$subject}}">
                    </div>
                    <div class="form-group" v-if="!enquiry_is_submit">
                        <label for="">Notes</label>
                        <textarea required rows="6" id="notesData" class="form-control" placeholder="{{ __('Enter Notes') }}" name="notes"></textarea>
                    </div>
                    <div class="message_box"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                        data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" style="margin-top:-1px;" class="btn btn-primary btn-su">{{ __('Send Message') }}
                        <i class="fa icon-loading fa-spinner fa-spin fa-fw" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #notesData{
        height: 175px !important;
    }
</style>