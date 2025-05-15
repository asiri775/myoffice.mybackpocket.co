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
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-3">
            <div class="card card-default  guest-checkout-card full-height-n card-bordered p-4 card-radious">
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
                                    <tr style="padding-bottom:5px;" height="30">
                                        <td colspan="2" style="padding-bottom:20px !important;font-family:Montserrat;border-bottom:1px solid #ddd;" class="td-id text-uppercase">Booking
                                            #{{ $booking->id }}</td>
                                    </tr>
                                    <tr style="border-bottom:1px solid #ddd;text-align:left;" height="80px">
                                        <td colspan="3" style="border-bottom:none;font-weight:900;">You are now Checked
                                            IN</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"
                                            style="padding-top:20px !important;text-align:center;border-bottom:none;">
                                            Your departure is
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-weight:900;border-bottom:none;text-align:center;">
                                            {{ date('F d, Y | g:i A', strtotime($booking->end_date)) }}
                                        </td>
                                    </tr>
                                    <tr style="border-bottom:1px solid #ddd;padding-bottom:10px;">
                                        <td colspan="3"
                                            style="border-bottom:1px solid #ddd;padding-bottom: 20px !important;">
                                            Your will receive a text message {{ $space->checkout_reminder_time }}
                                            prior to your departure time as a reminder.
                                            You may Extend Your Stay if the space is available
                                        </td>
                                    </tr>
                                    <tr style="border-bottom:none;" height="100">
                                        <td colspan="3"
                                            style="font-family:Montserrat;font-size:18pt;font-weight:600;border-bottom:none;">
                                            Enjoy your Stay!
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border:none;">
                                            <a class="guest-chk-link" style="margin-top: 20px;display:block;text-transform:uppercase;"
                                                href="{{ route('pwa.get.bookingDetails', $booking->id) }}">Booking Details</a>
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
