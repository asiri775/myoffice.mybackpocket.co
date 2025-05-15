<table class="booking-table-listing">
    <tbody>
        <tr style="border-bottom: 1px solid #ddd;">
            <td colspan="2" width="80%">{{ __('Space Rental Fee') }}</td> 
            <td width="20%">{{ format_money($priceDetails['price']) }}</td>
        </tr>
        @php $extra_price = ($priceDetails['extraFeeList']) @endphp
        @if (!empty($extra_price))
            @foreach ($extra_price as $type)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td colspan="2" width="80%">{{ $type['name'] }}</td>
                    <td width="20%">{{ format_money($type['totalAmount'] ?? 0) }}</td>
                </tr>
            @endforeach
        @endif
        <tr style="border-bottom: 2px solid #ddd;">
            <td colspan="2" width="80%" style="font-weight:600;">{{ __('Rental Total') }}</td>
            <td width="20%" style="font-weight:600;">{{ format_money($priceDetails['rentalTotal']) }}</td>
        </tr>
        @php $guestPrices = ($priceDetails['guestFeeList']) @endphp
        @if (!empty($guestPrices))
            @foreach ($guestPrices as $guestPrice)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td colspan="2" width="80%">{{ $guestPrice['name'] }}</td>
                    <td width="20%">{{ format_money($guestPrice['totalAmount'] ?? 0) }}</td>
                </tr>
            @endforeach
        @endif
        <tr style="border-bottom: 2px solid #ddd;">
            <td colspan="2" width="80%" style="font-weight:600;">{{ __('SUBTOTAL') }}</td>
            <td width="20%" style="font-weight:600;">
                {{ format_money($priceDetails['subTotal']) }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #ddd;">
            <td colspan="2" width="80%">
                {{ \App\Helpers\Constants::TAX_PERCENT_LABEL }}</td>
            <td width="20%">{{ format_money($priceDetails['tax']) }}</td>
        </tr>
        <?php
        if($priceDetails['discount'] > 0) {
            ?>
        <tr style="border-bottom: 2px solid #ddd;">
            <td colspan="2" width="80%" style="font-size: 13px;">{{ __('TOTAL') }}</td>
            <td width="20%" style="font-size: 13px;">
                {{ format_money($priceDetails['total']) }}
            </td>
        </tr>
        <tr style="border-bottom: 2px solid #ddd;">
            <td colspan="2" width="80%" style="font-weight:600;">{{ __('Coupon Discount') }}</td>
            <td width="20%" style="color: green" style="font-weight:600;">-{{ format_money($priceDetails['discount']) }}</td>
        </tr>
        <?php
        }
        ?>
        <tr >
            <td colspan="2" width="80%" style="font-size: 14px; font-weight:600;">
                {{ __('AMOUNT DUE') }}</td>
            <td width="20%" style="font-size: 14px; font-weight:600;">
                {{ format_money($priceDetails['payableAmount']) }}
            </td>
        </tr>
    </tbody>
</table>
