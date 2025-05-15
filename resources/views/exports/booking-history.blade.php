<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Listing Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Guest</th>
            <th>Amount</th>
            <th>Book Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ strip_tags($item->id) }}</td>
                <td>{{ strip_tags($item->title) }}</td>
                <td>{{ strip_tags($item->start_date) }}</td>
                <td>{{ strip_tags($item->end_date) }}</td>
                <td>{{ strip_tags($item->guest) }}</td>
                <td>{{ strip_tags($item->totalFormatted) }}</td>
                <td>{{ strip_tags($item->booking_status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
