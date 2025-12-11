@extends('frontend.pages.master')

@section('content')

<style>
    body {
        background: #ffffff !important;
        font-family: "Inter", sans-serif;
    }

    .page-header {
        padding: 40px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #eaeaea;
    }

    .step-text {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Cards */
    .custom-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
    }

    .vehicle-img-box {
        padding: 25px;
        background: #f7f9fc;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    .vehicle-img {
        width: 100%;
        max-width: 260px;
    }

    .vehicle-title {
        text-align: center;
        padding: 15px 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
    }

    .vehicle-stats {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        text-align: center;
    }

    .stat-item i {
        font-size: 1.6rem;
        color: #0FA96D;
        margin-bottom: 8px;
    }

    .stat-item span {
        font-weight: 600;
        color: #333;
    }

    /* Booking Details */
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #ddd;
        font-size: 0.95rem;
        color: #333;
    }

    .total-row {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0FA96D;
        padding-top: 15px;
    }

    .extra-luggage-box {
        background: #f8f9fc;
        border: 1px solid #e3e3e3;
        padding: 15px;
        border-radius: 8px;
        margin-top: 12px;
    }

    /* Summary Card */
    .summary-card {
        border-left: 4px solid #0FA96D;
        background: #fdfdfd;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .s-label {
        width: 110px;
        font-weight: 600;
        color: #333;
    }

    .s-value {
        color: #555;
        font-weight: 500;
    }

    /* Book Button */
    .btn-book {
        background: #0FA96D;
        color: #fff;
        padding: 13px;
        width: 100%;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        transition: 0.2s;
    }

    .btn-book:hover {
        background: #0c8b59;
    }

    .payment-note {
        font-size: 0.85rem;
        margin-top: 8px;
        color: #6c757d;
    }
</style>

<div class="container page-header">
    <h2 class="fw-bold m-0">Select Vehicle & Confirm Ride Details</h2>
    <div class="step-text">Your Current Selection (Step 2 of 4)</div>
</div>

<div class="container">

    <form action="{{ route('step3') }}" method="GET">

        @foreach(['date','time','tripType','pickup_address','dropoff_address','adults','children','luggage'] as $field)
            <input type="hidden" name="{{ $field }}" value="{{ $request[$field] }}">
        @endforeach

        <input type="hidden" name="final_total" value="5879">

        <div class="row g-4">

            <!-- VEHICLE -->
            <div class="col-lg-4">
                <div class="custom-card">
                    <div class="vehicle-img-box">
                        <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=1000&auto=format&fit=crop"
                             class="vehicle-img" alt="Van">
                    </div>
                    <div class="vehicle-title">7 Passenger Luxury Van</div>

                    <div class="vehicle-stats">
                        <div class="stat-item">
                            <i class="fas fa-user"></i>
                            <span>7<br>Passengers</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-suitcase"></i>
                            <span>4<br>Luggage</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-baby"></i>
                            <span>4<br>Child Seat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRICE DETAILS -->
            <div class="col-lg-4">
                <div class="custom-card p-3">

                    <h4 class="fw-bold mb-3">Booking Breakdown</h4>

                    <div class="price-row"><span>Distance Covered</span><span>960 Miles</span></div>
                    <div class="price-row"><span>Estimated Fare</span><span>$4870</span></div>
                    <div class="price-row"><span>Gratuity (20%)</span><span>$974</span></div>
                    <div class="price-row"><span>Airport Toll Tax</span><span>$15</span></div>
                    <div class="price-row"><span>Night Charges</span><span>$10</span></div>
                    <div class="price-row">
                        <span>Child Seat / Stopover</span>
                        <span>${{ $request->extras_total }}</span>
                    </div>

                    <div class="price-row total-row">
                        <span>Total Payable</span>
                        <span>$5879</span>
                    </div>

                    <div class="extra-luggage-box">
                        <strong>Extra Luggage</strong>
                        <div class="text-end">
                            <div class="fw-bold fs-5">$0</div>
                            <div style="font-size: 0.75rem;">($10.00/Bag)</div>
                        </div>
                    </div>

                    <p class="mt-2 text-muted" style="font-size: 0.8rem;">* 4 bags Free with this car. Extra charges apply above limit.</p>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="col-lg-4">
                <button class="btn-book" type="submit">Book Now</button>
                <div class="payment-note">Pay only $1 to confirm your reservation.</div>

                <div class="summary-card p-4 mt-4">
                    <h5 class="fw-bold mb-3">Booking Summary</h5>

                    <div class="d-flex mb-2">
                        <span class="s-label">Service</span>
                        <span class="s-value">
                            {{ $request->tripType == 'fromAirport' ? 'Ride From Airport' : 'Door to Door' }}
                        </span>
                    </div>

                    <div class="d-flex mb-2"><span class="s-label">Date</span><span class="s-value">{{ $request->date }}</span></div>
                    <div class="d-flex mb-2"><span class="s-label">Time</span><span class="s-value">{{ $request->time }}</span></div>
                    <div class="d-flex mb-2"><span class="s-label">Pickup</span><span class="s-value">{{ $request->pickup_address }}</span></div>
                    <div class="d-flex mb-2"><span class="s-label">Dropoff</span><span class="s-value">{{ $request->dropoff_address }}</span></div>

                    <div class="d-flex mb-2">
                        <span class="s-label">Passengers</span>
                        <span class="s-value">
                            {{ (int)$request->adults + (int)$request->children }}
                            <small class="text-muted"> ({{ $request->adults }} Adults + {{ $request->children }} Children)</small>
                        </span>
                    </div>

                    <div class="d-flex mb-2">
                        <span class="s-label">Luggage</span>
                        <span class="s-value">{{ $request->luggage }}</span>
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

@endsection
