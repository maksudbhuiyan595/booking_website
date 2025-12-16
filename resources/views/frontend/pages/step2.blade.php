@extends('frontend.pages.master')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body {
        background: #ffffff !important;
        font-family: "Inter", sans-serif;
        color: #333;
        margin-top: 25px;
    }

    /* --- Breadcrumbs --- */
    .breadcrumb-nav {
        font-size: 0.85rem;
        color: #0FA96D;
        margin-bottom: 20px;
        padding-top: 20px;
    }
    .breadcrumb-nav a {
        color: #666;
        text-decoration: none;
    }
    .breadcrumb-nav span {
        margin: 0 5px;
        color: #999;
    }

    /* --- Page Headers --- */
    .page-title {
        font-weight: 800;
        color: #222;
        margin-bottom: 5px;
    }
    .step-text {
        color: #999;
        font-weight: 500;
        margin-bottom: 30px;
    }

    /* --- LEFT COLUMN: Vehicle Card --- */
    .vehicle-card-dark {
        background: #2c3e50; /* Dark Slate Color */
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Simulate the city background graphic using a gradient or image if you have one */
    .vehicle-card-dark::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
        z-index: 0;
    }

    .info-icon-top {
        position: absolute;
        top: 10px;
        right: 15px;
        background: #fff;
        color: #333;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 0.8rem;
        line-height: 20px;
        cursor: pointer;
    }

    .v-img-container {
        position: relative;
        z-index: 1;
        margin-bottom: 10px;
    }

    .v-img {
        width: 100%;
        max-width: 250px;
        filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
    }

    .v-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 25px;
        position: relative;
        z-index: 1;
    }

    .v-icons-row {
        display: flex;
        justify-content: space-around;
        position: relative;
        z-index: 1;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 15px;
    }

    .v-icon-box i {
        font-size: 1.8rem;
        margin-bottom: 5px;
        display: block;
    }
    .v-icon-box span {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    /* --- LEFT COLUMN: Payment Box --- */
    .payment-options-box {
        background: #f9f9f9;
        border: 1px solid #eee;
        margin-top: 20px;
        position: relative;
    }
    .discount-badge {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #ff0000;
        color: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
    }
    .pay-col {
        padding: 15px;
        text-align: center;
    }
    .pay-col.left-col {
        border-right: 1px solid #ddd;
    }
    .price-red { color: #ff0000; font-weight: 700; font-size: 1.2rem; }
    .price-black { color: #333; font-weight: 700; font-size: 1.2rem; }
    .pay-sub { font-size: 0.8rem; color: #555; margin-top: 4px; }
    .pay-note {
        font-size: 0.75rem;
        color: #555;
        margin-top: 10px;
        line-height: 1.4;
    }

    /* --- MIDDLE COLUMN: Pricing --- */
    .details-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #444;
        margin-bottom: 20px;
    }
    .pricing-table {
        width: 100%;
        font-size: 0.95rem;
        color: #444;
    }
    .pricing-table td {
        padding: 8px 0;
        vertical-align: top;
    }
    .pricing-table td:first-child {
        font-weight: 600;
        width: 50%;
    }
    .pricing-table td:nth-child(2) {
        text-align: center;
        width: 20px;
    }
    .pricing-table td:last-child {
        text-align: right;
        font-weight: 700;
    }

    .extra-luggage-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .el-price {
        text-align: right;
    }
    .el-total { font-weight: 700; font-size: 1.1rem; color: #333; }
    .el-per { font-size: 0.75rem; color: #666; }

    /* --- RIGHT COLUMN: Summary & Button --- */
    .btn-book-green {
        background-color: #006644; /* Dark Green */
        color: #fff;
        font-weight: 600;
        width: 100%;
        padding: 12px;
        border-radius: 5px;
        border: none;
        font-size: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn-book-green:hover { background-color: #004d33; color: #fff; }

    .summary-yellow-box {
        background-color: #fffbeb; /* Light Beige/Yellow */
        border: 1px solid #f3eacb;
        padding: 20px;
        margin-top: 25px;
        border-radius: 4px;
    }
    .summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .summary-title { font-size: 1.1rem; font-weight: 700; color: #555; }
    .btn-change {
        background: #888;
        color: #fff;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 3px;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-change:hover { color: #fff; background: #666; }

    .summary-list td {
        padding: 6px 0;
        font-size: 0.9rem;
        vertical-align: top;
        color: #333;
    }
    .summary-list td:first-child {
        font-weight: 600;
        width: 100px;
    }
    .summary-list td:nth-child(2) { width: 15px; }

    .vehicle-details-section {
        margin-top: 20px;
        border-top: 1px solid #eaddbc;
        padding-top: 15px;
    }
    .v-det-title { font-size: 1.1rem; font-weight: 700; color: #555; margin-bottom: 10px; }

</style>

    <div class="container mb-5">
        <div class="breadcrumb-nav">
            <a href="#">Home</a> <span>&raquo;</span>
            <a href="#">Reservation</a> <span>&raquo;</span>
            <span style="color:#0FA96D">Choose vehicle ( Step 2 of 4 )</span>
        </div>

        <h2 class="page-title">Select Vehicle & Confirm Ride Details</h2>
        <div class="step-text">Your Current Selection ( Step 2 of 4 )</div>

        <form action="{{ route('step3') }}" method="GET">
            @foreach(['date','time','tripType','pickup_address','dropoff_address','adults','children','luggage'] as $field)
                <input type="hidden" name="{{ $field }}" value="{{ $request[$field] ?? '' }}">
            @endforeach

            <div class="row g-4">

                {{-- LEFT COLUMN: Vehicle Card --}}
                <div class="col-lg-4">
                    <div class="vehicle-card-dark">
                        <div class="info-icon-top"><i class="fas fa-info"></i></div>

                        <div class="v-img-container">
                            <img src="{{ asset('images/cars11.webp') }}" class="v-img" alt="Car">
                        </div>

                        <div class="v-name">6 Passenger Luxury Van 12<br>Bags Capacity</div>

                        <div class="v-icons-row">
                            <div class="v-icon-box">
                                <i class="fas fa-user"></i>
                                <span>{{ ((int)($request['adults'] ?? 0) + (int)($request['children'] ?? 0)) }}<br>Passengers</span>
                            </div>
                            <div class="v-icon-box">
                                <i class="fas fa-suitcase"></i>
                                <span>{{ $request['luggage'] }}<br>Luggage</span>
                            </div>
                            <div class="v-icon-box">
                                <i class="fas fa-baby"></i>
                                <span>{{ $request['seats_dummy'] }}<br>Child Seat</span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-options-box">
                        <div class="discount-badge">%</div>
                        <div class="d-flex">
                            <div class="pay-col left-col w-50">
                                <div class="fw-bold">Pay Cash : <span class="price-red">${{ number_format($fare['total'] * 0.9, 2) }}</span></div>
                                <div class="pay-sub">Get 10% Discount</div>
                            </div>
                            <div class="pay-col w-50">
                                <div class="fw-bold">Pay By Card : <span class="price-black">${{ number_format($fare['total'], 2) }}</span></div>
                                <div class="pay-sub">Pay full online</div>
                            </div>
                        </div>
                    </div>
                    <div class="pay-note">
                        Pay only $1 & confirm your reservation. Balance is payable after service by cash or card. Avail 10% discount on cash payment.
                    </div>
                </div>

                {{-- MIDDLE COLUMN: Fare Breakdown --}}
                <div class="col-lg-4">
                    <h3 class="details-title">Booking Details</h3>

                    <table class="pricing-table">
                        <tr>
                            <td>Distance Covered</td>
                            <td>:</td>
                            <td>{{ number_format($distance_miles, 2) }} Miles</td>
                        </tr>
                        <tr>
                            <td>Base Fare</td>
                            <td>:</td>
                            <td>${{ number_format($fare['base_fare'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Distance Fare</td>
                            <td>:</td>
                            <td>${{ number_format($fare['distance_fare'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Gratuity</td>
                            <td>:</td>
                            <td>${{ number_format($fare['gratuity'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Pickup Tax</td>
                            <td>:</td>
                            <td>${{ number_format($fare['pickup_tax'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Dropoff Tax</td>
                            <td>:</td>
                            <td>${{ number_format($fare['dropoff_tax'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Parking Fee</td>
                            <td>:</td>
                            <td>${{ number_format($fare['parking_fee'], 2) }}</td>
                        </tr>
                        <tr>
                            <td>Extra Luggage Fee</td>
                            <td>:</td>
                            <td>${{ number_format($fare['extra_luggage_fee'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="pt-3">Total Payable</td>
                            <td class="pt-3">:</td>
                            <td class="pt-3">${{ number_format($fare['total'], 2) }}</td>
                        </tr>
                    </table>

                    @if($fare['extra_luggage_fee'] > 0)
                    <div class="extra-luggage-card">
                        <span class="fw-bold text-dark">Extra Luggage</span>
                        <div class="el-price">
                            <div class="el-total">${{ number_format($fare['extra_luggage_fee'], 2) }}</div>
                            <div class="el-per">(${{ number_format($fare['extra_luggage_fee'] / 2, 2) }}/Bag)</div>
                        </div>
                    </div>
                    <div style="font-size: 0.8rem; color: #555;">
                        *4 bags Free with this car. Select Extra luggage as required
                    </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Summary & Book --}}
                <div class="col-lg-4">
                    <button type="submit" class="btn-book-green">Book Now</button>
                    <div class="text-center mt-2 mb-4" style="font-size: 0.8rem; color: #333;">
                        Pay only $1 & confirm your reservation. Balance is payable after service
                    </div>

                    <div class="summary-yellow-box">
                        <div class="summary-header">
                            <div class="summary-title">Booking Details</div>
                            <a href="{{ route('home') }}" class="btn-change">Change</a>
                        </div>


                        <table class="summary-list">
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>{{ $trip_type == 'fromAirport' ? 'Ride From Airport' : 'Ride To Airport' }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>:</td>
                                <td>{{ $request['date'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>:</td>
                                <td>{{ $request['time'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Pick up</td>
                                <td>:</td>
                                <td>{{  $pickup ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Drop off</td>
                                <td>:</td>
                                <td>{{ $dropoff ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Passengers</td>
                                <td>:</td>
                                <td>{{ ((int)($request['adults'] ?? 0) + (int)($request['seats_dummy'] ?? 0)) }} ({{ $request['adults'] ?? 0 }} Adults + {{ $request['seats_dummy'] ?? 0 }} Children)</td>
                            </tr>
                            <tr>
                                <td>Luggage</td>
                                <td>:</td>
                                <td>{{ $request['luggage'] ?? 0 }} (4 Bags Free + {{ max(0, ($request['luggage'] ?? 0)-4) }} Extra)</td>
                            </tr>
                        </table>

                        <div class="vehicle-details-section">
                            <div class="v-det-title">Vehicle Details</div>
                            <table class="summary-list">
                                <tr>
                                    <td>Vehicle</td>
                                    <td>:</td>
                                    <td>6 Passenger Luxury Van 12 Bags Capacity</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>


@endsection
