@extends('frontend.pages.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- STRIPE JS --}}
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        body { background: #ffffff !important; font-family: "Inter", sans-serif; color: #333; margin-top: 25px; }
        .page-title { font-weight: 700; color: #222; margin-bottom: 5px; font-size: 1.75rem; }
        .step-text { color: #999; font-weight: 500; margin-bottom: 30px; }

        /* Payment Toggles */
        .payment-toggles { display: flex; gap: 12px; margin-bottom: 25px; }
        .toggle-card {
            flex: 1; background: #fff; border: 2px solid #e5e7eb; border-radius: 12px;
            text-align: center; padding: 15px 5px; cursor: pointer; position: relative;
            transition: all 0.2s; display: flex; flex-direction: column; justify-content: center;
            align-items: center; min-width: 0; overflow: hidden;
        }
        .toggle-card:hover { border-color: #aaa; }
        .toggle-card.active { border-color: #0FA96D; background-color: #f0fdf4; box-shadow: 0 4px 12px rgba(15, 169, 109, 0.15); }
        .toggle-card.active::after {
            content: '\f00c'; font-family: "Font Awesome 6 Free"; font-weight: 900;
            position: absolute; top: 5px; right: 5px; background: #0FA96D; color: #fff;
            width: 18px; height: 18px; border-radius: 50%; font-size: 0.6rem;
            display: flex; align-items: center; justify-content: center;
        }

        /* Price Typography */
        .t-price { font-size: 1.1rem; font-weight: 800; color: #374151; margin-bottom: 5px; line-height: 1.2; width: 100%; word-wrap: break-word; }
        .t-desc { font-size: 0.7rem; color: #6b7280; font-weight: 600; margin-bottom: 10px; min-height: 25px; display: flex; align-items: center; justify-content: center; line-height: 1.1; padding: 0 2px; }
        .visual-btn { display: block; width: 100%; padding: 8px 2px; border-radius: 6px; font-weight: 700; font-size: 0.75rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-cash { background: #065f46; } .btn-deposit { background: #3b82f6; } .btn-full { background: #eab308; color: #000; }

        /* Stripe Element */
        .StripeElement {
            box-sizing: border-box; height: 50px; padding: 12px 15px;
            border: 1px solid #ced4da; border-radius: 6px; background-color: white; transition: 0.3s;
        }
        .StripeElement--focus { border-color: #0FA96D; box-shadow: 0 0 0 0.2rem rgba(15, 169, 109, 0.25); }
        .StripeElement--invalid { border-color: #fa755a; }
        #card-errors { color: #fa755a; margin-top: 10px; font-size: 0.875rem; }

        /* Payment Alert */
        .payment-alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.95rem; text-align: center; background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .btn-pay-main { background: #2563eb; color: #fff; width: 100%; padding: 16px; font-weight: 700; border: none; border-radius: 8px; font-size: 1.1rem; transition: 0.3s; }

        /* Sidebar & Others */
        .form-control { padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #ccc; }
        .sidebar-yellow { background-color: #fdf5d3; padding: 20px; border-radius: 4px; }
        .sidebar-header { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px; }
        .sidebar-title { font-size: 1.1rem; font-weight: 700; color: #555; margin: 0; }
        .summary-table { width: 100%; font-size: 0.85rem; color: #333; }
        .summary-table td { padding: 5px 0; vertical-align: top; }
        .summary-table td:first-child { font-weight: 700; width: 110px; color: #222; }

        /* Discount Boxes */
        .discount-container { display: flex; gap: 10px; margin-top: 20px; position: relative; }
        .discount-badge { position: absolute; top: -10px; left: -10px; background: #ff0000; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; z-index: 2; }
        .discount-box { background: #fff; flex: 1; text-align: center; padding: 10px 5px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); border: 1px solid #eee; }
        .d-price { color: #ff0000; font-weight: 800; font-size: 1.1rem; }
        .d-text { font-size: 0.7rem; color: #333; font-weight: 600; margin-top: 2px; }
        .d-sub { font-size: 0.65rem; color: #777; }

        @media(max-width: 768px) {
            .payment-toggles { gap: 6px; }
            .toggle-card { padding: 10px 2px; }
            .t-price { font-size: 0.9rem; }
            .visual-btn { font-size: 0.65rem; padding: 5px 2px; }
        }
    </style>

    <div class="container my-4">
        <h2 class="page-title">Payment Information</h2>
        <p class="step-text">Final Step ( 4 of 4 )</p>

        <form action="{{ route('book.confirm') }}" method="POST" id="payment-form">
            @csrf

            {{-- SMART HIDDEN INPUT GENERATOR --}}
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
                $renderHiddenInputs(request()->except(['_token', 'payment_method', 'stripe_token', 'amount_charged']));
            @endphp

            <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="cash">
            <input type="hidden" name="stripe_token" id="stripe-token">
            <input type="hidden" name="amount_charged" id="amountCharged" value="1.00">

            <div class="row">
                {{-- LEFT COLUMN: Payment Forms --}}
                <div class="col-lg-8">
                    {{-- Payment Options --}}
                    <div class="payment-toggles">
                        <div class="toggle-card active" onclick="selectPayment('cash')" id="box-cash">
                            <div class="t-price">1$ for reservation</div>
                        </div>
                        <div class="toggle-card" onclick="selectPayment('deposit')" id="box-deposit">
                            <div class="t-price">${{ number_format($request->fare['total'] * 0.9, 2) }}</div>
                            <div class="t-desc">1$ for reservation</div>
                            <div class="visual-btn btn-deposit">Pay Cash</div>
                        </div>
                        <div class="toggle-card" onclick="selectPayment('card')" id="box-card">
                            @php $fullCard = $request->fare['total']; @endphp
                            <div class="t-price">${{ number_format($fullCard, 2) }}</div>
                            <div class="t-desc">Pay Full</div>
                            <div class="visual-btn btn-full">Pay Full Card</div>
                        </div>
                    </div>

                    <div id="paymentAlert" class="payment-alert">
                        Pay <strong>$1.00</strong> Reservation Fee now via Stripe to confirm. Balance is payable by cash.
                    </div>

                    <h5 class="fw-bold mb-3">Billing & Card Details</h5>

                    {{-- STRIPE CARD ELEMENT --}}
                    <div class="mb-4">
                        <label class="form-label">Credit or Debit Card</label>
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>

                    {{-- Billing Form --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" id="cardholder-name" name="card_holder_name" value="{{ $request->passenger_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Billing Phone</label>
                            <input type="tel" class="form-control" name="billing_phone" value="{{ $request->phone_number }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Billing Address</label>
                            <input type="text" class="form-control" id="billing-address" name="billing_address" value="{{ $request->mailing_address }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="billing-city" name="billing_city" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" id="billing-state" name="billing_state" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="billing-zip" name="billing_zip" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="terms" checked>
                            <label class="form-check-label small" for="terms">I agree to Terms & Conditions</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="charge_agree" checked>
                            <label class="form-check-label small" for="charge_agree" id="agreeLabel">
                                I allow you to charge my card $1.00
                            </label>
                        </div>
                        <button type="submit" class="btn-pay-main" id="card-button">Confirm Booking & Pay $1.00</button>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Sidebar Summary --}}
                <div class="col-lg-4">
                    <div class="sidebar-yellow">

                        {{-- 1. PERSONAL INFORMATION (UPDATED) --}}
                        <div class="sidebar-header">
                            <h3 class="sidebar-title">Personal Information</h3>
                        </div>
                        <table class="summary-table mb-3">
                            {{-- Name --}}
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{ $request->passenger_name }}</td>
                            </tr>

                            {{-- Email --}}
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td style="word-break: break-all;">{{ $request->passenger_email }}</td>
                            </tr>

                            {{-- Phone (With Code) --}}
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{ $request->phone_country_code }} {{ $request->phone_number }}</td>
                            </tr>

                            {{-- Airline (If exists) --}}
                            @if($request->airline_name)
                            <tr>
                                <td>Airline</td>
                                <td>:</td>
                                <td>{{ $request->airline_name }}</td>
                            </tr>
                            @endif

                            {{-- Flight No (If exists) --}}
                            @if($request->flight_number)
                            <tr>
                                <td>Flight No.</td>
                                <td>:</td>
                                <td>{{ $request->flight_number }}</td>
                            </tr>
                            @endif

                            {{-- Alternate Phone (If exists) --}}
                            @if($request->alternate_phone)
                            <tr>
                                <td>Alt. Phone</td>
                                <td>:</td>
                                <td>{{ $request->alternate_phone }}</td>
                            </tr>
                            @endif

                            {{-- Mailing Address (If exists) --}}
                            @if($request->mailing_address)
                            <tr>
                                <td>Address</td>
                                <td>:</td>
                                <td>{{ Str::limit($request->mailing_address, 40) }}</td>
                            </tr>
                            @endif

                            {{-- Special Needs (If exists) --}}
                            @if($request->special_needs)
                            <tr>
                                <td>Notes</td>
                                <td>:</td>
                                <td style="color:#d32f2f;">{{ $request->special_needs }}</td>
                            </tr>
                            @endif
                        </table>

                        {{-- 2. Booking Details --}}
                        <div class="sidebar-header">
                            <h3 class="sidebar-title">Booking Details</h3>
                        </div>
                        <table class="summary-table mb-3">
                            <tr><td>Service</td><td>:</td><td>{{ $request->trip_type == 'fromAirport' ? 'Ride From Airport' : ucfirst($request->trip_type) }}</td></tr>
                            <tr><td>Date</td><td>:</td><td>{{ $request->date }}</td></tr>
                            <tr><td>Time</td><td>:</td><td>{{ $request->time }}</td></tr>
                            <tr><td>Pickup</td><td>:</td><td>{{ Str::limit($request->pickup ?? $request->fromAddress, 25) }}</td></tr>
                            <tr><td>Dropoff</td><td>:</td><td>{{ Str::limit($request->dropoff ?? $request->to_address, 25) }}</td></tr>
                            <tr>
                                <td>Passengers</td><td>:</td>
                                <td>{{ $request->reqPassengers }} <span style="font-size:0.75rem; color:#666;">({{ $request->adults }} Adults + {{ $request->child_seat }} Childen)</span></td>
                            </tr>
                            <tr><td>Luggage</td><td>:</td><td>{{ $request->luggage }} Bags</td></tr>

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

                        {{-- 3. VEHICLE DETAILS --}}
                        <div class="sidebar-header">
                            <h3 class="sidebar-title">Vehicle Details</h3>
                        </div>
                        <table class="summary-table mb-3">
                            <tr>
                                <td>Vehicle</td><td>:</td>
                                <td style="font-weight:700;">Luxury Vehicle </td>
                            </tr>
                            <tr>
                                <td>Max Pax</td><td>:</td>
                                <td>{{ $request->fare['capacity_passenger'] ?? '-' }} Persons</td>
                            </tr>
                            <tr>
                                <td>Max Bags</td><td>:</td>
                                <td>{{ $request->fare['capacity_luggage'] ?? '-' }} Bags</td>
                            </tr>
                        </table>

                        {{-- 4. Price Breakdown --}}
                        <div class="sidebar-header price-section">
                            <h3 class="sidebar-title">Price Breakdown</h3>
                        </div>

                        <table class="summary-table">
                            <tr><td>Distance</td><td>:</td><td>{{ number_format((float)($request->distance_miles ?? 0), 2) }} Miles</td></tr>
                            <tr><td>Base Fare</td><td>:</td><td>${{ number_format((float)($request->fare['estimatedFare'] ?? 0), 2) }}</td></tr>
                            <tr><td>Gratuity</td><td>:</td><td>${{ number_format((float)($request->fare['gratuity'] ?? 0), 2) }}</td></tr>

                            @if(($request->fare['pickup_tax'] ?? 0) > 0)
                            <tr><td>Pickup Tax</td><td>:</td><td>${{ number_format($request->fare['pickup_tax'], 2) }}</td></tr>
                            @endif

                            @if(($request->fare['dropoff_tax'] ?? 0) > 0)
                            <tr><td>Dropoff Tax</td><td>:</td><td>${{ number_format($request->fare['dropoff_tax'], 2) }}</td></tr>
                            @endif

                            @if(($request->fare['parking_fee'] ?? 0) > 0)
                            <tr><td>Parking Fee</td><td>:</td><td>${{ number_format($request->fare['parking_fee'], 2) }}</td></tr>
                            @endif

                            @if(($request->fare['toll_fee'] ?? 0) > 0)
                            <tr><td>Toll Fee</td><td>:</td><td>${{ number_format($request->fare['toll_fee'], 2) }}</td></tr>
                            @endif

                            {{-- DYNAMIC SURCHARGE BREAKDOWN LOOP --}}
                            @if(isset($request->surcharge_details) && is_array($request->surcharge_details) && count($request->surcharge_details) > 0)
                                <tr><td colspan="3" style="font-weight:600; color:#555; padding-top:5px;">Surcharges:</td></tr>
                                @foreach($request->surcharge_details as $sd)
                                <tr>
                                    <td style="color:#666; padding-left:10px; font-size:0.8rem;">- {{ $sd['name'] }}</td>
                                    <td>:</td>
                                    <td style="color:#666;">${{ number_format((float)$sd['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                            @elseif(($request->fare['surcharge_fee'] ?? 0) > 0)
                                <tr><td>Surcharge</td><td>:</td><td>${{ number_format($request->fare['surcharge_fee'], 2) }}</td></tr>
                            @endif

                            @if(($request->fare['extra_luggage_fee'] ?? 0) > 0)
                            <tr><td>Extra Luggage</td><td>:</td><td>${{ number_format($request->fare['extra_luggage_fee'], 2) }}</td></tr>
                            @endif

                            @php
                                $extras = ($request->fare['child_seat_fee'] ?? 0) +
                                          ($request->fare['booster_seat_fee'] ?? 0) +
                                          ($request->fare['front_seat_fee'] ?? 0) +
                                          ($request->fare['stopover_fee'] ?? 0);
                            @endphp
                            @if($extras > 0)
                            <tr><td>Extras (Seats/Stops)</td><td>:</td><td>${{ number_format($extras, 2) }}</td></tr>
                            @endif

                            <tr><td colspan="3"><hr style="margin:5px 0; border-color:#ccc;"></td></tr>

                            <tr>
                                <td style="color:#000; font-size:1.1rem; font-weight:700; padding-top:5px;">Total</td>
                                <td style="padding-top:5px;">:</td>
                                <td style="color:#000; font-weight:800; font-size:1.1rem; padding-top:5px;">
                                    ${{ number_format((float)($request->fare['total'] ?? 0), 2) }}
                                </td>
                            </tr>
                        </table>

                        <div class="discount-container">
                            <div class="discount-badge">%</div>
                            <div class="discount-box">
                                <div class="d-price">${{ number_format((float)($request->fare['total'] * 0.9 ?? 0) , 2) }}</div>
                                <div class="d-text">PAY CASH</div>
                                <div class="d-sub">$1 reservation fee</div>
                            </div>
                            <div class="discount-box">
                                <div class="d-price">${{ number_format((float)($request->fare['total'] ?? 0), 2) }}</div>
                                <div class="d-text">PAY CARD</div>
                                <div class="d-sub">Full Price</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- STRIPE LOGIC --}}
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        const style = {
            base: { color: '#32325d', fontFamily: '"Inter", sans-serif', fontSize: '16px', '::placeholder': { color: '#aab7c4' } },
            invalid: { color: '#fa755a', iconColor: '#fa755a' }
        };

        const card = elements.create('card', {style: style, hidePostalCode: true});
        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });

        const cardButton = document.getElementById('card-button');
        const form = document.getElementById('payment-form');

        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            if (!form.checkValidity()) { form.reportValidity(); return; }

            cardButton.disabled = true;
            const originalText = cardButton.innerText;
            cardButton.innerText = "Processing...";

            const {token, error} = await stripe.createToken(card, {
                name: document.getElementById('cardholder-name').value,
                address_line1: document.getElementById('billing-address').value,
                address_city: document.getElementById('billing-city').value,
                address_state: document.getElementById('billing-state').value,
                address_zip: document.getElementById('billing-zip').value,
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                cardButton.disabled = false;
                cardButton.innerText = originalText;
            } else {
                stripeTokenHandler(token);
            }
        });

        function stripeTokenHandler(token) {
            document.getElementById('stripe-token').value = token.id;
            form.submit();
        }

        const baseTotal = parseFloat("{{ $request->fare['total'] }}");
        const cardTotal = (baseTotal).toFixed(2);

        function selectPayment(method) {
            document.getElementById('selectedPaymentMethod').value = method;
            ['box-cash', 'box-deposit', 'box-card'].forEach(id => document.getElementById(id).classList.remove('active'));
            document.getElementById('box-' + method).classList.add('active');

            const alertBox = document.getElementById('paymentAlert');
            const agreeLabel = document.getElementById('agreeLabel');
            const amountInput = document.getElementById('amountCharged');

            if (method === 'card') {
                alertBox.innerHTML = `You are paying the <strong>Full Amount $${cardTotal}</strong> now via Card.`;
                agreeLabel.innerText = `I allow you to charge my card $${cardTotal} for full payment.`;
                amountInput.value = cardTotal;
            } else {
                alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee Now. Balance is payable by cash/card.`;
                agreeLabel.innerText = `I allow you to charge my card $1.00 for the reservation.`;
                amountInput.value = "1.00";
            }
            document.getElementById('card-button').innerText = `Confirm Booking & Pay $${amountInput.value}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            selectPayment('cash');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Notify = (type, message) => {
            Swal.fire({ toast: true, position: 'top-center', icon: type, title: message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        };
    </script>
    @if(session('notify'))
    <script>Notify("{{ session('notify.type') }}", "{{ session('notify.message') }}");</script>
    @endif
@endsection
