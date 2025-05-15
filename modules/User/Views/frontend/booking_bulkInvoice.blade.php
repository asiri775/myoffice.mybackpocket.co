<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
<div style="padding: 20px">
    <h2>Bookings</h2>
    <table>
        <tr>
            <th>#ID</th>
            <th>Booking</th>
            <th>Booking Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Amount</th>
            <th>Transaction Status</th>
        </tr>
        @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ \Modules\Space\Models\Space::where('id', $booking->object_id)->first()->translateOrOrigin(app()->getLocale())->title }}</td>
                <td>{{ str_replace('_', ' ', strtoupper($booking->status)) }}</td>
                <td>{{ $booking->start_date }}</td>
                <td>{{ $booking->end_date }}</td>
                <td>{{ $booking->total }}</td>
                <td>{{ $booking->is_paid == 1 ? 'PAID' : 'UNPAID' }}</td>
            </tr>
        @endforeach

    </table>
</div>

</body>
</html>

