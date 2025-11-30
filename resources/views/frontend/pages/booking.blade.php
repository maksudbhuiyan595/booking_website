<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Premium Reservation</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-bg: #0b1d1f;
      --secondary-bg: #132d2f;
      --accent-color: #10b981;
      --light-accent: #57d4a2;
      --text-main: #f3f4f6;
      --text-muted: #9ca3af;
      --nav-height: 80px;
    }

    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: var(--primary-bg);
      color: var(--text-main);
      overflow-x: hidden;
    }

    /* --- NAVBAR --- */
    #navbar {
      height: var(--nav-height);
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      background: rgba(11, 29, 31, 0.95);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.04);
      display: flex;
      align-items: center; /* Vertically center content */
    }

    .nav-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
    }

    /* LOGO FIX */
    .navbar-brand {
      padding: 0;
      display: flex;
      align-items: center;
      text-decoration: none;
    }
    .navbar-brand img {
      height: 50px; /* Fixed height for consistency */
      width: auto;
      object-fit: contain;
      display: block;
    }

    .nav-links { display: flex; gap: 28px; align-items: center; list-style: none; margin: 0; padding: 0; }
    .nav-link { color: var(--text-main); text-decoration: none; font-weight: 500; transition: color 0.3s; }
    .nav-link:hover, .nav-link.active { color: var(--accent-color); }

    /* Hamburger */
    .menu-toggle { display: none; cursor: pointer; gap: 6px; flex-direction: column; }
    .menu-toggle span { width: 28px; height: 3px; background: var(--accent-color); border-radius: 3px; }

    /* Mobile Nav */
    .mobile-nav {
      position: fixed; top: var(--nav-height); right: -100%; width: 100%; height: calc(100vh - var(--nav-height));
      background: var(--primary-bg); transition: right .32s ease; z-index: 999;
      display: flex; align-items: center; justify-content: center;
    }
    .mobile-nav.active { right: 0; }
    .mobile-nav-links { list-style: none; padding: 0; text-align: center; }
    .mobile-nav-links a { display: block; padding: 12px 24px; font-size: 1.4rem; color: var(--text-main); text-decoration: none; }

    /* --- FORM STYLES --- */
    .reservation-section { padding-top: calc(var(--nav-height) + 40px); padding-bottom: 60px; }
    .reservation-card { background: var(--secondary-bg); border-radius: 14px; border: 1px solid rgba(255,255,255,0.04); }

    .form-control, .form-select {
      background: var(--primary-bg) !important;
      color: var(--text-main) !important;
      border: 1px solid #2c4a4c !important;
      padding: 12px 15px;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 3px rgba(16,185,129,0.12) !important;
      border-color: var(--accent-color) !important;
      outline: none;
    }

    .input-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none; opacity: .75; }
    .position-relative { position: relative; }

    /* Custom Radio */
    .custom-radio-input { appearance: none; width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--light-accent); background: transparent; margin-right: 8px; vertical-align: middle; cursor: pointer; }
    .custom-radio-input:checked { background: var(--light-accent); box-shadow: 0 0 12px rgba(87,212,162,0.18); }
    .seat-select { padding-right: 1rem; min-width: 72px; }

    /* Footer */
    footer { background: #061213; padding: 60px 0 24px; border-top: 1px solid #1f3638; color: var(--text-muted); margin-top: auto; }
    .footer-content { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 36px; padding: 0 20px 36px; }
    .social-link { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; background: #1f3638; color: #fff; border-radius: 50%; text-decoration: none; margin-right: 8px; }
    .social-link:hover { background: var(--accent-color); color: #000; }

    @media (max-width: 992px) { .footer-content { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 768px) {
      .nav-links { display: none; }
      .menu-toggle { display: flex; }
      .footer-content { grid-template-columns: 1fr; gap: 28px; }
    }
  </style>
</head>
<body>

  <nav id="navbar">
    <div class="nav-container">
      <a class="navbar-brand" href="#">
         <img src="https://cdn-icons-png.flaticon.com/512/3097/3097144.png" alt="Logo">
      </a>

      <ul class="nav-links">
        <li><a href="#" class="nav-link active">Home</a></li>
        <li><a href="#" class="nav-link">Services</a></li>
        <li><a href="#" class="nav-link">Fleet</a></li>
        <li><a href="#" class="nav-link">Contact</a></li>
      </ul>

      <div class="menu-toggle" id="menuToggle" aria-expanded="false">
        <span></span><span></span><span></span>
      </div>
    </div>
  </nav>

  <div class="mobile-nav" id="mobileNav" aria-hidden="true">
    <ul class="mobile-nav-links">
      <li><a href="#">Home</a></li>
      <li><a href="#">Services</a></li>
      <li><a href="#">Fleet</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </div>

  <section class="reservation-section">
    <div class="container">
      <div class="row align-items-center">

        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="p-1 border border-secondary border-opacity-25 rounded shadow-lg">
            <div class="p-4 reservation-card">

              <header class="text-center mb-4">
                <h1 class="fs-4 fw-bold text-white text-uppercase" style="letter-spacing:2px">Instant Reservation</h1>
                <p class="small text-secondary mt-1">Instant SMS & Email Confirmation</p>
              </header>

              <form id="reservationForm" action="{{ route('step2') }}" method="GET" novalidate>
                <input type="hidden" name="extras_total" id="extrasTotalInput" value="0">

                <div class="row g-2 mb-3">
                  <div class="col-6 position-relative">
                    <label for="date" class="form-label small text-secondary">Date</label>
                    <input type="date" id="date" name="date" required class="form-control" />
                  </div>
                  <div class="col-6 position-relative">
                    <label for="time" class="form-label small text-secondary">Time</label>
                    <select id="time" name="time" required class="form-select" aria-label="Select time">
                      <option value="">Select Time</option>
                    </select>
                    <span class="input-icon">⏱️</span>
                  </div>
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-4">
                    <label class="d-flex align-items-center">
                      <input class="custom-radio-input" type="radio" name="tripType" value="fromAirport">
                      <span class="small text-white">From Airport</span>
                    </label>
                  </div>
                  <div class="col-4">
                    <label class="d-flex align-items-center">
                      <input class="custom-radio-input" type="radio" name="tripType" value="toAirport" checked>
                      <span class="small text-white">To Airport</span>
                    </label>
                  </div>
                  <div class="col-4">
                    <label class="d-flex align-items-center">
                      <input class="custom-radio-input" type="radio" name="tripType" value="doorToDoor">
                      <span class="small text-white">Door-to-Door</span>
                    </label>
                  </div>
                </div>

                <div class="mb-3">
                  <p id="fromLabel" class="small text-secondary fw-bold ms-1 mb-1">Pickup Address</p>
                  <div id="fromLocation" class="mb-3"></div>

                  <p id="toLabel" class="small text-secondary fw-bold ms-1 mb-1">Destination</p>
                  <div id="toLocation"></div>
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <label class="small text-secondary mb-1">Adults</label>
                    <div class="position-relative">
                      <select id="adults" name="adults" class="form-select">
                        <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                      </select>
                      <span class="input-icon">👥</span>
                    </div>
                  </div>
                  <div class="col-6">
                    <label class="small text-secondary mb-1">Children</label>
                    <div class="position-relative">
                      <select id="children" name="children" class="form-select">
                        <option value="0">0</option><option value="1">1</option><option value="2">2</option>
                      </select>
                      <span class="input-icon">👶</span>
                    </div>
                  </div>
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <label class="small text-secondary mb-1">Luggage</label>
                    <div class="position-relative">
                      <select id="luggage" name="luggage" class="form-select">
                        <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option>
                      </select>
                      <span class="input-icon">💼</span>
                    </div>
                  </div>
                  <div class="col-6">
                    <label class="small text-secondary mb-1">Child Seats</label>
                    <div class="position-relative">
                      <select id="childSeats" name="childSeats" class="form-select">
                        <option value="0">None</option><option value="1">1</option><option value="2">2</option>
                      </select>
                      <span class="input-icon">🧒</span>
                    </div>
                  </div>
                </div>

                <div class="border-top border-secondary border-opacity-25 pt-3 mt-3">
                  <div class="d-flex align-items-center mb-3">
                    <span class="fs-5 fw-bold text-success me-2">+</span>
                    <h3 class="fs-6 fw-semibold text-white mb-0">Extras</h3>
                  </div>

                  <div class="d-flex justify-content-between align-items-center py-1">
                    <div>
                      <label class="small text-light d-block mb-0 fw-semibold">Stopover</label>
                      <span class="small text-secondary">$25 / stop</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <select id="stopover" name="stopover" data-price="25" class="form-select seat-select me-2">
                        <option value="0">0</option><option value="1">1</option><option value="2">2</option>
                      </select>
                      <span id="stopoverTotal" class="fw-bold text-success" style="width:52px; text-align:right;">$0</span>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center py-1">
                    <div>
                      <label class="small text-light d-block mb-0 fw-semibold">Infant Seat</label>
                      <span class="small text-secondary">$10 (0-2 yrs)</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <select id="infantSeat" name="infant_seat" data-price="10" class="form-select seat-select me-2">
                        <option value="0">0</option><option value="1">1</option>
                      </select>
                      <span id="infantSeatTotal" class="fw-bold text-success" style="width:52px; text-align:right;">$0</span>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center py-1">
                    <div>
                      <label class="small text-light d-block mb-0 fw-semibold">Booster Seat</label>
                      <span class="small text-secondary">$5 (5-7 yrs)</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <select id="boosterSeat" name="booster_seat" data-price="5" class="form-select seat-select me-2">
                        <option value="0">0</option><option value="1">1</option>
                      </select>
                      <span id="boosterSeatTotal" class="fw-bold text-success" style="width:52px; text-align:right;">$0</span>
                    </div>
                  </div>
                </div>

                <div id="messageBox" class="alert d-none mt-4 text-center fw-medium p-2 small"></div>

                <button type="submit" class="btn w-100 mt-4 text-dark fw-bold text-uppercase py-3"
                        style="background-color:var(--accent-color); box-shadow:0 4px 15px rgba(16,185,129,0.35);">
                  Get Fare & Continue
                </button>

                <p class="text-center small mt-3 text-secondary" style="font-size:0.75rem;">
                  Pay <span class="text-success">$1</span> to confirm. 10% Off for cash payments.
                </p>

              </form>
              </div>
          </div>
        </div>

        <div class="col-lg-6 ps-lg-5">
          <div class="position-relative">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; border:2px solid var(--accent-color); z-index:-1;"></div>

            <img src="https://images.unsplash.com/photo-1485291571150-772bcfc10da5?q=80&w=1000&auto=format&fit=crop"
                 class="img-fluid rounded shadow-lg border border-secondary border-opacity-25 w-100"
                 alt="Luxury car" style="object-fit:cover; height:700px; filter:brightness(0.8);">

            <div class="position-absolute bottom-0 start-0 bg-dark p-4 m-4 rounded shadow bg-opacity-75" style="backdrop-filter: blur(6px);">
              <h4 class="text-white fw-bold">Luxury & Comfort</h4>
              <p class="mb-0 text-light small">Professional chauffeurs, pristine vehicles.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <footer>
    <div class="footer-content">
      <div>
        <h5 class="text-white mb-3">LUXE RIDE</h5>
        <p class="small">Experience the best in class chauffeur service. Reliable, Safe and Luxurious.</p>
        <div class="mt-3">
            <a href="#" class="social-link">F</a>
            <a href="#" class="social-link">T</a>
            <a href="#" class="social-link">I</a>
        </div>
      </div>
      <div>
        <h6 class="text-white mb-3">Company</h6>
        <div style="display:flex; flex-direction:column; gap:8px; font-size:0.9rem;">
          <a href="#" style="color:inherit; text-decoration:none;">About</a>
          <a href="#" style="color:inherit; text-decoration:none;">Services</a>
          <a href="#" style="color:inherit; text-decoration:none;">Fleet</a>
        </div>
      </div>
      <div>
        <h6 class="text-white mb-3">Support</h6>
        <div style="display:flex; flex-direction:column; gap:8px; font-size:0.9rem;">
          <a href="#" style="color:inherit; text-decoration:none;">FAQ</a>
          <a href="#" style="color:inherit; text-decoration:none;">Contact</a>
          <a href="#" style="color:inherit; text-decoration:none;">Terms</a>
        </div>
      </div>
      <div>
        <h6 class="text-white mb-3">Contact</h6>
        <p class="small">123 Luxury Ave,<br>New York, NY 10001<br>+1 (555) 123-4567</p>
      </div>
    </div>
    <div class="text-center small border-top border-secondary border-opacity-25 pt-3">
        &copy; 2025 Luxe Ride. All Rights Reserved.
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /* 1. Constants & Elements */
    const AIRPORTS = [
      { value: 'logan', label: 'Logan Airport (BOS)' },
      { value: 'jfk', label: 'JFK Airport (NY)' },
      { value: 'lax', label: 'Los Angeles (LAX)' }
    ];

    const timeSelect = document.getElementById('time');
    const dateInput = document.getElementById('date');
    const reservationForm = document.getElementById('reservationForm');
    const messageBox = document.getElementById('messageBox');

    /* 2. Mobile Menu Logic */
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');
    if(menuToggle){
        menuToggle.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
        });
        // Close on link click
        document.querySelectorAll('.mobile-nav-links a').forEach(a => {
            a.addEventListener('click', () => mobileNav.classList.remove('active'));
        });
    }

    /* 3. Generate Time Options */
    function generateTimeOptions() {
      if(!timeSelect) return;
      timeSelect.innerHTML = '<option value="">Select Time</option>';
      for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += 15) {
          const hh = String(h).padStart(2,'0');
          const mm = String(m).padStart(2,'0');
          const display = `${h%12||12}:${mm} ${h<12?'AM':'PM'}`;
          const opt = document.createElement('option');
          opt.value = `${hh}:${mm}`;
          opt.textContent = display;
          timeSelect.appendChild(opt);
        }
      }
    }

    /* 4. Dynamic Addresses (With NAME attributes) */
    const fromLocation = document.getElementById('fromLocation');
    const toLocation = document.getElementById('toLocation');
    const fromLabel = document.getElementById('fromLabel');
    const toLabel = document.getElementById('toLabel');

    function buildInput(id, name, placeholder) {
        return `<div class="position-relative"><input type="text" id="${id}" name="${name}" class="form-control" placeholder="${placeholder}" required></div>`;
    }

    function buildSelect(id, name) {
        let opts = AIRPORTS.map(a => `<option value="${a.value}">${a.label}</option>`).join('');
        return `<div class="position-relative">
                  <select id="${id}" name="${name}" class="form-select">${opts}</select>
                  <span class="input-icon">✈️</span>
                </div>`;
    }

    function updateAddressFields() {
        const type = document.querySelector('input[name="tripType"]:checked').value;
        fromLocation.innerHTML = '';
        toLocation.innerHTML = '';

        if(type === 'toAirport') {
            fromLabel.textContent = 'Pickup Address';
            toLabel.textContent = 'Dropoff Airport';
            fromLocation.innerHTML = buildInput('pickupAddr', 'pickup_address', 'Enter Pickup Address');
            toLocation.innerHTML = buildSelect('dropoffAirport', 'dropoff_airport');
        } else if(type === 'fromAirport') {
            fromLabel.textContent = 'Pickup Airport';
            toLabel.textContent = 'Dropoff Address';
            fromLocation.innerHTML = buildSelect('pickupAirport', 'pickup_airport');
            toLocation.innerHTML = buildInput('dropoffAddr', 'dropoff_address', 'Enter Dropoff Address');
        } else {
            fromLabel.textContent = 'Pickup Address';
            toLabel.textContent = 'Dropoff Address';
            fromLocation.innerHTML = buildInput('pickupAddr', 'pickup_address', 'Enter Pickup Address');
            toLocation.innerHTML = buildInput('dropoffAddr', 'dropoff_address', 'Enter Dropoff Address');
        }
    }

    /* 5. Extras Calculation */
    const extraIds = ['stopover', 'infantSeat', 'boosterSeat'];
    function calcExtras() {
        let total = 0;
        extraIds.forEach(id => {
            const el = document.getElementById(id);
            const price = parseInt(el.dataset.price);
            const qty = parseInt(el.value);
            const sub = price * qty;
            document.getElementById(id+'Total').textContent = '$' + sub;
            total += sub;
        });
        document.getElementById('extrasTotalInput').value = total;
    }

    /* 6. Event Listeners & Init */
    document.querySelectorAll('.custom-radio-input').forEach(r => r.addEventListener('change', updateAddressFields));
    extraIds.forEach(id => document.getElementById(id).addEventListener('change', calcExtras));

    window.addEventListener('DOMContentLoaded', () => {
        // Set Min Date
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
        dateInput.value = today;

        generateTimeOptions();
        updateAddressFields();
        calcExtras();
    });

    /* 7. Form Submit */
    reservationForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const dateVal = dateInput.value;
        const timeVal = timeSelect.value;
        const pInput = fromLocation.querySelector('input, select');
        const dInput = toLocation.querySelector('input, select');

        // Simple validation
        if(!dateVal || !timeVal || !pInput.value || !dInput.value) {
            messageBox.classList.remove('d-none', 'alert-success');
            messageBox.classList.add('alert', 'alert-danger');
            messageBox.textContent = "Please fill in all required fields (Date, Time, Locations).";
            return;
        }

        // Pass validation -> Submit to Server
        messageBox.classList.remove('d-none', 'alert-danger');
        messageBox.classList.add('alert', 'alert-success');
        messageBox.textContent = "Validating... Redirecting...";

        // Actually submit the form to the action URL
        reservationForm.submit();
    });
  </script>
</body>
</html>
