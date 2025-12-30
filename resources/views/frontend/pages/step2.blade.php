@extends('frontend.pages.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

{{-- ========================================================= --}}
{{--  PHP BLOCK: SET TIMEZONE & FORMAT DATE  --}}
{{-- ========================================================= --}}
@php
    use Carbon\Carbon;

    // 1. ইনপুট থেকে তারিখ ও সময় নিয়ে Carbon অবজেক্ট তৈরি (Timezone: America/New_York)
    $rawDate = $request['date'] ?? now()->toDateString();
    $rawTime = $request['time'] ?? '12:00';

    try {
        $bostonDateTime = Carbon::createFromFormat('Y-m-d H:i', $rawDate . ' ' . $rawTime, 'America/New_York');
    } catch (\Exception $e) {
        // যদি ফরম্যাটে কোনো সমস্যা হয়, ডিফল্ট বর্তমান সময় নেওয়া হবে
        $bostonDateTime = Carbon::now('America/New_York');
    }

    // 2. সুন্দর ফরম্যাট তৈরি করা
    $formattedDate = $bostonDateTime->format('l, F j, Y'); // e.g., Sunday, December 28, 2025
    $formattedTime = $bostonDateTime->format('g:i A');      // e.g., 2:30 PM
@endphp

<style>
    body {
        background: #ffffff !important;
        font-family: "Inter", sans-serif;
        color: #333;
        margin-top: 25px;
    }

    /* --- THEME COLORS (Green/Clean) --- */
    :root {
        --primary-green: #006644;
        --primary-hover: #004d33;
    }

    /* Breadcrumbs */
    .breadcrumb-nav {
        font-size: 0.85rem;
        color: #0FA96D;
        margin-bottom: 20px;
        padding-top: 20px;
    }
    .breadcrumb-nav a { color: #666; text-decoration: none; }
    .breadcrumb-nav span { margin: 0 5px; color: #999; }

    .page-title { font-weight: 800; color: #222; margin-bottom: 5px; }
    .step-text { color: #999; font-weight: 500; margin-bottom: 30px; }

    /* LEFT COLUMN: Active Vehicle Card */
    .vehicle-card-dark {
        background: #2c3e50;
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
    .vehicle-card-dark::before {
        content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 100px;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent); z-index: 0;
    }
    .v-img-container { position: relative; z-index: 1; margin-bottom: 10px; }
    .v-img { width: 100%; max-width: 360px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3)); }

    .v-name {
        font-size: 1.1rem; font-weight: 700; margin-bottom: 25px; position: relative; z-index: 1;
        color: #fff;
    }
    .v-icons-row {
        display: flex; justify-content: space-around; position: relative; z-index: 1;
        border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;
    }
    .v-icon-box i { font-size: 1.8rem; margin-bottom: 5px; display: block; color: #fff; }
    .v-icon-box span { font-size: 0.8rem; opacity: 0.9; }

    /* Payment Box */
    .payment-options-box {
        background: #f9f9f9;
        border: 1px solid #eee;
        margin-top: 20px; position: relative;
    }
    .discount-badge {
        position: absolute; top: -10px; left: -10px;
        background: #ff0000; color: #fff;
        width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;
    }
    .pay-col { padding: 15px; text-align: center; }
    .pay-col.left-col { border-right: 1px solid #ddd; }
    .price-red { color: #ff0000; font-weight: 700; font-size: 1.2rem; }
    .price-black { color: #333; font-weight: 700; font-size: 1.2rem; }
    .pay-sub { font-size: 0.8rem; color: #555; margin-top: 4px; }
    .pay-note { font-size: 0.75rem; color: #555; margin-top: 10px; line-height: 1.4; }

    /* Middle Column Table */
    .details-title { font-size: 1.2rem; font-weight: 700; color: #444; margin-bottom: 20px; }
    .pricing-table { width: 100%; font-size: 0.95rem; color: #444; }
    .pricing-table td { padding: 8px 0; vertical-align: top; }
    .pricing-table td:first-child { font-weight: 600; width: 60%; }
    .pricing-table td:nth-child(2) { text-align: center; width: 10px; }
    .pricing-table td:last-child { text-align: right; font-weight: 700; }

    /* Extra Luggage Card */
    .extra-luggage-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 25px 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .el-title { font-weight: 700; color: #333; font-size: 1rem; }
    .el-total { font-weight: 700; font-size: 1.2rem; color: #333; }
    .el-per { font-size: 0.8rem; color: #666; margin-top: 2px; }

    /* Summary & Button */
    .btn-book-green {
        background-color: var(--primary-green);
        color: #fff;
        font-weight: 600; width: 100%;
        padding: 12px; border-radius: 5px; border: none; font-size: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .btn-book-green:hover { background-color: var(--primary-hover); }

    .summary-yellow-box {
        background-color: #fffbeb; border: 1px solid #f3eacb; padding: 20px; margin-top: 25px; border-radius: 4px;
    }
    .summary-list td { padding: 6px 0; font-size: 0.9rem; color: #333; }
    .summary-list td:first-child { font-weight: 600; width: 100px; }

    /* Vehicle Details Section in Summary */
    .vehicle-det-header {
        font-weight: 700; color: #333; margin-top: 10px; margin-bottom: 5px; font-size: 1rem;
    }

    /* MORE VEHICLE OPTIONS STYLES */
    .more-options-wrapper {
        margin-top: 50px; padding-top: 30px; border-top: 1px solid #eee; background-color: #fff;
    }
    .mo-header { text-align: center; margin-bottom: 30px; }
    .mo-title { font-size: 1.5rem; font-weight: 700; color: #444; margin-bottom: 5px; }
    .mo-subtitle { font-size: 0.9rem; color: #777; }

    .option-card {
        background: #fff; border-bottom: 1px solid #eee; padding: 30px 0; transition: all 0.2s ease; cursor: default;
    }
    .option-card:hover { background-color: #fafafa; }

    .d-none-custom { display: none !important; }

    .oc-img-box { display: flex; align-items: center; justify-content: center; padding: 10px; }
    .oc-img { max-width: 400px; width: 100%; height: auto; object-fit: contain; }
    .oc-title { font-size: 1.25rem; font-weight: 700; color: #2c3e50; margin-bottom: 10px; }
    .oc-features { font-size: 0.9rem; color: #333; margin-bottom: 12px; font-weight: 500; }
    .oc-features i { margin-right: 6px; color: #000; }
    .oc-divider { margin: 0 12px; color: #ccc; }
    .oc-breakdown { font-size: 0.8rem; color: #666; line-height: 1.6; }

    .btn-book-outline {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
        padding: 8px 25px; font-weight: 700; font-size: 0.95rem; border-radius: 4px;
        transition: 0.3s; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-book-outline:hover {
        background: var(--primary-green);
        color: #fff;
    }
    .oc-action-container {
        display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #eee;
    }
    .oc-price { font-size: 1.5rem; font-weight: 800; color: var(--primary-green); }

    /* =========================================
       RESPONSIVE FIX FOR MOBILE (SIDE BY SIDE)
       ========================================= */
    @media (max-width: 768px) {
        .oc-action-container {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .btn-book-outline {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        .oc-price {
            font-size: 1.2rem;
        }
        .oc-total-label {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container mb-5">
    <div class="breadcrumb-nav">
        <a href="#">Home</a> <span>&raquo;</span>
        <a href="#">Reservation</a> <span>&raquo;</span>
        <span style="color:#0FA96D">Choose vehicle ( Step 2 of 4 )</span>
    </div>

    <h2 class="page-title">Select Vehicle & Confirm Ride Details</h2>
    <div class="step-text">Your Current Selection ( Step 2 of 4 )</div>

    <form id="bookingForm" action="{{ route('step3') }}" method="GET">

        {{-- HIDDEN INPUTS (Keeping raw numbers for Backend processing) --}}
        @foreach($request as $key => $value)
            @if(!is_array($value) && $key != 'fare')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <input type="hidden" name="trip_type" value="{{ $trip_type }}">
        <input type="hidden" name="distance_miles" value="{{ $distance_miles }}">
        <input type="hidden" name="pickup" value="{{ $pickup }}">
        <input type="hidden" name="dropoff" value="{{ $dropoff }}">
        <input type="hidden" name="vehicles_used" value="{{ $vehicles_used ?? 1 }}">

        @if(isset($extra_charge_details) && is_array($extra_charge_details))
            @foreach($extra_charge_details as $index => $charge)
                <input type="hidden" name="extra_charge_details[{{ $index }}][name]" value="{{ $charge['name'] ?? '' }}">
                <input type="hidden" name="extra_charge_details[{{ $index }}][amount]" value="{{ $charge['amount'] ?? 0 }}">
            @endforeach
        @endif

        {{-- DYNAMIC HIDDEN INPUTS --}}
        <input type="hidden" name="vehicle_id" id="inp_vehicle_id" value="{{ $defaultVehicle['vehicle_id'] }}">
        <input type="hidden" name="fare[capacity_passenger]" id="inp_cap_pass" value="{{ $defaultVehicle['capacity_passenger'] }}">
        <input type="hidden" name="fare[capacity_luggage]" id="inp_cap_lug" value="{{ $defaultVehicle['capacity_luggage'] }}">
        <input type="hidden" name="fare[name]" id="inp_v_name" value="{{ $defaultVehicle['name'] }}">
        <input type="hidden" name="fare[estimatedFare]" id="inp_est_fare" value="{{ $defaultVehicle['estimated_fare'] }}">
        <input type="hidden" name="fare[gratuity]" id="inp_gratuity" value="{{ $defaultVehicle['gratuity_fee'] }}">
        <input type="hidden" name="fare[pickup_tax]" value="{{ $defaultVehicle['pickup_tax'] }}">
        <input type="hidden" name="fare[dropoff_tax]" value="{{ $defaultVehicle['dropoff_tax'] }}">
        <input type="hidden" name="fare[parking_fee]" value="{{ $defaultVehicle['parking_fee'] }}">
        <input type="hidden" name="fare[stopover_fee]" value="{{ $defaultVehicle['stopover_fee'] }}">
        <input type="hidden" name="fare[child_seat_fee]" value="{{ $defaultVehicle['child_seat_fee'] }}">
        <input type="hidden" name="fare[booster_seat_fee]" value="{{ $defaultVehicle['booster_seat_fee'] }}">
        <input type="hidden" name="fare[front_seat_fee]" value="{{ $defaultVehicle['front_seat_fee'] }}">
        <input type="hidden" name="fare[extra_charges]" value="{{ $defaultVehicle['extra_charges'] }}">
        <input type="hidden" name="fare[toll_fee]" value="{{ $defaultVehicle['toll_fee'] }}">
        <input type="hidden" name="fare[surcharge_fee]" id="inp_surcharge" value="{{ $defaultVehicle['surcharge_fee'] }}">
        <input type="hidden" name="fare[extra_luggage_fee]" id="inp_ex_lug_fee" value="{{ $defaultVehicle['extra_luggage_fee'] }}">
        <input type="hidden" name="fare[total]" id="inp_total" value="{{ $defaultVehicle['total_fare'] }}">

        <input type="hidden" name="child_seat" id="inp_total" value="{{ $child_seat }}">
        <input type="hidden" name="reqPassengers" id="inp_total" value="{{ $reqPassengers }}">

        <div id="surcharge_inputs_container">
            @foreach($defaultVehicle['surcharge_details'] ?? [] as $idx => $sd)
                <input type="hidden" name="surcharge_details[{{ $idx }}][name]" value="{{ $sd['name'] }}">
                <input type="hidden" name="surcharge_details[{{ $idx }}][amount]" value="{{ $sd['amount'] }}">
            @endforeach
        </div>

        {{-- TOP SECTION --}}
        <div class="row g-4">
            {{-- LEFT: Card --}}
            <div class="col-lg-4">
                <div class="vehicle-card-dark">
                    <div class="v-img-container">
                       <div class="v-img-container">
                        <img src="{{ $defaultVehicle['image'] ?? asset('images/cars11.webp') }}"
                            onerror="this.onerror=null;this.src='{{ asset('images/cars11.webp') }}';"
                            id="disp_image"
                            class="v-img"
                            alt="Car">
                    </div>

                    </div>
                    <div class="v-name">
                        <span id="disp_pax_cap">{{ $defaultVehicle['capacity_passenger'] }}</span> Passenger
                        <span id="disp_name"> Luxury Vehicle</span>
                        <span id="disp_lug_cap">{{ $defaultVehicle['capacity_luggage'] }}</span> Bags Capacity
                    </div>
                    <div class="v-icons-row">
                        <div class="v-icon-box">
                            <i class="fas fa-user"></i>
                            <span>{{ $reqPassengers ?? 0 }}<br>Passengers</span>
                        </div>
                        <div class="v-icon-box">
                            <i class="fas fa-suitcase"></i>
                            <span>{{ $request['luggage'] }}<br>Luggage</span>
                        </div>
                        <div class="v-icon-box">
                            <i class="fas fa-baby"></i>
                            <span>{{ $child_seat ?? 0 }}<br>Child Seat</span>
                        </div>
                    </div>
                </div>

                <div class="payment-options-box">
                    <div class="discount-badge">%</div>
                    <div class="d-flex">
                        <div class="pay-col left-col w-50">
                            <div class="fw-bold">Pay Cash : <span class="price-red">$<span id="disp_pay_cash">{{ number_format($defaultVehicle['pay_cash'], 2) }}</span></span></div>
                            <div class="pay-sub">$1 Reservation Fee</div>
                        </div>
                        <div class="pay-col w-50">
                            <div class="fw-bold">Pay By Card : <span class="price-black">$<span id="disp_pay_card">{{ number_format($defaultVehicle['total_fare'], 2) }}</span></span></div>
                            <div class="pay-sub">Pay full online</div>
                        </div>
                    </div>
                </div>
                <div class="pay-note">
                    Pay only $1 & confirm your reservation. Balance is payable after service by cash or card. Avail 10% discount on cash payment.
                </div>
            </div>

            {{-- MIDDLE: Pricing Table --}}
            <div class="col-lg-4">
                <h3 class="details-title">Booking Details</h3>

                <table class="pricing-table">
                    <tr><td>Distance Covered</td><td>:</td><td>{{ number_format($distance_miles, 2) }} Miles</td></tr>
                    <tr><td>Estimated Fare</td><td>:</td><td>$<span id="tbl_est_fare">{{ number_format($defaultVehicle['estimated_fare'], 2) }}</span></td></tr>
                    <tr><td>Gratuity(20% fee)</td><td>:</td><td>$<span id="tbl_gratuity">{{ number_format($defaultVehicle['gratuity_fee'], 2) }}</span></td></tr>

                    @if($defaultVehicle['pickup_tax'] > 0) <tr><td>Airport Pickup Tax</td><td>:</td><td>${{ number_format($defaultVehicle['pickup_tax'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['dropoff_tax'] > 0) <tr><td>Airport Dropoff Tax</td><td>:</td><td>${{ number_format($defaultVehicle['dropoff_tax'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['parking_fee'] > 0) <tr><td>Parking Fee</td><td>:</td><td>${{ number_format($defaultVehicle['parking_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['stopover_fee'] > 0) <tr><td>Stopover Fee</td><td>:</td><td>${{ number_format($defaultVehicle['stopover_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['pet_fee'] > 0) <tr><td> Pets Fee</td><td>:</td><td>${{ number_format($defaultVehicle['pet_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['child_seat_fee'] > 0) <tr><td>Infant Seat Fee</td><td>:</td><td>${{ number_format($defaultVehicle['child_seat_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['booster_seat_fee'] > 0) <tr><td>Booster Seat Fee</td><td>:</td><td>${{ number_format($defaultVehicle['booster_seat_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['front_seat_fee'] > 0) <tr><td>Front Seat Fee</td><td>:</td><td>${{ number_format($defaultVehicle['front_seat_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['toll_fee'] > 0) <tr><td>Toll Fee</td><td>:</td><td>${{ number_format($defaultVehicle['toll_fee'], 2) }}</td></tr> @endif
                    @if($defaultVehicle['extra_charges'] > 0) <tr><td>Zip Extra Charges</td><td>:</td><td>${{ number_format($defaultVehicle['extra_charges'], 2) }}</td></tr> @endif

                    <tr id="row_surcharge" style="{{ $defaultVehicle['surcharge_fee'] > 0 ? '' : 'display:none;' }}">
                        <td>Surcharge / Night Fee</td><td>:</td><td>$<span id="tbl_surcharge">{{ number_format($defaultVehicle['surcharge_fee'], 2) }}</span></td>
                    </tr>

                    <tr>
                        <td class="pt-3" style="border-top: 1px solid #ddd;">Total Payable</td>
                        <td class="pt-3" style="border-top: 1px solid #ddd;">:</td>
                        <td class="pt-3" style="border-top: 1px solid #ddd;">$<span id="tbl_total">{{ number_format($defaultVehicle['total_fare'], 2) }}</span></td>
                    </tr>
                </table>

                {{-- EXTRA LUGGAGE CARD (HIGHLIGHTED BOX) --}}
                <div id="box_ex_lug" class="extra-luggage-card" style="{{ $defaultVehicle['extra_luggage_fee'] > 0 ? '' : 'display:none;' }}">
                    <div class="el-title">Extra Luggage Fee</div>
                    <div class="text-end">
                        <div class="el-total">$<span id="txt_ex_lug_total">{{ number_format($defaultVehicle['extra_luggage_fee'], 2) }}</span></div>
                        <div class="el-per">
                            ($<span id="txt_ex_lug_rate">{{
                                $defaultVehicle['extra_luggage_count'] > 0
                                ? number_format($defaultVehicle['extra_luggage_fee'] / $defaultVehicle['extra_luggage_count'], 2)
                                : '0.00'
                            }}</span>/Bag)
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT: Summary --}}
            <div class="col-lg-4">
                <button type="submit" class="btn-book-green">Book Now</button>
                <div class="text-center mt-2 mb-4" style="font-size: 0.8rem; color: #333;">
                    Pay only $1 & confirm your reservation. Balance is payable after service
                </div>

                <div class="summary-yellow-box">
                    <div class="summary-header">
                        <div class="summary-title" style="font-weight:700; color:#444; margin-bottom:10px;">Booking Details</div>
                    </div>
                    <table class="summary-list">
                        <tr>
                            <td>Service</td>
                            <td>:</td>
                            <td>{{ $trip_type == 'fromAirport' ? 'Ride From Airport' : ($trip_type == 'toAirport' ? 'Ride To Airport' : 'Door to Door') }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td>{{ $formattedDate }}</td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td>:</td>
                            <td>{{ $formattedTime }}</td>
                        </tr>
                        <tr>
                            <td>Pick up</td>
                            <td>:</td>
                            <td>{{ Str::limit($pickup, 30) }}</td>
                        </tr>
                        <tr>
                            <td>Drop off</td>
                            <td>:</td>
                            <td>{{ Str::limit($dropoff, 30) }}</td>
                        </tr>

                        {{-- PASSENGER BREAKDOWN --}}
                        <tr>
                            <td>Passengers</td>
                            <td>:</td>
                            <td>
                                {{ $reqPassengers ?? 0 }}
                                <span style="font-size: 0.8rem; color: #666;">
                                    ({{ $request['adults'] ?? 0 }} Adults + {{ $child_seat ?? 0 }} Children)
                                </span>
                            </td>
                        </tr>

                        {{-- LUGGAGE --}}
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

                        <tr><td colspan="3"><hr style="margin: 5px 0; border-color: #131312;"></td></tr>

                        {{-- VEHICLE DETAILS (ADDED HERE) --}}
                        <tr>
                            <td colspan="3" class="vehicle-det-header">Vehicle Details</td>
                        </tr>

                        <tr>
                            <td>Vehicle</td>
                            <td>:</td>
                            <td><span id="sum_name"> Luxury Vehicle</span></td>
                        </tr>
                        <tr>
                            <td>Max Pax</td>
                            <td>:</td>
                            <td><span id="sum_pax">{{ $defaultVehicle['capacity_passenger'] }}</span></td>
                        </tr>
                        <tr>
                            <td>Max Bags</td>
                            <td>:</td>
                            <td><span id="sum_lug">{{ $defaultVehicle['capacity_luggage'] }}</span></td>
                        </tr>

                         <tr><td colspan="3"><hr style="margin: 5px 0; border-color: #131312;"></td></tr>

                        <tr>
                            <td style="font-weight:700;">Total</td>
                            <td style="font-weight:700;">:</td>
                            <td class="fw-bold" style="font-size: 1.1rem; color: #000;">
                                $<span id="sum_total">{{ number_format($defaultVehicle['total_fare'], 2) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </form>

    {{-- ======================================================= --}}
    {{-- MORE VEHICLE OPTIONS LOOP --}}
    {{-- ======================================================= --}}
    <div class="more-options-wrapper">
        <div class="mo-header">
            <div class="mo-title">More Vehicle Option</div>
            <div class="mo-subtitle">Select another vehicle that suits your needs</div>
        </div>
        @foreach($vehicleOptions as $vOpt)
            <div class="option-card {{ $vOpt['vehicle_id'] == $defaultVehicle['vehicle_id'] ? 'd-none-custom' : '' }}"
                 id="card_vehicle_{{ $vOpt['vehicle_id'] }}">
                <div class="row align-items-center">
                    <div class="col-md-3 oc-img-box">
                        <img src="{{ $vOpt['image'] }}" class="oc-img">
                    </div>
                    <div class="col-md-9">
                        <div class="oc-details">
                            <div class="oc-title">
                                {{ $vOpt['capacity_passenger'] }} Passenger {{ $vOpt['name'] }}
                            </div>
                            <div class="oc-features">
                                <i class="fas fa-suitcase"></i> {{ $vOpt['capacity_luggage'] }} Bags Capacity
                                <span class="oc-divider">|</span>
                                <i class="fas fa-car"></i> {{ $vOpt['features'][0] ?? 'Luxury' }}
                            </div>
                            <div class="oc-breakdown">
                                Estimated: ${{ number_format($vOpt['estimated_fare'], 2) }}, Gratuity: ${{ number_format($vOpt['gratuity_fee'], 2) }}
                                @if($vOpt['surcharge_fee'] > 0) , Surcharge: ${{ number_format($vOpt['surcharge_fee'], 2) }} @endif
                                @if($vOpt['extra_luggage_fee'] > 0) , Extra Luggage: ${{ number_format($vOpt['extra_luggage_fee'], 2) }} @endif
                            </div>

                            <div class="oc-action-container">
                                <button type="button" class="btn-book-outline" onclick="selectVehicle({{ json_encode($vOpt) }})">
                                    Book Now <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                <div>
                                    <span class="oc-total-label">Total Fare :</span>
                                    <span class="oc-price">${{ number_format($vOpt['total_fare'], 2)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function formatMoney(amount) {
        return Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function selectVehicle(data) {
        // 1. SHOW ALL cards first
        document.querySelectorAll('.option-card').forEach(el => {
            el.classList.remove('d-none-custom');
        });

        // 2. Hide the clicked card
        const clickedCard = document.getElementById('card_vehicle_' + data.vehicle_id);
        if(clickedCard) {
            clickedCard.classList.add('d-none-custom');
        }

        // 3. Update Hidden Inputs
        document.getElementById('inp_vehicle_id').value = data.vehicle_id;
        document.getElementById('inp_cap_pass').value = data.capacity_passenger;
        document.getElementById('inp_cap_lug').value = data.capacity_luggage;
        document.getElementById('inp_v_name').value = data.name;
        document.getElementById('inp_est_fare').value = data.estimated_fare;
        document.getElementById('inp_gratuity').value = data.gratuity_fee;
        document.getElementById('inp_surcharge').value = data.surcharge_fee;
        document.getElementById('inp_ex_lug_fee').value = data.extra_luggage_fee;
        document.getElementById('inp_total').value = data.total_fare;

        const surContainer = document.getElementById('surcharge_inputs_container');
        surContainer.innerHTML = '';
        if(data.surcharge_details && data.surcharge_details.length > 0) {
            data.surcharge_details.forEach((sd, idx) => {
                surContainer.innerHTML += `<input type="hidden" name="surcharge_details[${idx}][name]" value="${sd.name}">`;
                surContainer.innerHTML += `<input type="hidden" name="surcharge_details[${idx}][amount]" value="${sd.amount}">`;
            });
        }

        // 4. Update Visuals
        document.getElementById('disp_image').src = data.image;
        document.getElementById('disp_pax_cap').innerText = data.capacity_passenger;
        document.getElementById('disp_name').innerText = data.name;
        document.getElementById('disp_lug_cap').innerText = data.capacity_luggage;
        document.getElementById('disp_pay_cash').innerText = formatMoney(data.pay_cash);
        document.getElementById('disp_pay_card').innerText = formatMoney(data.total_fare);

        document.getElementById('tbl_est_fare').innerText = formatMoney(data.estimated_fare);
        document.getElementById('tbl_gratuity').innerText = formatMoney(data.gratuity_fee);

        const rowSur = document.getElementById('row_surcharge');
        if(data.surcharge_fee > 0) {
            rowSur.style.display = 'table-row';
            document.getElementById('tbl_surcharge').innerText = formatMoney(data.surcharge_fee);
        } else {
            rowSur.style.display = 'none';
        }

        // Update Extra Luggage Card
        const boxExLug = document.getElementById('box_ex_lug');
        if(data.extra_luggage_fee > 0) {
            boxExLug.style.display = 'flex';
            document.getElementById('txt_ex_lug_total').innerText = formatMoney(data.extra_luggage_fee);

            // Calculate Rate
            let rate = 0;
            if(data.extra_luggage_count > 0) {
                rate = (data.extra_luggage_fee / data.extra_luggage_count).toFixed(2);
            }
            document.getElementById('txt_ex_lug_rate').innerText = rate;

        } else {
            boxExLug.style.display = 'none';
        }

        document.getElementById('tbl_total').innerText = formatMoney(data.total_fare);

        // Update Summary Fields
        document.getElementById('sum_name').innerText = data.name;
        document.getElementById('sum_pax').innerText = data.capacity_passenger;
        document.getElementById('sum_lug').innerText = data.capacity_luggage;
        document.getElementById('sum_total').innerText = formatMoney(data.total_fare);

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection
