<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Code</th>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Gross Sale</th>
            <th>Discount Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ strip_tags($item->clientName) }}</td>
                <td>{{ strip_tags($item->coupon_code) }}</td>
                <td>{{ strip_tags($item->orderId) }}</td>
                <td>{{ strip_tags($item->created_at) }}</td>
                <td>{{ strip_tags($item->grossSaleFormatted) }}</td>
                <td>{{ strip_tags($item->coupon_amount_formatted) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
