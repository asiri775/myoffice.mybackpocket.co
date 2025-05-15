<?php
$priceDetails = \App\Helpers\CodeHelper::getBookingPriceInfo($booking);
?>
<table class="table table-borderless">
    <tbody>
        <thead>
            <tr>
                <th style="width:50%">Item</th>
                <th style="width:35%;text-align: center;">QTY</th>
                <th style="width:15%;text-align: right;">Rate</th>
            </tr>
        </thead>
        <tr class="border-bottom">
            <td>Space Rental Fee</td>
            <td class="text-center">
                1.0
            </td>
            <td class="text-right">
                {{ format_money($priceDetails['price']) }}
            </td>
        </tr>
        @php $extra_price = ($priceDetails['extraFeeList']) @endphp
        @if (!empty($extra_price))
            @foreach ($extra_price as $type)
                <tr class="border-bottom">
                    <td>{{ $type['name'] }}</td>
                    <td class="text-center">
                        1.0
                    </td>
                    <td class="text-right">
                        {{ format_money($type['totalAmount'] ?? 0) }}
                    </td>
                </tr>
            @endforeach
        @endif
        {{-- <tr>
            <td class="fs-12"></td>
            <td class="fs-14 font-weight-bold text-uppercase text-right">Rental Total
            </td>
            <td class="text-right">{{ format_money($priceDetails['rentalTotal']) }}</td>
        </tr> --}}
        @php $guestPrices = ($priceDetails['guestFeeList']) @endphp
        @if (!empty($guestPrices))
            @foreach ($guestPrices as $guestPrice)
                <tr class="border-bottom">
                    <td>{{ $guestPrice['name'] }}</td>
                    <td class="text-center">
                        1.0
                    </td>
                    <td class="text-right">
                        {{ format_money($guestPrice['totalAmount'] ?? 0) }}
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td class="fs-12"></td>
            <td class="fs-14 font-weight-bold text-uppercase text-right">Subtotal
            </td>
            <td class="text-right">{{ format_money($priceDetails['subTotal']) }}</td>
        </tr>

        <tr>
            <td></td>
            <td class="fs-14 font-weight-bold text-uppercase text-right">
                {{ \App\Helpers\Constants::TAX_PERCENT_LABEL }}
            </td>
            <td class="text-right">{{ format_money($priceDetails['tax']) }}</td>
        </tr>

        <?php
        if($priceDetails['discount'] > 0) {
            ?>

        <tr>
            <td></td>
            <td class="fs-14 font-weight-bold text-uppercase text-right">
                TOTAL
            </td>
            <td class="text-right">{{ format_money($priceDetails['total']) }}</td>
        </tr>

        <tr>
            <td></td>
            <td class="fs-14 font-weight-bold text-uppercase text-right">
                Coupon Discount
            </td>
            <td class="text-right">{{ format_money($priceDetails['discount']) }}</td>
        </tr>

        <?php
        }
        ?>

        <tr>
            <td></td>
            <td class="fs-16 font-weight-bold text-uppercase bg-complete-lighter text-right">
                Grand Total
            </td>
            <td class="fs-18 bg-complete-lighter text-right" style="font-weight: 900;">
                {{ format_money($priceDetails['payableAmount']) }}
            </td>
        </tr>

    </tbody>
</table>
