
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm Ride | Premium Reservation</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-bg: #0b1d1f;
      --secondary-bg: #132d2f;
      --accent-color: #10b981;
      --gold-accent: #d4af37;
      --text-main: #f3f4f6;
      --text-muted: #9ca3af;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--primary-bg);
      color: var(--text-main);
      padding-bottom: 60px;
    }

    /* --- PAGE HEADER --- */
    .page-header {
      padding: 40px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      margin-bottom: 40px;
    }
    .step-text { color: var(--text-muted); font-size: 0.9rem; margin-top: 5px; }

    /* --- CARDS COMMON --- */
    .custom-card {
      background: var(--secondary-bg);
      border: 1px solid rgba(255,255,255,0.05);
      border-radius: 8px;
      overflow: hidden;
      height: 100%;
    }

    /* --- COLUMN 1: VEHICLE --- */
    .vehicle-img-box {
      background: radial-gradient(circle at center, #234a4e 0%, #132d2f 70%);
      padding: 30px 10px;
      text-align: center;
    }
    .vehicle-img { width: 100%; max-width: 280px; object-fit: contain; }
    .vehicle-title {
      background: rgba(0,0,0,0.2);
      padding: 15px;
      text-align: center;
      font-weight: 700;
      font-size: 1.1rem;
      border-top: 1px solid rgba(255,255,255,0.05);
    }
    .vehicle-stats {
      display: flex;
      justify-content: space-around;
      padding: 20px;
      text-align: center;
    }
    .stat-item i { font-size: 1.5rem; margin-bottom: 8px; color: var(--text-muted); }
    .stat-item span { display: block; font-size: 0.8rem; font-weight: 600; }

    /* --- COLUMN 2: BREAKDOWN --- */
    .price-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px dashed rgba(255,255,255,0.1);
      font-size: 0.95rem;
    }
    .price-row:last-child { border-bottom: none; }
    .total-row {
      border-top: 2px solid rgba(255,255,255,0.1);
      margin-top: 10px;
      padding-top: 15px;
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--accent-color);
    }

    .extra-luggage-box {
      background: rgba(0,0,0,0.2);
      border: 1px solid rgba(255,255,255,0.1);
      padding: 15px;
      border-radius: 6px;
      margin-top: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* --- COLUMN 3: SUMMARY (Yellow Box Replica) --- */
    .summary-card {
      /* Matching your image's distinct box, but using Dark/Gold theme */
      background: rgba(212, 175, 55, 0.05); /* Very subtle gold tint */
      border: 1px solid var(--gold-accent);
    }
    .summary-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px;
    }
    .btn-change {
      background: #4b5563; color: #fff; font-size: 0.75rem;
      padding: 4px 10px; border-radius: 4px; text-decoration: none;
    }
    .summary-line {
      display: flex; margin-bottom: 12px; font-size: 0.9rem;
    }
    .s-label { width: 100px; color: var(--gold-accent); font-weight: 600; flex-shrink: 0; }
    .s-value { color: #fff; line-height: 1.4; }

    /* --- BUTTON --- */
    .btn-book {
      background: var(--accent-color);
      color: #000;
      width: 100%;
      font-weight: 700;
      padding: 12px;
      text-transform: uppercase;
      border: none;
      border-radius: 6px;
      margin-bottom: 10px;
    }
    .btn-book:hover { background: #0e9f6e; }

    .payment-note { font-size: 0.8rem; color: var(--text-muted); text-align: center; line-height: 1.4; }

  </style>
</head>
<body>
   @include('frontend.pages.nav')
  <div class="container page-header">
    <h2 class="fw-bold m-0">Select Vehicle & Confirm Ride Details</h2>
    <div class="step-text">Your Current Selection ( Step 2 of 4 )</div>
  </div>

  <div class="container">
    <form action="{{ route('step3') }}" method="GET">
      <input type="hidden" name="date" value="{{ $request->date }}">
      <input type="hidden" name="time" value="{{ $request->time }}">
      <input type="hidden" name="tripType" value="{{ $request->tripType }}">
      <input type="hidden" name="pickup_address" value="{{ $request->pickup_address }}">
      <input type="hidden" name="dropoff_address" value="{{ $request->dropoff_address }}">
      <input type="hidden" name="adults" value="{{ $request->adults }}">
      <input type="hidden" name="children" value="{{ $request->children }}">
      <input type="hidden" name="luggage" value="{{ $request->luggage }}">
      <input type="hidden" name="final_total" value="5879">

      <div class="row g-4">

        <div class="col-lg-4">
          <div class="custom-card">
            <div class="vehicle-img-box">
              <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=1000&auto=format&fit=crop" class="vehicle-img" alt="Van">
            </div>
            <div class="vehicle-title">
              7 Passenger Luxury Van
            </div>
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

        <div class="col-lg-4">
          <div class="p-2"> <h4 class="mb-4 fw-bold">Booking Details</h4>

            <div class="price-row">
              <span>Distance Covered</span>
              <span>960 Miles</span>
            </div>
            <div class="price-row">
              <span>Estimated Fare</span>
              <span>$4870.00</span>
            </div>
            <div class="price-row">
              <span>Gratuity (20%)</span>
              <span>$974.00</span>
            </div>
            <div class="price-row">
              <span>Airport Toll Tax</span>
              <span>$15.00</span>
            </div>
            <div class="price-row">
              <span>Night Charges</span>
              <span>$10.00</span>
            </div>
            <div class="price-row">
              <span>Child Seat / Stopover</span>
              <span>${{ $request->extras_total }}</span>
            </div>

            <div class="price-row total-row">
              <span>Total Payable</span>
              <span>$5879.00</span>
            </div>

            <div class="extra-luggage-box">
              <span class="small fw-bold">Extra Luggage</span>
              <div class="text-end">
                <div class="fw-bold fs-5 text-white">$0</div>
                <div class="small text-muted" style="font-size:0.7rem">($10.00/Bag)</div>
              </div>
            </div>
            <p class="small text-muted mt-2 fst-italic">* 4 bags Free with this car. Select Extra luggage as required.</p>
          </div>
        </div>

        <div class="col-lg-4">

          <div class="mb-4 text-center">
            <button type="submit" class="btn-book">Book Now</button>
            <div class="payment-note">
              Pay only $1 & confirm your reservation. Balance is payable after service.
            </div>
          </div>

          <div class="custom-card summary-card p-4">
            <div class="summary-header">
              <h5 class="m-0 fw-bold text-white">Booking Details</h5>
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
              <span class="s-value">{{ $request->pickup_address }}</span>
            </div>

            <div class="summary-line">
              <span class="s-label">Drop off</span>
              <span class="s-value">{{ $request->dropoff_address }}</span>
            </div>

            <div class="summary-line">
              <span class="s-label">Passengers</span>
              <span class="s-value">
                {{ (int)$request->adults + (int)$request->children }}
                <span class="text-muted small">({{ $request->adults }} Adults + {{ $request->children }} Children)</span>
              </span>
            </div>

            <div class="summary-line">
              <span class="s-label">Luggage</span>
              <span class="s-value">{{ $request->luggage }}</span>
            </div>

          </div>
        </div>

      </div> </form>
  </div>

</body>
</html>
