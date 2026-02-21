<!DOCTYPE html>
<html>

<head>
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0FA96D;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header h2 {
            color: #0FA96D;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .booking-ref {
            font-size: 16px;
            color: #555;
            margin-top: 5px;
        }

        .section-title {
            background: #f8f9fa;
            padding: 10px 15px;
            font-weight: 700;
            border-left: 5px solid #0FA96D;
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 13px;
        }

        td {
            padding: 8px 10px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .label {
            font-weight: 600;
            width: 45%;
            color: #555;
        }

        .value {
            color: #000;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>Booking Confirmed</h2>
            <div class="booking-ref">Reference No: <strong>{{ $booking->booking_no }}</strong></div>
        </div>

        <p>Dear <strong>{{ $booking->passenger_name }}</strong>,</p>

        <!-- [UPDATED TEXT] More professional message -->
        <p>We are pleased to inform you that your booking has been successfully created and confirmed by our team.
            Please review the details below.</p>

        <!-- Trip Information -->
        <div class="section-title">Trip Information</div>
        <table>
            <tr>
                <td class="label">Service Type:</td>
                <td class="value">
                    @if ($booking->trip_type == 'fromAirport') From Airport
                    @elseif($booking->trip_type == 'toAirport') To Airport
                    @else Door to Door @endif
                </td>
            </tr>

            @if($booking->pickup_date)
            <tr>
                <td class="label">Pickup Date:</td>
                <td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, d M Y') }}</td>
            </tr>
            @endif

            @if($booking->pickup_time)
            <tr>
                <td class="label">Pickup Time:</td>
                <td class="value">{{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</td>
            </tr>
            @endif

            @if($booking->pickup_address)
            <tr>
                <td class="label">Pickup Location:</td>
                <td class="value">{{ $booking->pickup_address }}</td>
            </tr>
            @endif

            @if($booking->dropoff_address)
            <tr>
                <td class="label">Dropoff Location:</td>
                <td class="value">{{ $booking->dropoff_address }}</td>
            </tr>
            @endif

            @if (!empty($booking->flight_number))
            <tr>
                <td class="label">Flight Info:</td>
                <td class="value">{{ $booking->airline_name ?? '' }} (Flight: {{ $booking->flight_number }})</td>
            </tr>
            @endif
        </table>

        <!-- Vehicle & Payment (Only if available) -->
        @if($booking->vehicle_type || $booking->total_fare > 0)
        <div class="section-title">Booking Details</div>
        <table>
            @if($booking->vehicle_type)
            <tr>
                <td class="label">Vehicle Type:</td>
                <td class="value">{{ $booking->vehicle_type }}</td>
            </tr>
            @endif

            @if($booking->total_fare > 0)
            <tr>
                <td class="label">Total Estimated Fare:</td>
                <td class="value" style="font-weight:bold;">${{ number_format($booking->total_fare, 2) }}</td>
            </tr>
            @endif

            <tr>
                <td class="label">Payment Status:</td>
                <td class="value" style="text-transform: capitalize;">{{ $booking->payment_status }}</td>
            </tr>
        </table>
        @endif

        <div class="footer">
            <p>If you have questions, reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
    </div>

</body>

</html>
