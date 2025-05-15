<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Listing Name</th>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Amount</th>
            <th>Book Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ strip_tags($item->guest) }}</td>
                <td>{{ strip_tags($item->title) }}</td>
                <td>{{ strip_tags($item->id) }}</td>
                <td>{{ strip_tags($item->start_date) }}</td>
                <td>{{ strip_tags($item->totalFormatted) }}</td>
                <td>{{ strip_tags($item->booking_status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
