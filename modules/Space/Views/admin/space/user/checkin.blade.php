<style>
	textarea{height:100px;}
</style>
<div class="panel">
    <div class="panel-title"><strong>{{ __('Check in') }}</strong></div>
    <div class="panel-body">
        <div class="form-group">
			<p><b>Pre-Arrival Notification :</b>
			This is the notification that the guest will receive prior to start of their booking.
			</p>
            <label>{{ __('Reminder Time') }}</label>
			<select  placeholder="" id="checkin_reminder_time" name="checkin_reminder_time" class="form-control">
					 
					@if ( old('checkin_reminder_time', $row->checkin_reminder_time)=='30 Minutes' )
					<option value="30 Minutes" selected>30 Minutes</option>
					<option value="1 Hour" >1 Hour</option>
					<option value="90 Minutes" >90 Minutes</option>
					<option value="2 Hours" >2 Hours</option>
					@elseif ( old('checkin_reminder_time', $row->checkin_reminder_time)=='1 Hour' )
					<option value="30 Minutes" >30 Minutes</option>
					<option value="1 Hour" selected>1 Hour</option>
					<option value="90 Minutes" >90 Minutes</option>
					<option value="2 Hours" >2 Hours</option>
					@elseif ( old('checkin_reminder_time', $row->checkin_reminder_time)=='90 Minutes' )
					<option value="30 Minutes" >30 Minutes</option>
					<option value="1 Hour" >1 Hour</option>
					<option value="90 Minutes" selected>90 Minutes</option>
					<option value="2 Hours" >2 Hours</option>
					@elseif ( old('checkin_reminder_time', $row->checkin_reminder_time)=='2 Hours' )
					<option value="30 Minutes" >30 Minutes</option>
					<option value="1 Hour" >1 Hour</option>
					<option value="90 Minutes" >90 Minutes</option>
					<option value="2 Hours" selected>2 Hours</option>
					@else
					<option value="30 Minutes" selected>30 Minutes</option>
					<option value="1 Hour" >1 Hour</option>
					<option value="90 Minutes" >90 Minutes</option>
					<option value="2 Hours" >2 Hours</option>
					@endif
					
			</select>
		</div>
		<div class="form-group">
				 <label for="prearrival_checkin_text">{{ __('Prearrival Checkin Text') }}</label>
				  <Textarea class="form-control" rows="4" id="prearrival_checkin_text" name="prearrival_checkin_text">
				  Hi {firstname},
				  
				  Your MyOffice Booking at {spacename} is coming up in {checkintime}. 

				  Please be sure of Check IN upon arrival:
				  {bookinglink}
				  </Textarea>
		</div>
		<div class="form-group">
			<p><b>Arrival Checkin :</b>
			This is the Notification that the Guest will recive at the start of their booking.
			</p>
            <label>{{ __('Arrival checkin') }}</label>
            <select  placeholder="" id="arrival_checkin_reminder" name="arrival_checkin_reminder" class="form-control">
					@if ( old('arrival_checkin_reminder', $row->arrival_checkin_reminder)=='On Time' )
					<option value="On Time" selected>On Time</option>
					@endif

					@if ( old('arrival_checkin_reminder', $row->arrival_checkin_reminder)=='' )
					<option value="On Time" selected>On Time</option>
					@endif
			</select>
		</div>
		<div class="form-group">
				 <label for="arrival_checkin_text">{{ __('Arrival Checkin Text') }}</label>
				  <Textarea  class="form-control"  id="arrival_checkin_text" name="arrival_checkin_text">
				  Your MyOffice booking #{bookingno} is starting now.  
				  
				  Please be sure to Check IN to let your Host know that you have arrived: 
				  {bookinglink}
				  </Textarea>
		</div>
		<div class="form-group">
			<p><b>Late Checkin :</b>
			This is the Notification that the Host will recive via Email, in case a Guest has not checked IN.
			</p>
            <label>{{ __('Host Reminder Time') }}</label>
            <select  placeholder="" id="host_checkin_reminder" name="host_checkin_reminder" class="form-control">
					@if ( old('host_checkin_reminder', $row->host_checkin_reminder)=='5 Minutes' )
					<option value="5 Minutes" selected>5 Minutes</option>
					<option value="15 Minutes">15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@elseif ( old('host_checkin_reminder', $row->host_checkin_reminder)=='15 Minutes' )
					<option value="5 Minutes">5 Minutes</option>
					<option value="15 Minutes" selected>15 Minutes</option>
					<option value="30 Minutes">30 Minutes</option>
					@elseif ( old('host_checkin_reminder', $row->host_checkin_reminder)=='30 Minutes' )
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
				<label for="host_reminder_text">{{ __('Host Reminder Text') }}</label>
            	<Textarea class="form-control bravo_wrap" rows="4"  id="host_reminder_text" name="host_reminder_text">
				Dear {FirstName},
				
				Your Guest {guestname} has not yet Checked IN for Booking #. 
				
				Please manually Check IN the Guest, or contact them to verify if they are still going to complete their scheduled booking.
				
				Booking Details :
				
				Listing Name            :
				Arrival Time            :
				Departure Date and Time :
				
				Cancellation Fee        :

                Contact Guest | Manual Check IN | Edit Booking    				
				</Textarea>
		</div>
		
    </div>
</div>
