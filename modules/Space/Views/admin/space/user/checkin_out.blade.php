<style>
	.bravo_wrap textarea.form-control.no-height{
		height: auto !important;
	}
</style>
<div class="nav nav-tabs">
    <a class="active" data-toggle="tab" href="#navcheckin">{{ __('Check IN') }}</a>
    <a data-toggle="tab" href="#navcheckout">{{ __('Check OUT') }}</a>
</div>
<div class="tab-content">
    <div id="navcheckin" class="tab-pane fade in active">

        <div class="panel">
            <div class="panel-title"><strong>{{ __('Check IN') }}</strong></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <p><b>Pre-Arrival Notification :</b>
                                This is the notification that the guest will receive prior to start of their booking.
                            </p>
                            <label>{{ __('Reminder Time') }}</label>
                            <select placeholder="" id="checkin_reminder_time" name="checkin_reminder_time"
                                class="form-control">

                                @if (old('checkin_reminder_time', $row->checkin_reminder_time) == '30 Minutes')
                                    <option value="30 Minutes" selected>30 Minutes</option>
                                    <option value="1 Hour">1 Hour</option>
                                    <option value="90 Minutes">90 Minutes</option>
                                    <option value="2 Hours">2 Hours</option>
                                @elseif (old('checkin_reminder_time', $row->checkin_reminder_time) == '1 Hour')
                                    <option value="30 Minutes">30 Minutes</option>
                                    <option value="1 Hour" selected>1 Hour</option>
                                    <option value="90 Minutes">90 Minutes</option>
                                    <option value="2 Hours">2 Hours</option>
                                @elseif (old('checkin_reminder_time', $row->checkin_reminder_time) == '90 Minutes')
                                    <option value="30 Minutes">30 Minutes</option>
                                    <option value="1 Hour">1 Hour</option>
                                    <option value="90 Minutes" selected>90 Minutes</option>
                                    <option value="2 Hours">2 Hours</option>
                                @elseif (old('checkin_reminder_time', $row->checkin_reminder_time) == '2 Hours')
                                    <option value="30 Minutes">30 Minutes</option>
                                    <option value="1 Hour">1 Hour</option>
                                    <option value="90 Minutes">90 Minutes</option>
                                    <option value="2 Hours" selected>2 Hours</option>
                                @else
                                    <option value="30 Minutes" selected>30 Minutes</option>
                                    <option value="1 Hour">1 Hour</option>
                                    <option value="90 Minutes">90 Minutes</option>
                                    <option value="2 Hours">2 Hours</option>
                                @endif

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prearrival_checkin_text">{{ __('Reminder Content') }}</label>
                            <textarea rows="10" class="form-control no-height" rows="20" id="prearrival_checkin_text"
                                name="prearrival_checkin_text">{{ old('prearrival_checkin_text', $row->prearrival_checkin_text) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <p><b>Arrival Check In :</b>
                                This is the Notification that the Guest will recive at the start of their booking.
                            </p>
                            <label>{{ __('Arrival Check In') }}</label>
                            <select placeholder="" id="arrival_checkin_reminder" name="arrival_checkin_reminder"
                                class="form-control">
                                @if (old('arrival_checkin_reminder', $row->arrival_checkin_reminder) == 'On Time')
                                    <option value="On Time" selected>On Time</option>
                                @endif

                                @if (old('arrival_checkin_reminder', $row->arrival_checkin_reminder) == '')
                                    <option value="On Time" selected>On Time</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="arrival_checkin_text">{{ __('Reminder Content') }}</label>
                            <textarea rows="10" class="form-control no-height" rows="20" id="arrival_checkin_text" name="arrival_checkin_text">{{ old('arrival_checkin_text', $row->arrival_checkin_text) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <p><b>Late Check IN - Host Reminder</b>
                                This is the Notification that the Host will recive via Email, in case a Guest has not
                                Checked IN.
                            </p>
                            <label>{{ __('Host Reminder Time') }}</label>
                            <select placeholder="" id="host_checkin_reminder" name="host_checkin_reminder"
                                class="form-control">
                                @if (old('host_checkin_reminder', $row->host_checkin_reminder) == '5 Minutes')
                                    <option value="5 Minutes" selected>5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @elseif (old('host_checkin_reminder', $row->host_checkin_reminder) == '15 Minutes')
                                    <option value="5 Minutes">5 Minutes</option>
                                    <option value="15 Minutes" selected>15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @elseif (old('host_checkin_reminder', $row->host_checkin_reminder) == '30 Minutes')
                                    <option value="5 Minutes">5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes" selected>30 Minutes</option>
                                @else
                                    <option value="5 Minutes" selected>5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="host_reminder_text">{{ __('Reminder Content') }}</label>
                            <textarea rows="10" class="form-control no-height" rows="20" id="host_reminder_text" name="host_reminder_text">{{ old('host_reminder_text', $row->host_reminder_text) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>

    <div id="navcheckout" class="tab-pane fade">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-title"><strong>{{ __('Check OUT') }}</strong></div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <p><b>Departure Reminder :</b>
                                This is the notification that the guest will receive prior to end of their booking.
                            </p>
                            <label>{{ __('Departure Reminder Time') }}</label>
                            <select placeholder="" id="checkout_reminder_time" name="checkout_reminder_time"
                                class="form-control">

                                @if (old('checkout_reminder_time', $row->checkout_reminder_time) == '15 Minutes')
                                    <option value="15 Minutes" selected>15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @elseif (old('checkout_reminder_time', $row->checkout_reminder_time) == '30 Minutes')
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes" selected>30 Minutes</option>
                                @else
                                    <option value="15 Minutes" selected>15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @endif

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departure_reminder_text">{{ __('Reminder Content') }}</label>
                            <textarea rows="10" class="form-control no-height" rows="10" id="departure_reminder_text"
                                name="departure_reminder_text">{{ old('departure_reminder_text', $row->departure_reminder_text) }}</textarea>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">
                            <p><b>Late Checkout:</b>
                                This is the notification that the guest receives if they have not Checked OUT.
                            </p>
                            <label>{{ __('Reminder Time After Expiry') }}</label>
                            <select placeholder="" id="latecheckout_reminder_time" name="latecheckout_reminder_time"
                                class="form-control">
                                @if (old('latecheckout_reminder_time', $row->latecheckout_reminder_time) == '5 Minutes')
                                    <option value="5 Minutes" selected>5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @elseif (old('latecheckout_reminder_time', $row->latecheckout_reminder_time) == '15 Minutes')
                                    <option value="5 Minutes">5 Minutes</option>
                                    <option value="15 Minutes" selected>15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @elseif (old('latecheckout_reminder_time', $row->latecheckout_reminder_time) == '30 Minutes')
                                    <option value="5 Minutes">5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes" selected>30 Minutes</option>
                                @else
                                    <option value="5 Minutes" selected>5 Minutes</option>
                                    <option value="15 Minutes">15 Minutes</option>
                                    <option value="30 Minutes">30 Minutes</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Reminder Content') }}</label>
                            <textarea rows="10" class="form-control no-height" rows="10" id="latecheckout_reminder_text"
                                name="latecheckout_reminder_text">{{ old('latecheckout_reminder_text', $row->latecheckout_reminder_text) }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>
