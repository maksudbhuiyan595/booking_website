<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Passenger Info | Premium Reservation</title>

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

    /* --- FORM CARD --- */
    .form-card {
      background: var(--secondary-bg);
      border: 1px solid rgba(255,255,255,0.05);
      border-radius: 8px;
      padding: 30px;
    }

    /* Gray Bar (Traveler Question) - Dark Theme Adaptation */
    .traveler-check-bar {
      background: rgba(255,255,255,0.05);
      padding: 15px 20px;
      border-radius: 6px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 20px;
      font-weight: 600;
    }

    /* Form Inputs */
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

    .input-group-text {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      color: #fff;
    }

    /* --- SIDEBAR SUMMARY CARDS --- */
    .sidebar-card {
      background: rgba(212, 175, 55, 0.03); /* Subtle Gold Tint */
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

    .summary-line {
      display: flex; margin-bottom: 10px; font-size: 0.85rem;
    }
    .s-label { width: 110px; color: var(--gold-accent); font-weight: 600; flex-shrink: 0; }
    .s-value { color: var(--text-main); flex-grow: 1; }

    /* --- BUTTONS --- */
    .btn-submit {
      background: #064e3b; /* Dark Green base */
      background: linear-gradient(135deg, #065f46 0%, #047857 100%);
      color: #fff;
      font-weight: 700;
      text-transform: uppercase;
      padding: 15px 30px;
      border: none;
      border-radius: 6px;
      transition: all 0.3s;
    }
    .btn-submit:hover {
      background: linear-gradient(135deg, #047857 0%, #059669 100%);
      box-shadow: 0 5px 15px rgba(16,185,129,0.3);
      color: #fff;
    }

    /* Custom Radio */
    .form-check-input {
      background-color: var(--input-bg);
      border-color: rgba(255,255,255,0.3);
    }
    .form-check-input:checked {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
    }
  </style>
</head>
<body>

  <div class="container page-header">
    <h2 class="fw-bold m-0">Passenger Information</h2>
    <div class="step-text">Your Current Selection ( Step 3 of 4 )</div>
  </div>

  <div class="container">
    <form action="{{ route('step4') }}" method="GET">
      <input type="hidden" name="date" value="{{ $request->date }}">
      <input type="hidden" name="time" value="{{ $request->time }}">
      <input type="hidden" name="tripType" value="{{ $request->tripType }}">
      <input type="hidden" name="pickup_address" value="{{ $request->pickup_address }}">
      <input type="hidden" name="dropoff_address" value="{{ $request->dropoff_address }}">
      <input type="hidden" name="vehicle_id" value="{{ $request->vehicle_id }}">
      <input type="hidden" name="final_total" value="{{ $request->final_total }}">
      <div class="row g-5">

        <div class="col-lg-8">
          <div class="form-card">

            <div class="traveler-check-bar">
              <span>Are you also the traveler ?</span>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_traveler" id="yesTraveler" value="yes" checked>
                <label class="form-check-label text-white" for="yesTraveler">Yes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_traveler" id="noTraveler" value="no">
                <label class="form-check-label text-white" for="noTraveler">No</label>
              </div>
            </div>

            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label">Passenger Name</label>
                <input type="text" class="form-control" name="passenger_name" placeholder="John Doe" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Passenger Email</label>
                <input type="email" class="form-control" name="passenger_email" placeholder="email@example.com" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Airline Name</label>
                <input type="text" class="form-control" name="airline_name" placeholder="e.g. Delta">
              </div>
              <div class="col-md-6">
                <label class="form-label">Flight No.</label>
                <input type="text" class="form-control" name="flight_number" placeholder="e.g. DL123">
              </div>

              <div class="col-md-6">
                <label class="form-label">Passenger Phone Number</label>
                <div class="input-group">
                  <select class="form-select" style="max-width: 100px;">
                    <option value="+1">USA (+1)</option>
                    <option value="+44">UK (+44)</option>
                  </select>
                  <input type="tel" class="form-control" name="phone_number" required>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Alternate Phone Number</label>
                <input type="tel" class="form-control" name="alternate_phone" placeholder="Mobile number">
              </div>

              <div class="col-12">
                <label class="form-label">Mailing Address</label>
                <textarea class="form-control" name="mailing_address" rows="2"></textarea>
              </div>

              <div class="col-12">
                <label class="form-label">Special Needs</label>
                <textarea class="form-control" name="special_needs" rows="3" placeholder="Child seat requirements, accessibility needs, etc."></textarea>
              </div>

              <div class="col-12 text-end mt-4">
                <button type="submit" class="btn-submit">
                  Continue to Payment <i class="fas fa-chevron-right ms-2"></i>
                </button>
              </div>

              <div class="col-12 text-center mt-3">
                <p class="small text-muted mb-0">
                  Pay only $1 & confirm your reservation. Balance is payable after service by cash or card.
                </p>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-4">

          <div class="sidebar-card">
            <div class="sidebar-header">
              <h5 class="sidebar-title">Booking Details</h5>
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

          <div class="sidebar-card">
            <div class="sidebar-header">
              <h5 class="sidebar-title">Vehicle Details</h5>
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

            <div class="summary-line mt-3 pt-3 border-top border-secondary border-opacity-25">
               <span class="s-label text-white">Total</span>
               <span class="s-value text-end text-success fw-bold fs-5">${{ $request->final_total }}</span>
            </div>
          </div>

        </div>

      </div>
    </form>
  </div>

</body>
</html>
