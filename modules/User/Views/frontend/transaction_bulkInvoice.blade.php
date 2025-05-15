<?php
$amount = 0;
?>
<!DOCTYPE html>
<html lang="en" class="">
<head>

    <style type="text/css">
        html,
        body {
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
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
            background-color: white;
        }

        .table-service-head th {
            padding: 5px 15px;
        }

        #invoice-print-zone {
            background: white;
            padding: 15px;
            margin: 0;
            max-width: 100%;
        }

        .invoice-company-info {
            margin-top: 15px;
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
            width: 100%;
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
            font-weight: 600;
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
            height: 100px;
            width: auto;
            max-width: auto !important;
            margin-top: 0;
        }

        .invoice-table {
            table-layout: fixed;
        }

        .invoice-table td {
            border-bottom: 1px solid #ccc;
            height: 35px;
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
            font-weight: 600;
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
            max-width: 100%;
        }

        .inv-sub-table td {
            border-bottom: 1px solid #000;
        }

        .inv-sub-table td:first-child {
            text-align: right;
        }

        .inv-sub-table td:nth-child(2) {
            width: 35px;
            max-width: 35px;
        }

        .invoice-table td:nth-child(2),
        .invoice-table th:nth-child(2) {
            text-align: left;
        }

        .inv-sub-table td:nth-child(3) {
            text-align: right;
        }

        .inv-total-table {
            border: 2px solid #000;
        }

        .noborder {
            border: none !important;
        }

        td.no-r-padding.no-b-border {
            padding: 0;
            border: none;
        }
    </style>


    <div id="invoice-print-zone">
        <table width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="twoinline">
                    <th width="100%" class="leftdata">
                        <div class="invoice-brand-details">
                            <div class="brand-logo">
                                <img src="{{ asset('user_assets/img/logo-black-main.png') }}"
                                    alt="{{ setting_item('site_title') }}">
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" width="100%" class="leftdata">
                        <table class="inv-table invoice-table">
                            <tr>
                                <th style="text-align: left !important;">#ID</th>
                                <th style="text-align: left !important;">Date</th>
                                <th style="text-align: left !important;">Type</th>
                                <th style="text-align: left !important;">Amount</th>
                                <th style="text-align: left !important;">Status</th>
                            </tr>
                            @foreach ($transactions as $transaction)
                                <?php
                                $amount += $transaction->amount;
                                ?>
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->created_at }}</td> 
                                    <td>{{ ucwords($transaction->type) }}</td>
                                    <td>${{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->status_name }}</td>
                                </tr>
                            @endforeach

                        </table>
                    </th>
                </tr>
                <tr class="twoinline">
                    <th width="100%" class="rightdata">

                        <table class="inv-table inv-sub-table inv-total-table">
                            <tbody> 
                                <tr>
                                    <td class="label fsz21">Total:</td>
                                    <td class="val fsz21"><strong style="color: #000">${{number_format($amount, 2)}}</strong></td>
                                </tr>
                            </tbody>
                        </table>  
                    </th>
                </tr>
            </thead>
        </table>


    </div>

    </div>

    </body>

</html>
