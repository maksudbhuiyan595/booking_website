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

        .highlight-red {
            color: #d32f2f;
            font-weight: bold;
        }

        .highlight-green {
            color: #2e7d32;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .sub-row td {
            padding-left: 20px;
            color: #666;
            font-style: italic;
            font-size: 12px;
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
        <p>Your booking has been successfully placed. We have received your partial payment via
            <strong>{{ ucfirst($booking->payment_method) }}</strong>.</p>

        <div class="section-title">Passenger Details</div>
        <table>
            <tr>
                <td class="label">Name:</td>
                <td class="value">{{ $booking->passenger_name }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td class="value">{{ $booking->passenger_email }}</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td class="value">{{ $booking->phone_country_code }} {{ $booking->passenger_phone }}</td>
            </tr>

            @if ($booking->alternate_phone)
                <tr>
                    <td class="label">Alt. Phone:</td>
                    <td class="value">{{ $booking->alternate_phone }}</td>
                </tr>
            @endif

            @if ($booking->mailing_address)
                <tr>
                    <td class="label">Address:</td>
                    <td class="value">{{ $booking->mailing_address }}</td>
                </tr>
            @endif

            @if ($booking->special_needs)
                <tr>
                    <td class="label highlight-red">Special Needs:</td>
                    <td class="value highlight-red">{{ $booking->special_needs }}</td>
                </tr>
            @endif
        </table>

        <div class="section-title">Trip Information</div>
        <table>
            <tr>
                <td class="label">Service Type:</td>
                <td class="value">
                    @if ($booking->trip_type == 'fromAirport')
                        From Airport
                    @elseif($booking->trip_type == 'toAirport')
                        To Airport
                    @else
                        Door to Door
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Pickup Date:</td>
                <td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('l, d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Pickup Time:</td>
                <td class="value">{{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</td>
            </tr>
            <tr>
                <td class="label">Pickup Location:</td>
                <td class="value">{{ $booking->pickup_address }}</td>
            </tr>
            <tr>
                <td class="label">Dropoff Location:</td>
                <td class="value">{{ $booking->dropoff_address }}</td>
            </tr>
            <tr>
                <td class="label">Total Distance:</td>
                <td class="value">{{ $booking->distance_miles }} Miles</td>
            </tr>

            @if ($booking->airline_name)
                <tr>
                    <td class="label">Airline Info:</td>
                    <td class="value">{{ $booking->airline_name }} (Flight: {{ $booking->flight_number }})</td>
                </tr>
            @endif
        </table>

        <div class="section-title">Vehicle & Passengers</div>
        <table>
            <tr>
                <td class="label">Selected Vehicle:</td>
                <td class="value">Luxury Vehicle</td>
            </tr>
            <tr>
                <td class="label">Total Passengers:</td>
               @php
                    $extraSeats =
                        ($booking->infant_seat_count ?? 0)
                    + ($booking->booster_seat_count ?? 0)
                    + ($booking->front_seat_count ?? 0)
                    + ($booking->stopover_count ?? 0)
                    + ($booking->pet_count ?? 0);

                    $totalSeats = ($booking->total_passengers ?? 0) + $extraSeats;
                @endphp

                <td class="value">
                    {{ $totalSeats }}
                    ({{ $booking->adults }} Adults, {{ $booking->children }} Childrens

                    @if ($booking->infant_seat_count > 0)
                        , {{ $booking->infant_seat_count }} Infants
                    @endif

                    @if ($booking->booster_seat_count > 0)
                        , {{ $booking->booster_seat_count }} Boosters
                    @endif

                    @if ($booking->front_seat_count > 0)
                        , {{ $booking->front_seat_count }} Front Facing
                    @endif

                    @if ($booking->stopover_count > 0)
                        ,{{ $booking->stopover_count }} Stopovers
                    @endif

                    @if ($booking->pet_count > 0)
                        , {{ $booking->pet_count }} Pets
                    @endif )
                </td>
            </tr>
            <tr>
                <td class="label">Luggage:</td>
                <td class="value">{{ $booking->luggage }} Bags</td>
            </tr>
        </table>

        <table>
            <tr>
                <td colspan="2" style="border-top: 2px solid #333;"></td>
            </tr>

            <tr style="font-size: 16px;">
                <td class="label">TOTAL FARE:</td>
                <td class="value" style="font-weight:bold;">${{ number_format($booking->total_fare, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Paid via {{ ucfirst($booking->payment_method) }}:</td>
                <td class="value highlight-green">${{ number_format($booking->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Balance Due (To Driver):</td>
                <td class="value highlight-red">${{ number_format($booking->due_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Pay Cash (10% discount):</td>
                <td class="value highlight-red">${{ number_format($booking->due_amount * 0.9, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>If you have questions, reply to this email or call us at +1857-331-9544.</p>
            <p>&copy; {{ date('Y') }} Boston Logan Airport Taxi. All Rights Reserved.</p>
        </div>
    </div>

</body>

</html>
