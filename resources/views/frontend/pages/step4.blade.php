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

        /* Forms & Inputs */
        #card-container { min-height: 80px; margin-bottom: 20px; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; background: #fff;}
        .form-control, .form-select { padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #ccc; }

        /* Payment Alert */
        .payment-alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.95rem; text-align: center; background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .payment-alert strong { color: #7f1d1d; font-weight: 800; }
        .btn-pay-main { background: #2563eb; color: #fff; width: 100%; padding: 16px; font-weight: 700; border: none; border-radius: 8px; font-size: 1.1rem; transition: 0.3s; }

        /* Sidebar */
        .sidebar-yellow { background-color: #fdf5d3; padding: 20px; border-radius: 4px; }
        .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px; }
        .sidebar-title { font-size: 1.2rem; font-weight: 700; color: #555; margin: 0; }
        .summary-table { width: 100%; font-size: 0.85rem; color: #333; }
        .summary-table td { padding: 5px 0; vertical-align: top; }
        .summary-table td:first-child { font-weight: 700; width: 110px; color: #222; }
        .btn-change { background: #888; color: #fff; font-size: 0.75rem; padding: 3px 10px; border-radius: 3px; text-decoration: none; font-weight: 600; }
        .btn-change:hover { background: #666; color: #fff; }

        /* Discount Boxes */
        .discount-container { display: flex; gap: 10px; margin-top: 20px; position: relative; }
        .discount-badge { position: absolute; top: -10px; left: -10px; background: #ff0000; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; z-index: 2; }
        .discount-box { background: #fff; flex: 1; text-align: center; padding: 10px 5px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); border: 1px solid #eee; }
        .d-price { color: #ff0000; font-weight: 800; font-size: 1.1rem; }
        .d-text { font-size: 0.7rem; color: #333; font-weight: 600; margin-top: 2px; }
        .d-sub { font-size: 0.65rem; color: #777; }

        /* Developer Data View Styles */
        .data-view-box { background: #212529; color: #fff; border-radius: 8px; padding: 20px; margin-top: 50px; border: 2px solid #dc3545; }
        .data-view-box h4 { border-bottom: 1px solid #555; padding-bottom: 10px; margin-bottom: 20px; color: #dc3545; }
        .data-table { width: 100%; border-collapse: collapse; font-family: monospace; font-size: 13px; }
        .data-table th, .data-table td { border: 1px solid #444; padding: 8px; text-align: left; }
        .data-table th { background: #333; color: #ffd700; }
        .data-table td { background: #2c3034; color: #00ff41; vertical-align: top; }
        .array-pre { margin: 0; color: #0dcaf0; }

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

            {{--
                =========================================================
                SMART HIDDEN INPUT GENERATOR (RECURSIVE)
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
                $renderHiddenInputs(request()->except(['_token', 'payment_method', 'square_nonce', 'amount_charged']));
            @endphp

            <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="cash">
            <input type="hidden" name="square_nonce" id="card-nonce">
            <input type="hidden" name="amount_charged" id="amountCharged" value="1.00">

            <div class="row">
                {{-- LEFT COLUMN: Payment Forms --}}
                <div class="col-lg-8">
                    {{-- Payment Options --}}
                    <div class="payment-toggles">
                        <div class="toggle-card active" onclick="selectPayment('cash')" id="box-cash">
                            <div class="t-price">${{ number_format($request->fare['total'] , 2) }}</div>
                            <div class="t-desc">1$ For Reservation</div>
                            <div class="visual-btn btn-cash">Pay Cash</div>
                        </div>
                        <div class="toggle-card" onclick="selectPayment('deposit')" id="box-deposit">
                            <div class="t-price">${{ number_format($request->fare['total'], 2) }}</div>
                            <div class="t-desc">Full reservation</div>
                            <div class="visual-btn btn-deposit">Pay Card</div>
                        </div>
                        <div class="toggle-card" onclick="selectPayment('card')" id="box-card">
                            @php $fullCard = $request->fare['total']; @endphp
                            <div class="t-price">${{ number_format($fullCard, 2) }}</div>
                            <div class="t-desc">Pay Full</div>
                            <div class="visual-btn btn-full">Pay Full Card</div>
                        </div>
                    </div>

                    <div id="paymentAlert" class="payment-alert">
                        Pay <strong>$1.00</strong> Reservation Fee now via Square to confirm. Balance is payable by cash.
                    </div>

                    <h5 class="fw-bold mb-3">Billing & Card Details</h5>
                    <div id="card-container"></div>

                    {{-- Billing Form --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" name="card_holder_name" value="{{ $request->passenger_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Billing Phone</label>
                            <input type="tel" class="form-control" name="billing_phone" value="{{ $request->phone_number }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Billing Address</label>
                            <input type="text" class="form-control" name="billing_address" value="{{ $request->mailing_address }}" required>
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

                        {{--
                            ============================================
                            SECTION 1: PASSENGER DETAILS (PERSONAL INFO)
                            This section shows fields ONLY if they exist
                            ============================================
                        --}}
                        <div class="sidebar-header" style="border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:15px;">
                            <h3 class="sidebar-title">Passenger Details</h3>
                        </div>
                        <table class="summary-table">
                            {{-- Name --}}
                            @if($request->passenger_name)
                            <tr>
                                <td>Name</td>
                                <td>:</td>
                                <td>{{ $request->passenger_name }}</td>
                            </tr>
                            @endif

                            {{-- Email --}}
                            @if($request->passenger_email)
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{ $request->passenger_email }}</td>
                            </tr>
                            @endif

                            {{-- Phone (Combine Country Code + Number) --}}
                            @if($request->phone_number)
                            <tr>
                                <td>Phone</td>
                                <td>:</td>
                                <td>{{ $request->phone_country_code }} {{ $request->phone_number }}</td>
                            </tr>
                            @endif

                            {{-- Alternate Phone --}}
                            @if($request->alternate_phone)
                            <tr>
                                <td>Alt. Phone</td>
                                <td>:</td>
                                <td>{{ $request->alternate_phone }}</td>
                            </tr>
                            @endif

                            {{-- Airline --}}
                            @if($request->airline_name)
                            <tr>
                                <td>Airline</td>
                                <td>:</td>
                                <td>{{ $request->airline_name }}</td>
                            </tr>
                            @endif

                            {{-- Flight No --}}
                            @if($request->flight_number)
                            <tr>
                                <td>Flight No</td>
                                <td>:</td>
                                <td>{{ $request->flight_number }}</td>
                            </tr>
                            @endif

                            {{-- Mailing Address --}}
                            @if($request->mailing_address)
                            <tr>
                                <td>Address</td>
                                <td>:</td>
                                <td>{{ $request->mailing_address }}</td>
                            </tr>
                            @endif

                            {{-- Special Needs --}}
                            @if($request->special_needs)
                            <tr>
                                <td class="text-danger">Notes</td>
                                <td>:</td>
                                <td class="text-danger">{{ $request->special_needs }}</td>
                            </tr>
                            @endif
                        </table>

                        {{-- SECTION 2: BOOKING DETAILS --}}
                        <div class="sidebar-header" style="margin-top:20px; border-top:1px solid #ddd; padding-top:15px;">
                            <h3 class="sidebar-title">Booking Details</h3>
                            {{-- <a href="{{ route('home') }}" class="btn-change">Change</a> --}}
                        </div>

                        <table class="summary-table">
                            <tr>
                                <td>Service</td>
                                <td>:</td>
                                <td>
                                    @if(($request->trip_type ?? '') == 'fromAirport')
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
                                <td>{{ \Carbon\Carbon::parse($request->date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td>:</td>
                                <td>{{ $request->time }}</td>
                            </tr>
                            <tr>
                                <td>Pick up</td>
                                <td>:</td>
                                <td>{{ $request->pickup ?? $request->pickup_formatted ?? $request->from_address }}</td>
                            </tr>
                            <tr>
                                <td>Drop off</td>
                                <td>:</td>
                                <td>{{ $request->dropoff ?? $request->dropoff_formatted ?? $request->to_address }}</td>
                            </tr>
                            <tr>
                                <td>Passengers</td>
                                <td>:</td>
                                <td>
                                    {{ (int)($request->adults ?? 0) + (int)($request->children ?? 0) }}
                                    ({{ $request->adults ?? 0 }} Adults + {{ $request->children ?? 0 }} Children)
                                </td>
                            </tr>
                            <tr>
                                <td>Luggage</td>
                                <td>:</td>
                                <td>
                                    {{ $request->luggage }}
                                    (4 Bags Free + {{ max(0, (int)$request->luggage - 4) }} Extra)
                                </td>
                            </tr>
                        </table>

                        {{-- SECTION 3: VEHICLE & PRICE --}}
                        <div class="sidebar-header price-section" style="margin-top:20px; border-top:1px solid #ddd; padding-top:15px;">
                            <h3 class="sidebar-title">Vehicle & Price</h3>
                            {{-- <a href="{{ route('step2', $request->all()) }}" class="btn-change">Change</a> --}}
                        </div>

                        <table class="summary-table">
                            <tr>
                                <td>Vehicle</td>
                                <td>:</td>
                                <td>{{ $request->vehicle_display_name ?? 'Luxury Van' }}</td>
                            </tr>
                            <tr>
                                <td>Distance</td>
                                <td>:</td>
                                <td>{{ number_format((float)($request->distance_miles ?? 0), 2) }} Miles</td>
                            </tr>
                            <tr>
                                <td>Base Fare</td>
                                <td>:</td>
                                <td>${{ number_format((float)($request->fare['estimatedFare'] ?? $request->fare['base_fare'] ?? 0), 2) }}</td>
                            </tr>
                            <tr>
                                <td>Gratuity (20%)</td>
                                <td>:</td>
                                <td>${{ number_format((float)($request->fare['gratuity'] ?? 0), 2) }}</td>
                            </tr>

                            {{-- DYNAMIC SURCHARGES LOOP --}}
                            @if(isset($request->surcharge_details) && is_array($request->surcharge_details))
                                @foreach($request->surcharge_details as $surcharge)
                                    <tr>
                                        <td>{{ $surcharge['name'] ?? 'Surcharge' }}</td>
                                        <td>:</td>
                                        <td>${{ number_format((float)($surcharge['amount'] ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- EXTRA CHARGES LOOP --}}
                            @if(isset($request->extra_charge_details) && is_array($request->extra_charge_details))
                                @foreach($request->extra_charge_details as $charge)
                                    <tr>
                                        <td>{{ $charge['name'] ?? 'Extra' }}</td>
                                        <td>:</td>
                                        <td>${{ number_format((float)($charge['amount'] ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- TAXES & FEES --}}
                            @if (($request->fare['pickup_tax'] ?? 0) > 0)
                                <tr><td>Pickup Tax</td><td>:</td><td>${{ number_format($request->fare['pickup_tax'], 2) }}</td></tr>
                            @endif
                            @if (($request->fare['dropoff_tax'] ?? 0) > 0)
                                <tr><td>Dropoff Tax</td><td>:</td><td>${{ number_format($request->fare['dropoff_tax'], 2) }}</td></tr>
                            @endif
                            @if (($request->fare['parking_fee'] ?? 0) > 0)
                                <tr><td>Parking Fee</td><td>:</td><td>${{ number_format($request->fare['parking_fee'], 2) }}</td></tr>
                            @endif
                            @if (($request->fare['toll_fee'] ?? 0) > 0)
                                <tr><td>Toll Fee</td><td>:</td><td>${{ number_format($request->fare['toll_fee'], 2) }}</td></tr>
                            @endif

                            {{-- Extra Luggage Fee --}}
                            @if(($request->fare['extra_luggage_fee'] ?? 0) > 0)
                                <tr>
                                    <td>Extra Luggage</td>
                                    <td>:</td>
                                    <td>${{ number_format((float)$request->fare['extra_luggage_fee'], 2) }}</td>
                                </tr>
                            @endif

                            <tr>
                                <td style="color:#222; font-size:1.1rem; padding-top:15px;">Total Payable</td>
                                <td style="padding-top:15px;">:</td>
                                <td style="color:#222; font-weight:800; font-size:1.1rem; padding-top:15px;">
                                    ${{ number_format((float)($request->fare['total'] ?? 0), 2) }}
                                </td>
                            </tr>
                        </table>

                        {{-- Cash vs Card Discount Box --}}
                        <div class="discount-container">
                            <div class="discount-badge">%</div>
                            <div class="discount-box">
                                <div class="d-price">${{ number_format((float)($request->fare['total'] ?? 0), 2) }}</div>
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

    {{-- JS SCRIPTS (Square & SweetAlert) --}}
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

            if (method === 'card') {
                alertBox.innerHTML = `You are paying the <strong>Full Amount $${cardTotal}</strong> now via Card. No balance due.`;
                agreeLabel.innerText = `I allow you to charge my card $${cardTotal} for full payment.`;
                amountInput.value = cardTotal;
            } else if (method === 'cash') {
                alertBox.innerHTML = `Pay <strong>$1.00</strong> Reservation Fee Now. Balance is payable by cash/card when you avail the service.`;
                agreeLabel.innerText = `I allow you to charge my card $1.00 for the reservation.`;
                amountInput.value = "1.00";
            } else {
                alertBox.innerHTML = `Pay <strong>$${cardTotal}</strong> Full Reservation Fee Now. The remaining balance is payable by card.`;
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
