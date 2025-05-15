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
            <div class="card card-default guest-checkout-card full-height-n card-bordered p-4 card-radious">
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
                                        <td colspan="2"
                                            style="padding-bottom:5px !important;font-family:Montserrat;border:none;"
                                            class="td-id text-uppercase">Booking
                                            #{{ $booking->id }}</td>
                                    </tr>
                                    <tr style="border-bottom:1px solid #ddd;padding-top:40px;vertical-align:bottom;"
                                        height="80">
                                        <td style="min-width: 150px;max-width: 150px;">
                                            <div class="listing-table-image-box"
                                                style="background-image: url('{{ $space->getImageLink() }}')"></div>
                                        </td>
                                        <td>
                                            <div class="listing-table-data-box">
                                                <?php
                                                $adressData = $space->addressWithDistance();
                                                ?>
                                                <h1>{{ $space->title }}</h1>
                                                <p>{{ $adressData['address'] }}</p>
                                                <a class="guest-chk-link" href="{{ $adressData['link'] }}"
                                                    target="_blank">View On Map</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr height="20">
                                        <td colspan="2" style="border:none;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="guest-chk-time-info">
                                            <div class="guest-chk-time-info-innner">
                                                <strong>Arrival:</strong>
                                                <span>{{ date('F d, Y | g:i:A', strtotime($booking->start_date)) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="guest-chk-time-info">
                                            <div class="guest-chk-time-info-innner">
                                                <strong>Departure:</strong>
                                                <span>{{ date('F d, Y | g:i:A', strtotime($booking->end_date)) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom:none;">
                                        <td colspan="2" style="border-bottom:none;">
                                            <div class="qr-code-image">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::generate(route('user.booking.bookingSmartView', $booking->code)) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border:none;">
                                            <a class="guest-chk-link" style="margin-top: 20px;display:block;text-transform:uppercase;"
                                                href="{{ route('pwa.get.bookingDetails', $booking->id) }}">Booking Details</a>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom:none;">
                                        <td colspan="2" style="border-bottom:none;">
                                            <form method="post"
                                                action="{{ route('user.booking.guestcheckoutpost', ['id' => $booking->id]) }}">
                                                @csrf
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="{{ $booking->id }}">
                                                <button type="submit" id="checkOutButton"
                                                    class="checkInOutBtns">CHECK OUT</button>
                                            </form>
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
