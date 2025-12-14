@extends('frontend.pages.master')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

<style>
    body {
        background: #ffffff !important;
        font-family: "Inter", sans-serif;
        color: #333;
        margin-top: 25px;
    }

    /* --- Page Headers --- */
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

    /* --- PAYMENT TOGGLE CARDS (Matches image_abee62.png) --- */
    .payment-toggles {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .toggle-card {
        flex: 1;
        background: #fff;
        border: 2px solid #e5e7eb; /* Default light gray border */
        border-radius: 12px;
        text-align: center;
        padding: 25px 15px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease-in-out;
        min-width: 0; /* Fix flex issue */
    }

    .toggle-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* ACTIVE STATE (Green Border + Checkmark) */
    .toggle-card.active {
        border: 2px solid #0FA96D; /* Green Border */
        background-color: #f0fdf4; /* Very light green bg */
    }

    /* The Checkmark Icon */
    .toggle-card.active::after {
        content: '\f00c'; /* FontAwesome Check */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 10px; right: 10px;
        background: #0FA96D;
        color: #fff;
        width: 24px; height: 24px;
        border-radius: 50%;
        font-size: 0.8rem;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Price Text */
    .t-price {
        font-size: 1.8rem;
        font-weight: 800;
        color: #4b5563;
        margin-bottom: 5px;
    }

    /* Description Text */
    .t-desc {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 20px;
        min-height: 20px;
    }

    /* VISUAL BUTTONS (Non-clickable, just for looks) */
    .visual-btn {
        display: block;
        width: 100%;
        padding: 12px 0;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.95rem;
        color: #fff;
        text-transform: capitalize;
    }

    /* Button Colors from Image */
    .btn-visual-cash {
        background-color: #065f46; /* Dark Green */
    }
    .btn-visual-deposit {
        background-color: #3b82f6; /* Blue */
    }
    .btn-visual-full {
        background-color: #eab308; /* Yellow/Gold */
        color: #000; /* Dark text for yellow button */
    }

    /* --- SQUARE FORM & ALERTS --- */
    .payment-container { padding-right: 15px; }

    #card-container {
        min-height: 90px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .form-control, .form-select {
        border-radius: 6px; border: 1px solid #ced4da;
        padding: 12px; font-size: 0.95rem; background: #fff; margin-bottom: 15px;
    }
    .form-label { font-size: 0.85rem; font-weight: 600; color: #555; margin-bottom: 5px; }

    /* Alert Box Style */
    .payment-alert {
        padding: 15px; border-radius: 8px; margin-bottom: 20px;
        font-weight: 600; text-align: center; font-size: 0.9rem;
    }
    .alert-info-soft { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }

    /* Main Pay Button */
    .btn-pay-blue {
        background: #2563eb; color: #fff; width: 100%; padding: 16px;
        font-weight: 700; font-size: 1.1rem; border: none; border-radius: 8px;
        margin-top: 10px; transition: 0.2s;
    }
    .btn-pay-blue:hover { background: #1d4ed8; }
    .btn-pay-blue:disabled { background: #9ca3af; cursor: not-allowed; }

    /* --- SIDEBAR --- */
    .sidebar-yellow { background-color: #fdf5d3; padding: 25px; border-radius: 12px; height: 100%; }
    .sidebar-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px;
    }
    .sidebar-title { font-size: 1.1rem; font-weight: 700; color: #444; margin: 0; }
    .btn-change {
        background: #9ca3af; color: #fff; font-size: 0.7rem; padding: 4px 10px;
        border-radius: 4px; text-decoration: none; font-weight: 600;
    }
    .summary-table { width: 100%; font-size: 0.85rem; color: #333; margin-bottom: 20px; }
    .summary-table td { padding: 6px 0; vertical-align: top; }
    .summary-table td:first-child { font-weight: 700; width: 120px; color: #222; }

    /* Mobile Responsive */
    @media(max-width: 991px){
        .payment-toggles { flex-direction: column; gap: 15px; }
        .sidebar-yellow { margin-top: 30px; }
    }
</style>

<div class="container my-4">

    <div class="mb-4">
        <h2 class="page-title">Payment Information</h2>
        <div class="step-text">Your Current Selection ( Step 4 Of 4 )</div>
    </div>

    <form action="{{ route('book.confirm') }}" method="POST" id="payment-form">
        @csrf

        @foreach($request->all() as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="cash">
        <input type="hidden" name="square_nonce" id="card-nonce">
        <input type="hidden" name="amount_charged" id="amountCharged" value="1.00">

        <div class="row">

            <div class="col-lg-8">
                <div class="payment-container">

                    <div class="payment-toggles">

                        <div class="toggle-card active" onclick="selectPayment('cash')" id="box-cash">
                            <div class="t-price">$ {{ number_format($request->final_total * 0.9, 0) }}</div>
                            <div class="t-desc">Get 10% Discount</div>
                            <div class="visual-btn btn-visual-cash">Pay Cash</div>
                        </div>

                        <div class="toggle-card" onclick="selectPayment('deposit')" id="box-deposit">
                            <div class="t-price">$ {{ number_format($request->final_total * 1.04, 0) }}</div>
                            <div class="t-desc">Full Amount</div>
                            <div class="visual-btn btn-visual-deposit">Pay by Card</div>
                        </div>

                        <div class="toggle-card" onclick="selectPayment('card')" id="box-card">
                            <div class="t-price">$ {{ number_format($request->final_total * 1.04, 0) }}</div>
                            <div class="t-desc">Pay Full </div>
                            <div class="visual-btn btn-visual-full">Pay by Card</div>
                        </div>

                    </div>

                    <div id="paymentAlert" class="payment-alert alert-info-soft">
                        Pay <strong>$1.00</strong> Reservation Fee now. Balance is payable by cash when you avail the service.
                    </div>

                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; color: #333;">Enter Card Details :</h4>

                    <div id="card-container"></div> <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" name="card_holder_name" required placeholder="Name on card">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="billing_phone" value="{{ $request->phone_number }}" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="billing_email" value="{{ $request->passenger_email }}" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Billing Address</label>
                            <input type="text" class="form-control" name="billing_address" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="billing_city" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="billing_state" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" name="billing_zip" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Country</label>
                            <select class="form-select" name="billing_country">
                                <option value="US" selected>United States</option>
                                <option value="CA">Canada</option>
                                <option value="GB">United Kingdom</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms" style="font-size: 0.85rem;">
                            Yes, I have read and agree to Boston Airport Express Inc. Terms & Conditions
                        </label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="charge_agree" required>
                        <label class="form-check-label" for="charge_agree" style="font-size: 0.85rem;" id="agreeLabel">
                            I allow you to Charge my Card $1.00 for the reservation.
                        </label>
                    </div>

                    <button type="submit" class="btn-pay-blue" id="card-button">
                        Pay $1.00 & Confirm Booking
                    </button>

                    <div class="mt-3 text-center text-muted" style="font-size: 0.75rem;">
                        <i class="fas fa-lock text-success"></i> 100% Secure Payment processed by Square.
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-yellow">

                    <div class="sidebar-header">
                        <h3 class="sidebar-title">Vehicle Details</h3>
                        <a href="{{ route('step2') }}" class="btn-change">Change</a>
                    </div>
                    <table class="summary-table">
                        <tr><td>Vehicle</td><td>:</td><td>{{ $request->vehicle_id ?? 'Selected Vehicle' }}</td></tr>
                        <tr><td>Distance</td><td>:</td><td>926 Miles</td></tr>
                        <tr><td>Base Fare</td><td>:</td><td>${{ $request->final_total }}</td></tr>
                        <tr><td style="color:#222; font-weight:800;">Total Estimate</td><td>:</td><td style="color:#222; font-weight:800;">${{ $request->final_total }}</td></tr>
                    </table>

                    <div class="sidebar-header" style="margin-top:25px; border-top:2px solid #fff; padding-top:20px;">
                        <h3 class="sidebar-title">Booking Details</h3>
                        <a href="{{ route('home') }}" class="btn-change">Change</a>
                    </div>
                    <table class="summary-table">
                        <tr><td>Service</td><td>:</td><td>{{ $request->tripType == 'fromAirport' ? 'Ride From Airport' : 'Door to Door' }}</td></tr>
                        <tr><td>Date</td><td>:</td><td>{{ $request->date }}</td></tr>
                        <tr><td>Time</td><td>:</td><td>{{ $request->time }}</td></tr>
                        <tr><td>Pick up</td><td>:</td><td>{{ $request->pickup_address }}</td></tr>
                        <tr><td>Drop off</td><td>:</td><td>{{ $request->dropoff_address }}</td></tr>
                        <tr><td>Passengers</td><td>:</td><td>{{ (int)$request->adults + (int)$request->children }}</td></tr>
                    </table>

                </div>
            </div>

        </div>
    </form>
</div>

<script type="text/javascript">

    // --- 1. CONFIGURATION ---
    const appId = 'YOUR_SQUARE_APPLICATION_ID';
    const locationId = 'YOUR_SQUARE_LOCATION_ID';

    // Constants
    const baseTotal = {{ $request->final_total }};
    const cardTotal = (baseTotal * 1.04).toFixed(2); // Full + 4%
    let currentMethod = 'cash';

    // --- 2. INITIALIZE SQUARE ---
    async function initializeSquare() {
        if (!window.Square) { throw new Error('Square.js failed to load'); }
        const payments = window.Square.payments(appId, locationId);

        let card;
        try {
            card = await payments.card();
            await card.attach('#card-container');
        } catch (e) {
            console.error('Initializing Card failed', e);
            return;
        }

        const cardButton = document.getElementById('card-button');
        const form = document.getElementById('payment-form');

        // Handle Payment Submit
        cardButton.addEventListener('click', async function (event) {
            event.preventDefault();

            // Validate standard HTML fields
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            cardButton.disabled = true;
            cardButton.innerText = "Processing...";

            try {
                // Tokenize Card
                const result = await card.tokenize();

                if (result.status === 'OK') {
                    document.getElementById('card-nonce').value = result.token;
                    form.submit();
                } else {
                    console.error(result.errors);
                    let msg = result.errors && result.errors[0] ? result.errors[0].message : 'Payment failed';
                    alert(msg);
                    cardButton.disabled = false;
                    updateButtonText();
                }
            } catch (e) {
                console.error(e);
                alert('An error occurred');
                cardButton.disabled = false;
                updateButtonText();
            }
        });
    }

    // --- 3. SELECTION LOGIC ---
    function selectPayment(method) {
        currentMethod = method;
        document.getElementById('selectedPaymentMethod').value = method;

        const boxCash = document.getElementById('box-cash');
        const boxDeposit = document.getElementById('box-deposit');
        const boxCard = document.getElementById('box-card');

        const alertBox = document.getElementById('paymentAlert');
        const agreeLabel = document.getElementById('agreeLabel');
        const amountInput = document.getElementById('amountCharged');

        // Reset Styles - Remove active from all first
        boxCash.classList.remove('active');
        boxDeposit.classList.remove('active');
        boxCard.classList.remove('active');

        // Logic based on selection
        if(method === 'card') {
            // FULL PAYMENT
            boxCard.classList.add('active');
            alertBox.innerHTML = `Pay <strong>$${cardTotal}</strong> (Full Amount + 4% Fee) now.`;
            agreeLabel.innerText = `I allow you to Charge my Card $${cardTotal} for the full booking.`;
            amountInput.value = cardTotal;

        } else if (method === 'deposit') {
            // DEPOSIT (Card Later)
            boxDeposit.classList.add('active');
            alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee now. Pay balance by Card when you avail the service.`;
            agreeLabel.innerText = `I allow you to Charge my Card $1.00 for the reservation.`;
            amountInput.value = "1.00";

        } else {
            // CASH (Default)
            boxCash.classList.add('active');
            alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee now. Pay balance by Cash (10% Discount) when you avail the service.`;
            agreeLabel.innerText = `I allow you to Charge my Card $1.00 for the reservation.`;
            amountInput.value = "1.00";
        }
        updateButtonText();
    }

    function updateButtonText() {
        const btn = document.getElementById('card-button');
        if(currentMethod === 'card') {
            btn.innerText = "Pay Full Amount $" + cardTotal;
        } else {
            btn.innerText = "Pay $1.00 & Confirm Booking";
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', function() {
        initializeSquare();
        selectPayment('cash'); // Set default selection
    });

</script>

@endsection
