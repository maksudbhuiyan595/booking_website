<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>Hello {{ $booking->passenger_name }},</h2>
    <p>Your booking has been confirmed successfully!</p>

    <h3>Booking Details:</h3>
    <ul>
        <li><strong>Booking No:</strong> {{ $booking->booking_no }}</li>
        <li><strong>Date:</strong> {{ $booking->pickup_date }} at {{ $booking->pickup_time }}</li>
        <li><strong>Pickup:</strong> {{ $booking->pickup_address }}</li>
        <li><strong>Dropoff:</strong> {{ $booking->dropoff_address }}</li>
        <li><strong>Vehicle:</strong> {{ $booking->vehicle_type }}</li>
        <li><strong>Total Paid:</strong> ${{ number_format($booking->paid_amount, 2) }}</li>
    </ul>

    <p>Thank you for choosing us!</p>
</body>
</html>
