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
    $referral_details = null;
    $signUpReferral = \Modules\Referalprogram\Models\ReferralProgram::where('uri', 'register')->first();
    if ($signUpReferral != null) {
        $referral_details = new \Modules\Referalprogram\Models\ReferralLink();
        $referral_details = $referral_details->getRefferal($booking->customer, $signUpReferral);
    }
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
                                            style="padding-bottom:20px !important;font-family:Montserrat;border-bottom:1px solid #ddd;"
                                            class="td-id text-uppercase">Booking
                                            #{{ $booking->id }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" height="50"
                                            style="font-family:Montserrat;padding-top:10px;border-bottom:1px solid #ddd;font-weight:900;">
                                            Thank you for using MyOffice
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" height="50"
                                            style="border-bottom:none;padding-top:10px;line-height:20px !important;padding-bottom:0 !important;">
                                            We hope you enjoyed your stay at </br>
                                        </td>
                                    <tr style="border:none;padding-top:0px;vertical-align:bottom;"
                                        height="80">
                                        <td style="min-width: 150px;max-width: 150px;border:none">
                                            <div class="listing-table-image-box"
                                                style="background-image: url('{{ $space->getImageLink() }}')"></div>
                                        </td>
                                        <td style="border:none;">
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
                                    </tr>
                                    <tr style="border-bottom:1px solid #ddd;" height="10">
                                        <td colspan="3"
                                            style="border-bottom:1px solid #ddd;padding-bottom:20px !important;">
                                            Please visit us again soon!
                                        </td>
                                    </tr>
                                    @if ($referral_details)
                                        <tr style="border-bottom:none;" height="100">
                                            <td colspan="3" style="font-size:14pt;border-bottom:none;font-weight:900;">
                                                Refer A Friend and Receive a ${{ $signUpReferral->amount }} Credit in your
                                                account
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:none;padding-bottom:15px;">
                                            <td colspan="3"
                                                style="border-bottom:none;padding-bottom:15px;font-weight:900;font-size:20pt;">
                                                <h6
                                                    style="font-size: 16px;margin-bottom: -10px;color:#FFCD04;font-weight:600;">
                                                    {{ $referral_details->code }}</h6> <br />
                                                <p style="font-size:9pt;">Sign Up Code</p>
                                            </td>
                                        </tr>
                                    @endif
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
