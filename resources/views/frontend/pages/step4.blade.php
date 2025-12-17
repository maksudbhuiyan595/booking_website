@extends('frontend.pages.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- SQUARE JS SDK --}}
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <style>
        body { background: #ffffff !important; font-family: "Inter", sans-serif; color: #333; margin-top: 25px; }
        .page-title { font-weight: 700; color: #222; margin-bottom: 5px; font-size: 1.75rem; }
        .step-text { color: #999; font-weight: 500; margin-bottom: 30px; }

        /* Payment Toggles Container */
        .payment-toggles {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }

        /* Single Card Style */
        .toggle-card {
            flex: 1;
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            padding: 15px 5px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 0;
            overflow: hidden;
        }

        .toggle-card:hover { border-color: #aaa; }

        .toggle-card.active {
            border-color: #0FA96D;
            background-color: #f0fdf4;
            box-shadow: 0 4px 12px rgba(15, 169, 109, 0.15);
        }

        /* Checkmark Icon */
        .toggle-card.active::after {
            content: '\f00c';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: 5px;
            right: 5px;
            background: #0FA96D;
            color: #fff;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- PRICE FIX --- */
        .t-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #374151;
            margin-bottom: 5px;
            line-height: 1.2;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            width: 100%;
        }

        .t-desc {
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 10px;
            min-height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.1;
            padding: 0 2px;
        }

        .visual-btn {
            display: block;
            width: 100%;
            padding: 6px 2px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.75rem;
            color: #fff;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .btn-cash { background: #065f46; }
        .btn-deposit { background: #3b82f6; }
        .btn-full { background: #eab308; color: #000; }

        /* Forms & Inputs */
        #card-container { min-height: 80px; margin-bottom: 20px; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; background: #fff;}
        .form-control, .form-select { padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #ccc; }

        /* --- RED PAYMENT ALERT (UPDATED) --- */
        .payment-alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            text-align: center;
            /* লাল ব্যাকগ্রাউন্ড এবং টেক্সট */
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        .payment-alert strong {
            color: #7f1d1d;
            font-weight: 800;
        }

        .btn-pay-main { background: #2563eb; color: #fff; width: 100%; padding: 16px; font-weight: 700; border: none; border-radius: 8px; font-size: 1.1rem; transition: 0.3s; }

        /* Sidebar */
        .sidebar-yellow { background-color: #fdf5d3; padding: 20px; border-radius: 4px; }
        .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px; }
        .sidebar-title { font-size: 1.2rem; font-weight: 700; color: #555; margin: 0; }
        .summary-table { width: 100%; font-size: 0.85rem; color: #333; }
        .summary-table td { padding: 5px 0; vertical-align: top; }
        .summary-table td:first-child { font-weight: 700; width: 110px; color: #222; }
        .btn-change { background: #888; color: #fff; font-size: 0.75rem; padding: 3px 10px; border-radius: 3px; text-decoration: none; font-weight: 600; }

        /* MOBILE RESPONSIVE FIXES */
        @media(max-width: 991px) {
            .sidebar-summary { margin-top: 30px; }
        }

        @media(max-width: 768px) {
            .payment-toggles { gap: 6px; }
            .toggle-card { padding: 10px 2px; border-radius: 8px; }
            .t-price { font-size: 0.9rem; font-weight: 700; }
            .t-desc { font-size: 0.6rem; min-height: 30px; }
            .visual-btn { font-size: 0.65rem; padding: 5px 2px; }
        }
    </style>

    <div class="container my-4">
        <h2 class="page-title">Payment Information</h2>
        <p class="step-text">Final Step ( 4 of 4 )</p>

        <form action="{{ route('book.confirm') }}" method="POST" id="payment-form">
            @csrf
            {{-- Hidden Inputs Loop --}}
            @foreach ($request->all() as $key => $value)
                @if (is_array($value))
                    @foreach ($value as $subKey => $subValue)
                        @if (is_array($subValue))
                            @foreach ($subValue as $deepKey => $deepValue)
                                <input type="hidden" name="{{ $key }}[{{ $subKey }}][{{ $deepKey }}]" value="{{ $deepValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}[{{ $subKey }}]" value="{{ $subValue }}">
                        @endif
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="cash">
            <input type="hidden" name="square_nonce" id="card-nonce">
            <input type="hidden" name="amount_charged" id="amountCharged" value="1.00">

            <div class="row">
                <div class="col-lg-8">
                    <div class="payment-toggles">
                        {{-- CASH OPTION --}}
                        <div class="toggle-card active" onclick="selectPayment('cash')" id="box-cash">
                            <div class="t-price">${{ number_format($request->fare['total'] * 0.9, 2) }}</div>
                            <div class="t-desc">1$ Reservation</div>
                            <div class="visual-btn btn-cash">Pay Cash</div>
                        </div>

                        {{-- CARD STANDARD OPTION --}}
                        <div class="toggle-card" onclick="selectPayment('deposit')" id="box-deposit">
                            <div class="t-price">${{ number_format($request->fare['total'], 2) }}</div>
                            <div class="t-desc">Standard Price</div>
                            <div class="visual-btn btn-deposit">Pay Card</div>
                        </div>

                        {{-- CARD FULL PREPAY OPTION --}}
                        <div class="toggle-card" onclick="selectPayment('card')" id="box-card">
                            @php $fullCard = $request->fare['total']; @endphp
                            <div class="t-price">${{ number_format($fullCard, 2) }}</div>
                            <div class="t-desc">Pay Full</div>
                            <div class="visual-btn btn-full">Pay Full Card</div>
                        </div>
                    </div>

                    {{-- RED PAYMENT ALERT --}}
                    <div id="paymentAlert" class="payment-alert">
                        Pay <strong>$1.00</strong> Reservation Fee now via Square to confirm. Balance is payable by cash (10% Discounted).
                    </div>

                    <h5 class="fw-bold mb-3">Billing & Card Details</h5>
                    <div id="card-container"></div>

                    {{-- Billing Form Fields --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" name="card_holder_name"
                                value="{{ $request->passenger_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Billing Phone</label>
                            <input type="tel" class="form-control" name="billing_phone"
                                value="{{ $request->phone_number }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Billing Address</label>
                            <input type="text" class="form-control" name="billing_address"
                                value="{{ $request->mailing_address }}" required>
                        </div>
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

                    <div class="mt-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="terms"  checked>
                            <label class="form-check-label small" for="terms">I agree to Terms & Conditions</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="charge_agree"  checked>
                            <label class="form-check-label small" for="charge_agree" id="agreeLabel">
                                I allow you to charge my card $1.00
                            </label>
                        </div>
                        <button type="submit" class="btn-pay-main" id="card-button">Confirm Booking & Pay $1.00</button>
                    </div>
                </div>

                {{-- Sidebar / Booking Details --}}
                <div class="col-lg-4">
                    <div class="sidebar-summary sidebar-yellow">
                        <div class="sidebar-header d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0"><i class="fas fa-car me-2"></i>Vehicle Details</h6>
                            <a href="{{ route('step2', $request->all()) }}" class="btn-change">Change</a>
                        </div>
                        <table class="summary-table">
                            <tr><td>Vehicle</td><td>: {{ $request->vehicle_display_name ?? 'Luxury Van' }}</td></tr>
                            <tr><td>Distance</td><td>: {{ number_format($request->distance_miles, 1) }} Miles</td></tr>
                            <tr><td>Base Fare</td><td>: ${{ number_format($request->fare['base_fare'] ?? 0, 2) }}</td></tr>
                            <tr><td>Gratuity (20%)</td><td>: ${{ number_format($request->fare['gratuity'] ?? 0, 2) }}</td></tr>

                            @if (($request->fare['pickup_tax'] ?? 0) > 0)
                                <tr><td>Airport Tax</td><td>: ${{ number_format($request->fare['pickup_tax'], 2) }}</td></tr>
                            @endif

                            @if (isset($request->extra_charge_details) && is_array($request->extra_charge_details))
                                @foreach ($request->extra_charge_details as $charge)
                                    <tr>
                                        <td>{{ $charge['name'] }}</td>
                                        <td>: ${{ number_format($charge['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (($request->fare['extra_luggage_fee'] ?? 0) > 0)
                                <tr><td>Extra Luggage</td><td>: ${{ number_format($request->fare['extra_luggage_fee'], 2) }}</td></tr>
                            @endif
                            <tr class="border-top">
                                <td class="pt-2 fw-800 text-dark">Total Payable</td>
                                <td class="pt-2 fw-800 text-dark">: ${{ number_format($request->fare['total'] ?? 0, 2) }}</td>
                            </tr>
                        </table>

                        <div class="sidebar-header d-flex justify-content-between align-items-center mt-4 mb-3 border-top pt-3">
                            <h6 class="fw-bold m-0"><i class="fas fa-calendar-alt me-2"></i>Booking Details</h6>
                            <a href="{{ route('home') }}" class="btn-change">Change</a>
                        </div>

                        <table class="summary-table">
                            <tr>
                                <td>Service</td>
                                <td>: {{ $request->trip_type == 'fromAirport' ? 'Ride From Airport' : 'Point to Point' }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>: {{ \Carbon\Carbon::parse($request->date)->format('m/d/Y') }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>: {{ $request->time }}</td>
                            </tr>
                            <tr>
                                <td>Pick up</td>
                                <td class="small">: {{ $request->pickup_formatted ?: $request->fromAddress }}</td>
                            </tr>
                            <tr>
                                <td>Drop off</td>
                                <td class="small">: {{ $request->dropoff_formatted ?: $request->to_address }}</td>
                            </tr>
                            <tr>
                                <td>Passengers</td>
                                <td>: {{ (int) $request->adults + (int) $request->seats_dummy }}</td>
                            </tr>
                            <tr>
                                <td>Luggage</td>
                                <td>: {{ $request->luggage }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const appId = '{{ env('SQUARE_APPLICATION_ID') }}';
        const locationId = '{{ env('SQUARE_LOCATION_ID') }}';
        const baseTotal = parseFloat("{{ $request->fare['total'] }}");
        const cardTotal = (baseTotal * 1.04).toFixed(2);

        async function initializeSquare() {
            const payments = Square.payments(appId, locationId);
            const card = await payments.card();
            await card.attach('#card-container');

            const cardButton = document.getElementById('card-button');
            cardButton.addEventListener('click', async (e) => {
                e.preventDefault();
                if (!document.getElementById('payment-form').checkValidity()) {
                    document.getElementById('payment-form').reportValidity();
                    return;
                }
                cardButton.disabled = true;
                const originalText = cardButton.innerText;
                cardButton.innerText = "Processing...";
                try {
                    const result = await card.tokenize();
                    if (result.status === 'OK') {
                        document.getElementById('card-nonce').value = result.token;
                        document.getElementById('payment-form').submit();
                    } else {
                        let msg = 'Payment Failed';
                        if(result.errors && result.errors.length > 0) msg = result.errors[0].message;
                        alert(msg);
                        cardButton.disabled = false;
                        cardButton.innerText = originalText;
                    }
                } catch (error) {
                    console.error(error);
                    alert("An unexpected error occurred");
                    cardButton.disabled = false;
                    cardButton.innerText = originalText;
                }
            });
        }

        function selectPayment(method) {
            document.getElementById('selectedPaymentMethod').value = method;
            ['box-cash', 'box-deposit', 'box-card'].forEach(id => document.getElementById(id).classList.remove('active'));
            document.getElementById('box-' + method).classList.add('active');

            const alertBox = document.getElementById('paymentAlert');
            const agreeLabel = document.getElementById('agreeLabel');
            const amountInput = document.getElementById('amountCharged');

            // Logic to change Alert Text based on Selection
            if (method === 'card') {
                alertBox.innerHTML = `You are paying the <strong>Full Amount $${cardTotal}</strong> now via Card. No balance due.`;
                agreeLabel.innerText = `I allow you to charge my card $${cardTotal} for full payment.`;
                amountInput.value = cardTotal;
            }
            else if (method === 'cash') {
                alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee now via Square to confirm. Balance is payable by cash (10% Discounted).`;
                agreeLabel.innerText = `I allow you to charge my card $1.00 for the reservation.`;
                amountInput.value = "1.00";
            }
            else { // deposit (Standard Card)
                alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee now via Square to confirm. Balance is payable by card (Standard Price).`;
                agreeLabel.innerText = `I allow you to charge my card $1.00 for the reservation.`;
                amountInput.value = "1.00";
            }
            updateButtonText();
        }

        function updateButtonText() {
            const amount = document.getElementById('amountCharged').value;
            const btn = document.getElementById('card-button');
            btn.innerText = `Confirm Booking & Pay $${amount}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            initializeSquare();
            selectPayment('cash');
        });
    </script>
@endsection
