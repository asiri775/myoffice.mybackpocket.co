@extends('layouts.new_user')
@section('content')
    <style>
        .new-align {
            padding-top: 5px;
        }

        .new-padding {
            padding-top: 5px;
        }

        .input-sm {
            padding-top: 0px;
        }

        .select2-selection {
            border: 1px solid rgb(206, 212, 218) !important;
            height: 38px !important;
        }
    </style>
					<?php 
					//print_r($booking->object_id);
					?>
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
		<div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Guest Booking CheckIn</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5">
            <div class="card card-default full-height-n card-bordered p-4 card-radious">
                        <div class="row book-table mb-2">
                            <div class="col-lg-3 col-sm-3 col-md-3">
                                <div class="date-start text-center mt-3">
                                    <div class="calendar-day">
                                        @php
                                            $date = $booking->start_date;
                                        @endphp
                                        <div class="day-name">{{ date('d', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('F', strtotime($date)) }}</div>
                                        <div class="m-name">{{ date('Y', strtotime($date)) }}</div>
                                    </div>
                                    <div class="status-btn <?= $booking->statusClass() ?>"><?= $booking->statusText() ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-sm-9 col-md-9">
                                <div class="book-details">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="td-id text-uppercase">Booking
                                                    #{{ $booking->id }}</td>
                                            </tr>
											<tr>
													<td colspan="2" style="align:left;">
													<form method="post" action="{{ route('user.booking.sendsmspre') }}">
															@csrf
														
														Pre Arrival Notification<br/>
														This is the Notification that the Guest
														will receive prior to the start of their booking.
														<input type="hidden" class="form-control" name="id" id="id" value="{{ $booking->id }}">
														
														<button type="submit" id="sendcheckinpre" class="btn btn-info">SendSMS</button>
														
													</form>
													{{ $sms_prearrival_message }}
													</td>
													<td colspan="2" style="align:left;">
														<form method="post" action="{{ route('user.booking.sendsmsarrival') }}">
																	@csrf
																Arrival CheckIn<br/>
																This is the Notification that the Guest
																will receive at the start of their booking.
																<div class="form-group" style="">
																	<input type="hidden" class="form-control" name="id" id="id" value="{{ $booking->id }}">
																	<button type="submit" id="sendcheckinattime" class="btn btn-info">SendSMS</button>
																</div>
														</form>			
													{{ $sms_arrival_message }}
													</td>	
                                    	</tbody>
                                    </table>
                                </div>
						    </div>
						</div>	
						 
						<div class="row book-table mb-2">
                            <div class="col-lg-3 col-sm-3 col-md-3">
							</div>
                            <div class="col-lg-9 col-sm-9 col-md-9">
                                <div class="book-details">
                                    <table class="table">
                                        <tbody>
                                           <tr>
													<td colspan="2" style="align:left;">
													<form method="post" action="{{ route('user.booking.sendemaillatecheckin') }}">
															@csrf
														
														Late CheckIn Host Reminder<br/>
														This is the Notification that the Host receives
														via Email, in case the guest has not Checked In.
														<input type="hidden" class="form-control" name="id" id="id" value="{{ $booking->id }}">
														
														<button type="submit" id="sendemaillatecheckin" class="btn btn-info">SendEmail</button>
														
													</form>
													{{ $failed }}
													{{ $success }}
													{{ $error }}
													</td>
											</tr>		
								    	</tbody>
                                    </table>
                                </div>
								
						    </div>
						</div>	
                        </div>
                    <!--card-->
                </div>
			
			<!-- START card -->
            <div class="card card-default card-bordered card-padding-body-zero p-4 card-radious">
                
            </div>
            <!-- END card -->
        </div>
        <!-- END CONTAINER FLUID -->
    </div>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection
