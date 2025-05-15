<?php
$bookingItemsAndSummary = \App\Helpers\CodeHelper::getBookingItemsAndSummary($booking);
$role = '';
$download = $download ?? false;
?>
@extends('Layout::empty')
@section('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style type="text/css">
        html,
        body {
            background: #fff;
            font-family: Roboto;
        }

        .bravo_topbar,
        .bravo_header,
        .bravo_footer {
            display: none;
        }

        .invoice-amount {
            margin-top: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 20px;
            display: inline-block;
            text-align: center;
        }

        .email_new_booking .b-table {
            width: 100%;
        }

        .email_new_booking .val {
            text-align: right;
        }

        .email_new_booking td,
        .email_new_booking th {
            padding: 5px;
        }

        .email_new_booking .val table {
            text-align: left;
        }

        .email_new_booking .b-panel-title,
        .email_new_booking .booking-number,
        .email_new_booking .booking-status,
        .email_new_booking .manage-booking-btn {
            display: none;
        }

        .email_new_booking .fsz21 {
            font-size: 21px;
        }

        .table-service-head {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .table-service-head th {
            padding: 5px 15px;
        }

        #invoice-print-zone {
            background: white;
            padding: 15px;
            margin: 90px auto 40px auto;
            max-width: 1025px;
        }

        .invoice-company-info {
            margin-top: 2px;
        }

        .invoice-company-info p {
            margin-bottom: 2px;
            font-weight: normal;
        }

        .invoice-brand-details {
            text-align: center;
        }

        table {
            table-layout: fixed
        }

        .twoinline th.leftdata,
        .twoinline th.rightdata {
            width: 50%;
            vertical-align: top;
        }

        th.leftdata,
        th.rightdata {
            padding: 0 15px;
        }

        .twoinline th.rightdata {
            text-align: right;
        }

        table.inv-table {
            width: 100%;
            text-align: left;
            margin: 15px 0;
        }

        table.inv-table.center {
            text-align: center;
        }

        table.inv-table th {
            background: #000;
            color: #FFC106;
            text-transform: uppercase;
        }

        table.inv-table th,
        table.inv-table td {
            padding: 7px;
        }

        h2.invoice-main-title {
            font-size: 45px;
            color: #000;
            font-weight: 700;
            margin: 15px 0;
            font-family: Montserrat;
        }

        .twoinline table.inv-table {
            width: 100%;
        }

        .twoinline .rightdata table.inv-table {
            margin-right: 0;
            margin-left: auto
        }

        .brand-logo img {
            height: 60px;
            width: auto;
            max-width: auto !important;
            margin-top: 50px;
        }

        .invoice-table {
            table-layout: fixed;
        }

        .invoice-table td {
            border-bottom: 1px solid #ccc;
            height: 35px;
        }

        .invoice-table tr td:first-child,
        .invoice-table tr th:first-child {
            width: 65%;
        }

        .inv-table td {
            font-weight: normal;
        }

        .invoice-table tr td:not(:first-child) {
            border-left: 1px solid #ccc;
        }

        .invoice-make-it-count {
            margin-top: 35px;
            font-size: 12px;
            font-weight: normal;
        }

        .invoice-make-it-count h6 {
            font-weight: 700;
            font-size: 14px;
        }

        .invoiceextrainfo {
            padding-top: 0px !important;
            text-align: center;
            width: 100%;
        }

        .invoiceextrainfo p {
            text-align: center;
            margin: 2px 0;
            font-size: 12px;
            font-weight: normal;
        }

        .inv-sub-table {
            max-width: 340px;
        }

        .inv-sub-table td {
            border-bottom: 1px solid #000;
        }

        .inv-sub-table td:first-child {
            /* text-align: right !important; */
        }


        .inv-sub-table td:nth-child(2) {
            width: 35px;
            max-width: 35px;
        }

        .invoice-table td:nth-child(2),
        .invoice-table th:nth-child(2) {
            text-align: center;
        }

        .inv-sub-table td:nth-child(3) {
            text-align: right;
        }

        .inv-total-table {
            /* border: 2px solid #000; */
        }

        .noborder {
            border: none !important;
        }

        td.no-r-padding.no-b-border {
            padding: 0;
            border: none;
        }

        div#invoice-print-zone {
            padding: 0;
            max-width: 100%;
            margin: 0;
        }

        .inv-sub-table tr.grand-total * {
            font-size: 18px;
            font-weight: 700;
        }

        tr.bordered-inv-row {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }

        .inv-sub-table td.label {
    text-align: right !important;
}

    </style>
    <?php
    $translation = $service->translateOrOrigin(app()->getLocale());
    $lang_local = app()->getLocale();
    ?>
    <link href="{{ asset('module/user/css/user.css') }}" rel="stylesheet">
    <?php
    if(isset($_GET['print'])){
        ?>
    <script>
        window.print();
    </script>
    <?php
    }
    ?>

    <?php
if(isset($_GET['download'])){
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function saveAsPDF() {
            // Use html2pdf library to save the current page as PDF
            html2pdf(document.body, {
                margin: 10,
                filename: 'downloaded_page.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            }).then(function() {
                // PDF has been successfully created
                // Close the current window after a short delay (adjust as needed)
                setTimeout(function() {
                    window.close();
                }, 2000); // 2000 milliseconds (2 seconds) delay
            });
        }

        // Call the saveAsPDF function
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                saveAsPDF();
            }, 0);
        });
    </script>

    <?php
}else{
    ?>

    <?php
    if($download){
        ?>
    <style>
        #invoice-print-zone {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
        }
    </style>
    <?php
    }else{
        ?>
    <style>
        #invoice-print-zone {
            width: 840px !important;
            max-width: 840px !important;
            margin: 0 auto !important;
        }
    </style>
    <?php
    }
    ?>

    <style>
        @media print {
            #invoice-print-zone {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
    <?php
}
?>

    <div id="invoice-print-zone">
        <table width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="twoinline">
                    <th width="50%" class="leftdata">
                        <div class="invoice-brand-details">
                            <div class="brand-logo">
                                <img src="{{ asset('user_assets/img/brand-icon.png') }}"
                                    alt="{{ setting_item('site_title') }}">
                            </div>
                            <div class="invoice-company-info">
                                {!! setting_item_with_lang('invoice_company_info') !!}
                            </div>
                        </div>
                    </th>
                    <th width="50%" class="rightdata">
                        <h2 class="invoice-main-title">{{ __('INVOICE') }}</h2>
                        <table class="inv-table center">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                            </tr>
                            <tr>
                                <td>{{ __(':number', ['number' => $booking->id]) }}</td>
                                <td>{{ __(':date', ['date' => display_date($booking->created_at)]) }}</td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr class="twoinline">
                    <th width="50%" class="leftdata">
                        <div class="invoice-bill-options">
                            <table class="inv-table">
                                <tr>
                                    <th>Bill To</th>
                                </tr>
                                <tr>
                                    <td>
                                        <span
                                            style="font-weight: 700;">{{ $booking->first_name . ' ' . $booking->last_name }}
                                            (ID#{{ $booking->customer_id }})</span>
                                        <br>
                                        <span>{{ $booking->email }}</span><br>
                                        <span>{{ $booking->phone }}</span><br>
                                        <span>{{ $booking->address }}</span><br>
                                        <span>{{ implode(', ', [$booking->city, $booking->state, $booking->zip_code, get_country_name($booking->country)]) }}</span><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </th>
                    <th width="50%" class="rightdata">
                        <table class="inv-table">
                            <tr>
                                <th>Booking Details</th>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-weight: 700;">{!! clean($translation->title) !!}</span> <br>
                                    {{ $translation->address }} <br>
                                    Host: <a
                                        href="{{ route('user.profile.publicProfile', $booking->vendor_id) }}">{{ $booking->vendor->getPublicName() }}</a>
                                    <br>
                                    E-Mail: {{ $booking->vendor->email }}<br>
                                    <br>
                                    <span
                                        style="font-weight: 700;">Arrival:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    {{ display_date($booking->start_date) }}<br>
                                    <span style="font-weight: 700;">Departure:&nbsp;&nbsp;</span>
                                    {{ display_date($booking->end_date) }}
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" width="100%" class="leftdata">
                        <table class="inv-table invoice-table">
                            <tr>
                                <th style="text-align: center;">Description</th>
                                <th>RATE</th>
                                <th style="text-align: center;">Amount</th>
                            </tr>
                            <tbody>
                                <?php
                                foreach($bookingItemsAndSummary['items'] as $item){
                                    ?>
                                <tr class="border-bottom {{ $item['type'] }}">
                                    <td class="i-name"><?= $item['name'] ?></td>
                                    <td class="text-center i-amount">
                                        <?= $item['amount'] ?>
                                    </td>
                                    <td class="text-right i-amount">
                                        <?= $item['amount'] ?>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </th>
                </tr>
                <tr class="twoinline">
                    <th width="50%" class="leftdata">
                        <div class="invoice-make-it-count">
                            <div class="img">

                            </div>
                            <div class="content">
                                <h6><a style="color: #000;" href="http://www.janeen.ca" target="_blank">Make It Count</a>
                                </h6>
                                <p>Help Us Make A Difference! Your small micro donation will go
                                    towards providing free services and programs for Mental
                                    Health. In addition, this Merchant will also generously match
                                    your donation.</p>
                                <p><a style="color: #000;font-weight:700;" href="http://www.janeen.ca" target="_blank">Click
                                        Here</a> to learn more about this program and the Janeen
                                    Foundation</p>
                            </div>
                        </div>
                    </th>
                    <th width="50%" class="rightdata">
                        {{-- <table class="inv-table inv-sub-table">
                            <tr>
                                <td class="noborder" class="val" colspan="3">
                                    <table class="pricing-list" width="100%">
                                        

                                    </table>
                                </td>
                            </tr>

                        </table> --}}
                        <table class="inv-table inv-sub-table inv-total-table">
                            <tbody>
                                <?php
                foreach($bookingItemsAndSummary['summaryItems'] as $item){
                    ?>
                                <tr class=" {{ $item['type'] }}">
                                    <td class="label fsz21 i-name"><?= $item['name'] ?>
                                    </td>
                                    <td>$</td>
                                    <td class="val fsz21 i-amount">{{ $item['amount'] }}</td>
                                </tr>
                                <?php
                }
                ?>
                            </tbody>

                            <tr>
                                <td class="label fsz21">{{ __('Paid') }}</td>
                                <td>$</td>
                                <td class="val fsz21"><strong
                                        style="color: #000">{{ format_money($booking->paid) }}</strong></td>
                            </tr>
                            {{-- @if ($booking->total > $booking->paid) --}}
                            <tr class="bordered-inv-row">
                                <td class="label fsz21">{{ __('Balance') }}</td>
                                <td>$</td>
                                <td class="val fsz21"><strong
                                        style="color: #FA5636">{{ format_money($booking->total - $booking->paid) }}</strong>
                                </td>
                            </tr>
                            {{-- @endif --}}
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" width="100%" class="leftdata invoiceextrainfo">
                        <p style="margin-top: 15px;">For questions regarding your invoice, please contact us at</p>
                        <p><b>accounting@myoffice.ca</b></p>
                    </th>
                </tr>
                <tr>
                    <td><span style="display:block;margin-bottom:15px;"></span></td>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('module/user/js/user.js') }}"></script>
@endsection
