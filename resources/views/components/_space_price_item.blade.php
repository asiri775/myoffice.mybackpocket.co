<table class="booking-table-listing">
    <thead>
        <tr>
            <th class="p-2 col text-center" style="font-weight:500;">QTY</th>
            <th class="p-2 col text-center" style="font-weight:500;">Units</th>
            <th class="p-2 col text-center" style="font-weight:500;">Rate</th>
            <th class="p-2 col text-center" style="font-weight:500;">Amount</th>
        </tr>
    </thead>
    <tbody style="border-bottom: 2px solid black;">
        <?php
            foreach($priceDetails['items'] as $typeItem){
                ?>
        <tr>
            <td class="col text-center">{{ $typeItem['quantity'] }}</td>
            <td class="col text-center" style="text-transform: capitalize;">{{ $typeItem['type'] }}</td>
            <td class="col text-center">{{ format_money($typeItem['rate']) }}</td>
            <td class="col text-center">{{ format_money($typeItem['total']) }}</td>
        </tr>
        <?php
            }
            ?>
    </tbody>
</table>
