<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment | Premium Reservation</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-bg: #0b1d1f;
      --secondary-bg: #132d2f;
      --input-bg: #0f2527;
      --accent-color: #10b981;
      --gold-accent: #d4af37;
      --text-main: #f3f4f6;
      --text-muted: #9ca3af;
      --danger-color: #ef4444;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--primary-bg);
      color: var(--text-main);
      padding-bottom: 80px;
    }

    /* --- HEADER --- */
    .page-header {
      padding: 40px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      margin-bottom: 40px;
    }
    .step-text { color: var(--text-muted); font-size: 0.9rem; margin-top: 5px; }

    /* --- PAYMENT OPTION BOXES --- */
    .payment-option-label {
      cursor: pointer;
      width: 100%;
    }
    .payment-card {
      background: var(--input-bg);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s;
      height: 100%;
      position: relative;
    }
    .payment-card:hover { border-color: var(--accent-color); }

    /* Hide actual radio buttons */
    .payment-radio { display: none; }

    /* Selected State */
    .payment-radio:checked + .payment-card {
      background: rgba(16,185,129,0.1);
      border-color: var(--accent-color);
      box-shadow: 0 0 15px rgba(16,185,129,0.2);
    }

    .pay-amount { font-size: 1.25rem; font-weight: 700; color: #fff; display: block; margin-bottom: 5px; }
    .pay-desc { font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 10px; }
    .badge-custom { padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; }
    .badge-cash { background: #064e3b; color: var(--accent-color); border: 1px solid var(--accent-color); }
    .badge-card { background: #422006; color: #facc15; border: 1px solid #facc15; }

    /* --- FORM CARD --- */
    .form-card {
      background: var(--secondary-bg);
      border: 1px solid rgba(255,255,255,0.05);
      border-radius: 8px;
      padding: 30px;
    }

    .form-label { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; }

    .form-control, .form-select {
      background: var(--input-bg);
      border: 1px solid rgba(255,255,255,0.1);
      color: #fff;
      padding: 12px;
      font-size: 0.95rem;
    }
    .form-control:focus, .form-select:focus {
      background: var(--input-bg);
      border-color: var(--accent-color);
      color: #fff;
      box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    }

    /* Credit Card Visual */
    .cc-box {
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 6px;
      padding: 15px;
      background: rgba(255,255,255,0.02);
      margin-bottom: 20px;
    }

    /* --- SIDEBAR SUMMARY --- */
    .sidebar-card {
      background: rgba(212, 175, 55, 0.03);
      border: 1px solid rgba(212, 175, 55, 0.3);
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .sidebar-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 15px; border-bottom: 1px dashed rgba(255,255,255,0.1);
      padding-bottom: 10px;
    }
    .sidebar-title { font-weight: 700; font-size: 1.1rem; color: #fff; margin: 0; }
    .btn-change {
      background: #4b5563; color: #fff; font-size: 0.7rem;
      padding: 3px 8px; border-radius: 4px; text-decoration: none;
    }

    .summary-line { display: flex; margin-bottom: 10px; font-size: 0.85rem; }
    .s-label { width: 140px; color: var(--gold-accent); font-weight: 600; flex-shrink: 0; }
    .s-value { color: var(--text-main); flex-grow: 1; }

    /* --- BUTTONS --- */
    .btn-pay {
      background: #2563eb; /* Blue used in screenshot, adapted slightly */
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      color: #fff; font-weight: 700; text-transform: uppercase;
      padding: 15px 30px; border: none; border-radius: 6px; width: 100%;
      transition: all 0.3s;
    }
    .btn-pay:hover {
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
      color: #fff; transform: translateY(-2px);
    }
  </style>
</head>
<body>
 @include('frontend.pages.nav')
  <div class="container page-header">
    <h2 class="fw-bold m-0">Payment Information</h2>
    <div class="step-text">Your Current Selection ( Step 4 of 4 )</div>
  </div>

  <div class="container">
    <form action="" method="" id="paymentForm">
      <input type="hidden" name="date" value="{{ $request->date }}">
      <input type="hidden" name="time" value="{{ $request->time }}">
      <input type="hidden" name="tripType" value="{{ $request->tripType }}">
      <input type="hidden" name="pickup_address" value="{{ $request->pickup_address }}">
      <input type="hidden" name="dropoff_address" value="{{ $request->dropoff_address }}">
      <input type="hidden" name="adults" value="{{ $request->adults }}">
      <input type="hidden" name="children" value="{{ $request->children }}">
      <input type="hidden" name="luggage" value="{{ $request->luggage }}">
      <input type="hidden" name="vehicle_id" value="{{ $request->vehicle_id }}">
      <input type="hidden" name="final_total" value="{{ $request->final_total }}">
      <input type="hidden" name="passenger_name" value="{{ $request->passenger_name }}">
      <input type="hidden" name="passenger_email" value="{{ $request->passenger_email }}">
      <input type="hidden" name="phone_number" value="{{ $request->phone_number }}">
      <div class="row g-5">

        <div class="col-lg-7">

          <div class="row g-3 mb-4">
            <div class="col-6">
              <label class="payment-option-label">
                <input type="radio" name="payment_method" value="cash" class="payment-radio">
                <div class="payment-card">
                  <span class="pay-amount">$5291</span>
                  <span class="pay-desc">Get 10% Discount</span>
                  <span class="badge-custom badge-cash">Cash</span>
                </div>
              </label>
            </div>
            <div class="col-6">
              <label class="payment-option-label">
                <input type="radio" name="payment_method" value="card" class="payment-radio" checked>
                <div class="payment-card">
                  <span class="pay-amount">$6114</span>
                  <span class="pay-desc">4% Transaction Charges</span>
                  <span class="badge-custom badge-card">Credit Card</span>
                </div>
              </label>
            </div>
          </div>

          <p class="small text-muted mb-3">
            Pay $1 Reservation Fee and Confirm your Booking. Balance is payable by cash when you avail the service.
          </p>

          <h4 class="fw-bold mb-3">Credit card details :</h4>

          <div class="form-card">

            <div class="cc-box">
              <div class="input-group mb-0">
                <span class="input-group-text border-0"><i class="fas fa-credit-card"></i></span>
                <input type="text" class="form-control border-0" placeholder="Card number" name="cc_number">
                <input type="text" class="form-control border-0" placeholder="MM/YY" style="max-width: 100px;" name="cc_expiry">
                <input type="text" class="form-control border-0" placeholder="CVV" style="max-width: 80px;" name="cc_cvv">
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Card Holder Name</label>
                <input type="text" class="form-control" name="cc_name">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" name="billing_phone" value="{{ $request->phone_number }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="billing_email" value="{{ $request->passenger_email }}">
              </div>
              <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="billing_address">
              </div>
              <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="billing_city">
              </div>
              <div class="col-md-6">
                <label class="form-label">State</label>
                <input type="text" class="form-control" name="billing_state">
              </div>
              <div class="col-md-6">
                <label class="form-label">Country</label>
                <select class="form-select" name="billing_country">
                  <option selected>United States</option>
                  <option>United Kingdom</option>
                  <option>Canada</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Zip Code</label>
                <input type="text" class="form-control" name="billing_zip">
              </div>
            </div>

            <div class="mt-4">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label text-muted small" for="terms">
                  Yes, I have read and agree to Boston Airport Express Inc. <a href="#" class="text-accent-color">Terms & Conditions</a>
                </label>
              </div>
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="saveCard" name="save_card">
                <label class="form-check-label text-muted small" for="saveCard">
                  I allow you to Charge my Card after the Service is provided.
                </label>
              </div>

              <button type="submit" class="btn-pay">
                Pay with Card
              </button>
            </div>

          </div>

          <p class="small text-center text-muted mt-3">
            Avail 10% discount on cash payment.
          </p>

        </div>

        <div class="col-lg-5">

          <div class="sidebar-card">
            <div class="sidebar-header">
              <h5 class="sidebar-title text-gold">Vehicle Details</h5>
              <a href="{{ route('step2') }}" class="btn-change">Change</a>
            </div>

            <div class="summary-line">
              <span class="s-label">Vehicle</span>
              <span class="s-value text-capitalize">{{ $request->vehicle_id ?? 'Selected Vehicle' }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Distance</span>
              <span class="s-value">960 Miles</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Estimated Fare</span>
              <span class="s-value text-decoration-line-through text-muted" style="margin-right:8px;">$5200</span>
              <span class="s-value">$4870.00</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Gratuity (20%)</span>
              <span class="s-value">$974.00</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Airport Tax</span>
              <span class="s-value">$15.00</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Night Charges</span>
              <span class="s-value">$10.00</span>
            </div>
             <div class="summary-line">
              <span class="s-label">Child Seat</span>
              <span class="s-value">$10.00</span>
            </div>

            <div class="summary-line mt-3 pt-3 border-top border-secondary border-opacity-25">
               <span class="s-label text-white">Total Payable</span>
               <span class="s-value text-success fw-bold fs-5">${{ $request->final_total }}</span>
            </div>
          </div>

          <div class="sidebar-card">
            <div class="sidebar-header">
              <h5 class="sidebar-title text-gold">Booking Details</h5>
              <a href="{{ route('home') }}" class="btn-change">Change</a>
            </div>

            <div class="summary-line">
              <span class="s-label">Service</span>
              <span class="s-value">
                {{ $request->tripType == 'fromAirport' ? 'Ride From Airport' : 'Door to Door' }}
              </span>
            </div>
            <div class="summary-line">
              <span class="s-label">Date</span>
              <span class="s-value">{{ $request->date }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Time</span>
              <span class="s-value">{{ $request->time }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Pick up</span>
              <span class="s-value text-break">{{ $request->pickup_address }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Drop off</span>
              <span class="s-value text-break">{{ $request->dropoff_address }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Passengers</span>
              <span class="s-value">{{ (int)$request->adults + (int)$request->children }}</span>
            </div>
            <div class="summary-line">
              <span class="s-label">Luggage</span>
              <span class="s-value">{{ $request->luggage }}</span>
            </div>
          </div>

        </div>

      </div>
    </form>
  </div>

  <script>
    // Simple logic to toggle button text or styling based on Payment Method
    const payBtn = document.querySelector('.btn-pay');
    const radios = document.querySelectorAll('input[name="payment_method"]');

    radios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if(e.target.value === 'cash') {
                payBtn.textContent = 'Pay $1 Reservation Fee';
            } else {
                payBtn.textContent = 'Pay with Card';
            }
        });
    });
  </script>

</body>
</html>
