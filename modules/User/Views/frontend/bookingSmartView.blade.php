@extends('Layout::yellow_user_blank')
@section('content')
    <style>
        table tr {
            border-none;
        }

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

        .edit-img {
            display: none;
        }

        .delete {
            display: none;
        }
    </style>
    <?php
    //print_r($booking->object_id);
    ?>
    <!-- START PAGE CONTENT -->
    <div class="guest-checkout-content mons-font-container">
        <!-- START BREADCRUMBS-->

        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-3">
            <div class="card guest-checkout-card card-default full-height-n card-bordered p-4 card-radious">
                <div class="row book-table mb-2">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="book-details">
                            <table class="table" border="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="border-bottom:none;" class="td-id text-uppercase">
                                            <img class="logo-img" src="<?php echo url('/icon/MO_logo.svg'); ?>">
                                        </td>
                                    </tr>
                                    <tr style="padding-bottom:20px;" height="30">
                                        <td colspan="2" style="border-bottom:none;" class="td-id text-uppercase">Booking
                                            #{{ $booking->id }}</td>
                                    </tr>
                                    <tr style="padding-bottom:20px;" height="50">
                                        <td style="border: none;">
                                            <h3 style="margin-bottom: 0;color:#000;padding-bottom:0 !important;">
                                                {{ $booking->customer->name }}</h3>
                                        </td>
                                    </tr>
                                    <?php
                                    $book_status = $booking->statusText();
                                    $book_class = $booking->statusClass();
                                    ?>
                                    <tr>
                                        <td colspan="2"
                                            style="padding-top:0 !important;padding-bottom:20px !important;font-family:Montserrat;border-bottom:1px solid black;">
                                            <span class="status-btn <?= $book_class ?> success"><?= $book_status ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"
                                            style="text-align:center;border-bottom:none;width:50%;white-space:nowrap;">
                                            <b style="display: block;margin-top: 20px;text-transform: uppercase;">Booking
                                                Details</b>
                                        </td>
                                    </tr>
                                    <tr height="50">
                                        <td colspan="2"
                                            style="border-bottom:1px solid black;text-align:center;padding-bottom:20px !important;">
                                            <img width="25" height="25" src="<?php echo url('/icon/mo_arrive.svg'); ?>">
                                            &nbsp;&nbsp;{{ date('F d, Y | g:i:A', strtotime($booking->start_date)) }}
                                            <br /><br />
                                            <img width="25" height="25" src="<?php echo url('/icon/mo_depart.svg'); ?>">
                                            &nbsp;&nbsp;{{ date('F d, Y | g:i A', strtotime($booking->end_date)) }}
                                        </td>
                                    </tr>
                                    <tr height="20" style="border-bottom:none;">

                                    </tr>

                                    <?php
                                    if($booking->status === \App\Helpers\Constants::BOOKING_STATUS_BOOKED){
                                        ?>
                                    <tr style="border-bottom:none;">
                                        <td colspan="2" style="border-bottom:none;">
                                            <form method="post"
                                                action="{{ route('user.booking.guestcheckinpost', ['id' => $booking->id]) }}">
                                                @csrf
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="{{ $booking->id }}">
                                                <button class="checkInOutBtns" type="submit" id="checkInButton"
                                                    class="">CHECK IN</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>

                                    <?php
                                    if($booking->status === \App\Helpers\Constants::BOOKING_STATUS_CHECKED_IN){
                                        ?>
                                    <tr>
                                        <td colspan="2" style="border-bottom:none;">
                                            <form method="post"
                                                action="{{ route('user.booking.guestcheckoutpost', ['id' => $booking->id]) }}">
                                                @csrf
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="{{ $booking->id }}">
                                                <button class="checkInOutBtns" type="submit" id="checkOutButton"
                                                    class="">CHECK OUT</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="2" style="border:none;">
                                            <a style="margin-top: 20px;display:block;text-transform:uppercase;"
                                                href="{{ url('user/booking-details', $booking->id) }}">View Booking Details</a>
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


    </div>
    <!-- END CONTAINER FLUID -->
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection
