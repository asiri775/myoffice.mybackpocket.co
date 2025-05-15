<?php
$bookingItemsAndSummary = \App\Helpers\CodeHelper::getBookingItemsAndSummary($booking, $role);
$role = $role ?? '';
?> 
<style>
    .table-booking-items tr.grand-total .i-name {
        border-left: 1px solid grey;
        border-top: 1px solid grey;
        border-bottom: 1px solid grey;
        font-size: 16px !important;
    }

    .table-booking-items tr.grand-total .i-amount {
        font-weight: 900;
        border-right: 1px solid grey;
        font-size: 18px !important;
        border-top: 1px solid grey;
        border-bottom: 1px solid grey;
    }
</style>
<table class="table table-booking-items table-booking-pri-ce-items table-borderless">
    <tbody>
        <thead>
            <tr>
                <th style="width:50%;text-align: center;">Item</th>
                <th style="width:35%;text-align: center;">QTY</th>
                <th style="width:15%;text-align: right;">Rate</th>
            </tr>
        </thead>
        <?php
                foreach($bookingItemsAndSummary['items'] as $item){
                    ?>
        <tr class="border-bottom {{ $item['type'] }}">
            <td class="i-name"><?= $item['name'] ?></td>
            <td class="text-center i-qty">
                <?= $item['quantity'] ?>
            </td>
            <td class="text-right i-amount">
                <?= $item['amount'] ?>
            </td>
        </tr>
        <?php
                }
                ?>
        <?php
                foreach($bookingItemsAndSummary['summaryItems'] as $item){
                    ?>
        <tr class=" {{ $item['type'] }}">
            <td class="fs-12 i-data">
                {!! array_key_exists('extraData', $item) ? $item['extraData'] : '' !!}
            </td>
            <td class="fs-14 font-weight-bold i-name text-uppercase text-right"><?= $item['name'] ?>
            </td>
            <td class="text-right i-amount">{{ $item['amount'] }}</td>
        </tr>
        <?php
                }
                ?>
    </tbody>
</table>
