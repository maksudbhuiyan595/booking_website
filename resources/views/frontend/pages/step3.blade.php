@extends('frontend.pages.master')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #ffffff !important;
            font-family: "Inter", sans-serif;
            color: #333;
            margin-top: 25px;
        }

        /* --- Page Header --- */
        .page-title {
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
            font-size: 1.75rem;
        }

        .step-text {
            color: #999;
            font-weight: 500;
            margin-bottom: 30px;
        }

        /* --- LEFT COLUMN: FORM AREA --- */
        .form-container {
            background: #f4f4f4;
            padding: 25px;
            border-radius: 4px;
        }

        /* "Are you also the traveler" Bar */
        .traveler-bar {
            background: #dcdcdc;
            padding: 12px 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            font-weight: 700;
            color: #333;
        }

        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Inputs */
        .form-label {
            font-size: 0.85rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 0.95rem;
            background: #fff;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0FA96D;
        }

        .text-danger {
            color: #dc3545 !important;
            margin-left: 2px;
        }

        /* Green Button */
        .btn-submit {
            background: #006644;
            color: #fff;
            font-weight: 700;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            float: right;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #004d33;
            color: #fff;
        }

        .payment-note-small {
            font-size: 0.75rem;
            color: #555;
            margin-top: 15px;
            clear: both;
            text-align: center;
            line-height: 1.4;
        }

        /* --- RIGHT COLUMN: YELLOW SIDEBAR --- */
        .sidebar-yellow {
            background-color: #fdf5d3;
            padding: 20px;
            border-radius: 4px;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 10px;
        }

        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #555;
            margin: 0;
        }

        .btn-change {
            background: #888;
            color: #fff;
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-change:hover {
            color: #fff;
            background: #666;
        }

        /* Summary Table Styles */
        .summary-table {
            width: 100%;
            font-size: 0.85rem;
            color: #333;
            margin-bottom: 20px;
        }

        .summary-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .summary-table td:first-child {
            font-weight: 700;
            width: 110px;
            color: #222;
        }

        .summary-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        .price-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eaddbc;
        }

        /* Discount Boxes */
        .discount-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            position: relative;
        }

        .discount-badge {
            position: absolute;
            top: -10px;
            left: -10px;
            background: #ff0000;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            z-index: 2;
        }

        .discount-box {
            background: #fff;
            flex: 1;
            text-align: center;
            padding: 10px 5px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #eee;
        }

        .d-price {
            color: #ff0000;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .d-text {
            font-size: 0.7rem;
            color: #333;
            font-weight: 600;
            margin-top: 2px;
        }

        .d-sub {
            font-size: 0.65rem;
            color: #777;
        }

        @media(max-width: 768px) {
            .traveler-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn-submit {
                width: 100%;
                float: none;
                margin-top: 10px;
            }
        }
    </style>

    <div class="container my-4">

        <h2 class="page-title">Passenger Information</h2>
        <div class="step-text">Your Current Selection ( Step 3 Of 4 )</div>

        <form action="{{ route('step4') }}" method="GET">

            {{--
                =========================================================
                SMART HIDDEN INPUT GENERATOR (RECURSIVE)
                এটি নিশ্চিত করছে যে আগের সব ডাটা (Nested Array সহ) পরের পেজে যাচ্ছে।
                =========================================================
            --}}
            @php
                $renderHiddenInputs = function($data, $prefix = '') use (&$renderHiddenInputs) {
                    foreach ($data as $key => $value) {
                        $name = $prefix === '' ? $key : $prefix . '[' . $key . ']';
                        if (is_array($value)) {
                            $renderHiddenInputs($value, $name);
                        } else {
                            echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">' . PHP_EOL;
                        }
                    }
                };
                // _token বাদে সব ডাটা জেনারেট করা হচ্ছে
                $renderHiddenInputs(request()->except(['_token']));
            @endphp
            {{-- ========================================================= --}}


            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="form-container">
                        <div class="traveler-bar">
                            <span>Are you also the traveler ?</span>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_traveler" id="yesTraveler"
                                        value="yes" checked>
                                    <label class="form-check-label" for="yesTraveler">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input disabled class="form-check-input" type="radio" name="is_traveler" id="noTraveler"
                                        value="no">
                                    <label class="form-check-label" for="noTraveler">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Passenger Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="passenger_name" required
                                    value="{{ old('passenger_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Passenger Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="passenger_email" required
                                    value="{{ old('passenger_email') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Airline Name
                                    @if (($request->tripType ?? $request->trip_type) == 'fromAirport')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="text" class="form-control" name="airline_name"
                                    @if (($request->tripType ?? $request->trip_type) == 'fromAirport') required @endif>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Flight No.
                                    @if (($request->tripType ?? $request->trip_type) == 'fromAirport')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="text" class="form-control" name="flight_number"
                                    @if (($request->tripType ?? $request->trip_type) == 'fromAirport') required @endif>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Passenger Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" name="phone_country_code" style="max-width: 100px;">
                                        <option value="+1">USA (+1)</option>
                                        <option value="+44">UK (+44)</option>
                                        <option value="+880">BD (+880)</option>
                                    </select>
                                    <input type="tel" class="form-control" name="phone_number" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alternate Phone Number</label>
                                <input type="tel" class="form-control" name="alternate_phone">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Mailing Address</label>
                                <textarea class="form-control" name="mailing_address" rows="3"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Special Needs</label>
                                <textarea class="form-control" name="special_needs" rows="3"></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-submit">Continue to Payment</button>
                                <div class="payment-note-small">
                                    Pay only $1 & confirm your reservation. Balance is payable after service by cash or
                                    card.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">

                    <div class="sidebar-yellow">

                        <div class="sidebar-header">
                            <h3 class="sidebar-title">Booking Details</h3>
                            {{-- <a href="{{ route('home') }}" class="btn-change">Change</a> --}}
                        </div>

                        <table class="summary-table">
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>
                                    @if (($request->trip_type ?? '') == 'fromAirport')
                                        Ride From Airport
                                    @elseif(($request->trip_type ?? '') == 'toAirport')
                                        Ride To Airport
                                    @else
                                        Door to Door
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>:</td>
                                <td>{{ $request->date }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>:</td>
                                <td>{{ $request->time }}</td>
                            </tr>
                            <tr>
                                <td>Pick up</td>
                                <td>:</td>
                                <td>{{ $request->pickup ?? $request->pickup_formatted ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Drop off</td>
                                <td>:</td>
                                <td>{{ $request->dropoff ?? $request->dropoff_formatted ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Passengers</td>
                                <td>:</td>
                                <td>{{ $request->reqPassengers }} ({{ $request->adults }}
                                    Adults + {{ $request->child_seat}} Children)</td>
                            </tr>
                            <tr>
                            <td>Luggage</td>
                            <td>:</td>
                            <td>{{ $request['luggage'] ?? 0 }}</td>
                        </tr>

                        {{-- SEATS & EXTRAS --}}
                        @if(($request['infant_seat'] ?? 0) > 0)
                        <tr>
                            <td>Infant Seat</td>
                            <td>:</td>
                            <td>{{ $request['infant_seat'] }}</td>
                        </tr>
                        @endif

                        @if(($request['booster_seat'] ?? 0) > 0)
                        <tr>
                            <td>Booster Seat</td>
                            <td>:</td>
                            <td>{{ $request['booster_seat'] }}</td>
                        </tr>
                        @endif

                        @if(($request['front_seat'] ?? 0) > 0)
                        <tr>
                            <td>Front Facing</td>
                            <td>:</td>
                            <td>{{ $request['front_seat'] }}</td>
                        </tr>
                        @endif

                        @if(($request['stopover'] ?? 0) > 0)
                        <tr>
                            <td>Stopover</td>
                            <td>:</td>
                            <td>{{ $request['stopover'] }}</td>
                        </tr>
                        @endif
                        @if(($request['pets'] ?? 0) > 0)
                        <tr>
                            <td>Pets</td>
                            <td>:</td>
                            <td>{{ $request['pets'] }}</td>
                        </tr>
                        @endif
                        </table>

                        <div class="sidebar-header price-section">
                            <h3 class="sidebar-title">Vehicle & Price</h3>
                            {{-- <a href="{{ route('step2', $request->all()) }}" class="btn-change">Change</a> --}}
                        </div>

                        <table class="summary-table">
                            <tr>
                                <td>Vehicle</td>
                                <td>:</td>
                                <td>{{ $request->fare['name'] ?? $request->vehicle_display_name ?? 'Luxury Van' }}</td>
                            </tr>
                            <tr>
                                <td>Distance</td>
                                <td>:</td>
                                <td>{{ number_format($request->distance_miles, 2) }} Miles</td>
                            </tr>
                            <tr>
                                <td>Base Fare</td>
                                <td>:</td>
                                <td>${{ number_format($request->fare['estimatedFare'] ?? $request->fare['base_fare'] ?? 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Gratuity (20%)</td>
                                <td>:</td>
                                <td>${{ number_format($request->fare['gratuity'] ?? 0, 2) }}</td>
                            </tr>

                            {{-- TAXES --}}
                            @if (($request->fare['pickup_tax'] ?? 0) > 0)
                                <tr>
                                    <td>Pickup Tax</td>
                                    <td>:</td>
                                    <td>${{ number_format($request->fare['pickup_tax'], 2) }}</td>
                                </tr>
                            @endif

                            @if (($request->fare['dropoff_tax'] ?? 0) > 0)
                                <tr>
                                    <td>Dropoff Tax</td>
                                    <td>:</td>
                                    <td>${{ number_format($request->fare['dropoff_tax'], 2) }}</td>
                                </tr>
                            @endif

                             @if (($request->fare['toll_fee'] ?? 0) > 0)
                                <tr>
                                    <td>Toll Fee</td>
                                    <td>:</td>
                                    <td>${{ number_format($request->fare['toll_fee'], 2) }}</td>
                                </tr>
                            @endif

                            {{-- EXTRA CHARGES LOOP --}}
                            @if (isset($request->extra_charge_details) && is_array($request->extra_charge_details))
                                @foreach ($request->extra_charge_details as $charge)
                                    <tr>
                                        <td>{{ $charge['name'] ?? 'Extra' }}</td>
                                        <td>:</td>
                                        <td>${{ number_format($charge['amount'] ?? $charge['price'] ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- SURCHARGES LOOP --}}
                            @if (isset($request->surcharge_details) && is_array($request->surcharge_details))
                                @foreach ($request->surcharge_details as $surcharge)
                                    <tr>
                                        <td>{{ $surcharge['name'] ?? 'Surcharge' }}</td>
                                        <td>:</td>
                                        <td>${{ number_format($surcharge['amount'] ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (($request->fare['extra_luggage_fee'] ?? 0) > 0)
                                <tr>
                                    <td>Extra Luggage</td>
                                    <td>:</td>
                                    <td>${{ number_format($request->fare['extra_luggage_fee'], 2) }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td style="color:#222; font-size:1.1rem; padding-top:15px;">Total Payable</td>
                                <td style="padding-top:15px;">:</td>
                                <td style="color:#222; font-weight:800; font-size:1.1rem; padding-top:15px;">
                                    ${{ number_format($request->fare['total'] ?? 0, 2) }}
                                </td>
                            </tr>
                        </table>

                        {{-- Cash vs Card Discount Box --}}
                        <div class="discount-container">
                            <div class="discount-badge">%</div>
                            <div class="discount-box">
                                <div class="d-price">${{ number_format(($request->fare['total'] * 0.9 ?? 0), 2) }}
                                </div>
                                <div class="d-text">PAY CASH</div>
                                <div class="d-sub">$1 reservation fee</div>
                            </div>
                            <div class="discount-box">
                                <div class="d-price">${{ number_format($request->fare['total'] ?? 0, 2) }}</div>
                                <div class="d-text">PAY CARD</div>
                                <div class="d-sub">Full Price</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
