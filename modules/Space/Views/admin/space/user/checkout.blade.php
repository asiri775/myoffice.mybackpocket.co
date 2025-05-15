<div class="panel">
    <div class="panel-title"><strong>{{ __('Check_out') }}</strong></div>
    <div class="panel-body">
        <div class="form-group">
			<p><b>Departure Reminder :</b>
			This is the notification that the guest will receive prior to end of their booking.
			</p>
            <label>{{ __('Departure Reminder Time') }}</label>
            <select  placeholder="" id="checkout_reminder_time" name="checkout_reminder_time" class="form-control">
					
					@if ( old('checkout_reminder_time', $row->checkout_reminder_time)=='15 Minutes' )
					<option value="15 Minutes" selected>15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@elseif ( old('checkout_reminder_time', $row->checkout_reminder_time)=='30 Minutes' )
					<option value="15 Minutes">15 Minutes</option>
					<option value="30 Minutes" selected>30 Minutes</option>
					@else
					<option value="15 Minutes" selected>15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@endif
					
			</select>
		</div>
		<div class="form-group">
				<label for="departure_reminder_text">{{ __('Departure Reminder Text') }}</label>
            	<Textarea class="form-control bravo_wrap"  style="height:30px;" id="departure_reminder_text" name="departure_reminder_text">
 Your MyOffice Booking #{bookingno} at {spacename} is ending in {departure_reminder_time}.
 
 Kindly prepare the office for departure and ensure the space is ready for next tenant.
 
 Remember to Check OUT or you may EXTEND your stay (If available).

 {bookinglink}
				</Textarea>
		</div>
		<div class="form-group">
			<p><b>Late Checkout:</b>
			This is the notification that the guest receives if they have not Checked OUT.
			</p>
            <label>{{ __('Reminder Time After Expiry') }}</label>
            <select  placeholder="" id="latecheckout_reminder_time" name="latecheckout_reminder_time" class="form-control">
					
					@if ( old('latecheckout_reminder_time', $row->latecheckout_reminder_time)=='5 Minutes' )
					<option value="5 Minutes" selected>5 Minutes</option>
					<option value="15 Minutes">15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@elseif ( old('latecheckout_reminder_time', $row->latecheckout_reminder_time)=='15 Minutes' )
					<option value="5 Minutes">5 Minutes</option>
					<option value="15 Minutes" selected>15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@elseif ( old('latecheckout_reminder_time', $row->latecheckout_reminder_time)=='30 Minutes' )
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
				<label for="">{{ __('Late Departure Reminder Text') }}</label>
            	<Textarea class="form-control bravo_wrap" rows="10" id="latecheckout_reminder_text" name="latecheckout_reminder_text">
 Your MyOffice Booking #{bookingno} at {spacename} has expired and you have not yet Checked OUT of the space.
 
 Please note that it is important to notify your Host that you have departed, to ensure that your Space is checked for any damages or cleaning required.
 
 Check OUT 
 {checkouturl}
				</Textarea>
		</div>
		



		
    </div>
</div>
