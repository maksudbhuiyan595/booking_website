<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Noir Car Booking</title>

  <!-- Bootstrap (for grid & components) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tailwind CDN kept (optional utilities) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap');

    :root{
      --primary-bg: #0b1d1f;
      --secondary-bg: #132d2f;
      --accent-color: #10b981;
      --light-accent: #57d4a2;
      --text-main: #f3f4f6;
      --text-muted: #9ca3af;
      --nav-height: 80px;
    }

    html,body{
      height:100%;
      margin:0;
      font-family: 'Inter', sans-serif;
      background:var(--primary-bg);
      color:var(--text-main);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      overflow-x:hidden;
    }

    /* NAV */
    #navbar{
      height:var(--nav-height);
      position:fixed;
      top:0;
      left:0;
      right:0;
      z-index:1000;
      background:rgba(11,29,31,0.95);
      backdrop-filter:blur(8px);
      border-bottom:1px solid rgba(255,255,255,0.04);
    }
    .nav-container{
      max-width:1200px;
      margin:0 auto;
      height:100%;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:0 20px;
    }
    .nav-links{ display:flex; gap:28px; align-items:center; list-style:none; margin:0; padding:0; }
    .nav-link{ color:var(--text-main); text-decoration:none; font-weight:500; }
    .nav-link:hover, .nav-link.active{ color:var(--accent-color); }
    .nav-cta{ background:var(--accent-color); color:#000; padding:8px 16px; border-radius:8px; font-weight:700; text-decoration:none; }

    /* Hamburger */
    .menu-toggle{ display:none; cursor:pointer; gap:6px; flex-direction:column; }
    .menu-toggle span{ width:28px; height:3px; background:var(--accent-color); border-radius:3px; display:block; }

    /* Mobile nav */
    .mobile-nav{
      position:fixed;
      top:var(--nav-height);
      right:-100%;
      width:100%;
      height:calc(100vh - var(--nav-height));
      background:var(--primary-bg);
      transition:right .32s ease;
      z-index:999;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .mobile-nav.active{ right:0; }
    .mobile-nav-links{ list-style:none; padding:0; text-align:center; }
    .mobile-nav-links a{ display:block; padding:12px 24px; font-size:1.4rem; color:var(--text-main); text-decoration:none; }

    /* Reservation section */
    .reservation-section{ padding-top: calc(var(--nav-height) + 44px); padding-bottom:60px; }
    .reservation-card{ background:var(--secondary-bg); border-radius:14px; border:1px solid rgba(255,255,255,0.04); }

    .form-control, .form-select{
      background:var(--primary-bg) !important;
      color:var(--text-main) !important;
      border:1px solid #2c4a4c !important;
    }
    .form-control:focus, .form-select:focus{
      box-shadow:0 0 0 3px rgba(16,185,129,0.12) !important;
      border-color:var(--accent-color) !important;
      outline: none;
    }

    .input-icon{ position:absolute; right:14px; top:50%; transform:translateY(-50%); pointer-events:none; opacity:.75; font-size:0.95rem; }
    .position-relative { position: relative; }

    /* custom radio */
    .custom-radio-input{ appearance:none; width:20px; height:20px; border-radius:50%; border:2px solid var(--light-accent); background:transparent; display:inline-block; margin-right:8px; vertical-align:middle; }
    .custom-radio-input:checked{ background:var(--light-accent); box-shadow:0 0 12px rgba(87,212,162,0.18); }

    /* extras seat-select */
    .seat-select{ padding-right:1rem; min-width:72px; }

    footer{ background:#061213; padding:60px 0 24px; border-top:1px solid #1f3638; color:var(--text-muted); }
    .footer-content{ max-width:1200px; margin:0 auto; display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:36px; padding:0 20px 36px; }

    .social-link{ width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; background:#1f3638; color:#fff; border-radius:50%; text-decoration:none; margin-right:8px; }
    .social-link:hover{ background:var(--accent-color); color:#000; }

    /* responsive tweaks */
    @media (max-width: 992px){ .footer-content{ grid-template-columns:1fr 1fr; } }
    @media (max-width: 768px){
      .nav-links{ display:none; }
      .menu-toggle{ display:flex; }
      .footer-content{ grid-template-columns:1fr; gap:28px; }
      .footer-bottom{ flex-direction:column; gap:12px; text-align:center; }
    }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav id="navbar" aria-label="Main navigation">
    <div class="nav-container">
      <a href="#home" class="logo-link text-white text-decoration-none fs-4 fw-bold" aria-label="Noir Ride home">
        NOIR<span style="color:var(--accent-color)">.</span>RIDE
      </a>

      <ul class="nav-links" role="menubar" aria-hidden="false">
        <li role="none"><a href="#home" class="nav-link active" role="menuitem">Home</a></li>
        <li role="none"><a href="#about" class="nav-link" role="menuitem">About</a></li>
        <li role="none"><a href="#services" class="nav-link" role="menuitem">Services</a></li>
        <li role="none"><a href="#contact" class="nav-link" role="menuitem">Contact</a></li>
        <li role="none"><a href="#book" class="nav-cta" role="menuitem">Book Now</a></li>
      </ul>

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <!-- Mobile nav -->
  <div class="mobile-nav" id="mobileNav" aria-hidden="true">
    <ul class="mobile-nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#contact">Contact</a></li>
      <li><a href="#book" class="nav-cta">Book Now</a></li>
    </ul>
  </div>

  <!-- Reservation -->
  <section class="reservation-section" id="home" aria-labelledby="reservationHeading">
    <div class="container">
      <div class="row align-items-center">

        <!-- FORM COLUMN -->
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="p-1 border border-secondary border-opacity-25 rounded shadow-lg">
            <div class="p-4 reservation-card">
              <header class="text-center mb-4">
                <h1 id="reservationHeading" class="fs-4 fw-bold text-white text-uppercase" style="letter-spacing:2px">Instant Reservation</h1>
                <p class="small text-secondary mt-1">Instant SMS & Email Confirmation</p>
              </header>

              <form id="reservationForm" novalidate>
                <div class="row g-2 mb-3">
                  <div class="col-6 position-relative">
                    <label for="date" class="form-label small text-secondary">Date</label>
                    <input type="date" id="date" name="date" required class="form-control" />
                  </div>

                  <div class="col-6 position-relative">
                    <label for="time" class="form-label small text-secondary">Time</label>
                    <select id="time" name="time" required class="form-select dynamic-select" aria-label="Select time">
                      <option value="">Select Time</option>
                    </select>
                    <span class="input-icon" aria-hidden="true">⏱️</span>
                  </div>
                </div>

                <div class="row g-2 mb-3" role="radiogroup" aria-label="Trip type">
                  <div class="col-4">
                    <label class="d-flex align-items-center" for="fromAirport">
                      <input class="custom-radio-input" type="radio" name="tripType" id="fromAirport" value="fromAirport" />
                      <span class="small text-white">From Airport</span>
                    </label>
                  </div>
                  <div class="col-4">
                    <label class="d-flex align-items-center" for="toAirport">
                      <input class="custom-radio-input" type="radio" name="tripType" id="toAirport" value="toAirport" />
                      <span class="small text-white">To Airport</span>
                    </label>
                  </div>
                  <div class="col-4">
                    <label class="d-flex align-items-center" for="doorToDoor">
                      <input class="custom-radio-input" type="radio" name="tripType" id="doorToDoor" value="doorToDoor" />
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
                    <label class="small text-secondary mb-1">Adults - 8 Years &amp; above</label>
                    <div class="position-relative">
                      <select id="adults" name="adults" required class="form-select dynamic-select" aria-label="Adults">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                      </select>
                      <span class="input-icon" aria-hidden="true">👥</span>
                    </div>
                  </div>

                  <div class="col-6">
                    <label class="small text-secondary mb-1">Children - Up to 7 Years</label>
                    <div class="position-relative">
                      <select id="children" name="children" class="form-select dynamic-select" aria-label="Children">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                      </select>
                      <span class="input-icon" aria-hidden="true">👶</span>
                    </div>
                  </div>
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <label class="small text-secondary mb-1">Luggage (pieces)</label>
                    <div class="position-relative">
                      <select id="luggage" name="luggage" class="form-select dynamic-select" aria-label="Luggage">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                      </select>
                      <span class="input-icon" aria-hidden="true">💼</span>
                    </div>
                  </div>

                  <div class="col-6">
                    <label class="small text-secondary mb-1">Child Seats</label>
                    <div class="position-relative">
                      <select id="childSeats" name="childSeats" class="form-select dynamic-select" aria-label="Child seats">
                        <option value="0">No child seat</option>
                        <option value="1">Infant (0-2 yrs)</option>
                        <option value="2">Child (3-4 yrs)</option>
                        <option value="3">Booster (5-7 yrs)</option>
                      </select>
                      <span class="input-icon" aria-hidden="true">🧒</span>
                    </div>
                  </div>
                </div>

                <div class="border-top border-secondary border-opacity-25 pt-3 mt-3">
                  <div class="d-flex align-items-center mb-3">
                    <span class="fs-5 fw-bold text-success me-2">+</span>
                    <h3 class="fs-6 fw-semibold text-white mb-0">Extras & Child Seats</h3>
                  </div>

                  <div class="d-flex justify-content-between align-items-center py-1">
                    <div>
                      <label class="small text-light d-block mb-0 fw-semibold">Stopover</label>
                      <span class="small text-secondary">$25 / stop</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <select id="stopover" data-price="25" class="form-select seat-select me-2" aria-label="Stopovers">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                      </select>
                      <span id="stopoverTotal" class="fw-bold text-success" style="width:52px; text-align:right;">$0</span>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center py-1">
                    <div>
                      <label class="small text-light d-block mb-0 fw-semibold">Infant Seat</label>
                      <span class="small text-secondary">$10 (0-24 Months)</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <select id="infantSeat" data-price="10" class="form-select seat-select me-2" aria-label="Infant seats">
                        <option value="0">0</option>
                        <option value="1">1</option>
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
                      <select id="boosterSeat" data-price="5" class="form-select seat-select me-2" aria-label="Booster seats">
                        <option value="0">0</option>
                        <option value="1">1</option>
                      </select>
                      <span id="boosterSeatTotal" class="fw-bold text-success" style="width:52px; text-align:right;">$0</span>
                    </div>
                  </div>
                </div>

                <div id="messageBox" class="alert d-none mt-4 text-center fw-medium p-2 small" role="status" aria-live="polite"></div>

                <button type="submit" class="btn btn-lg w-100 mt-4 text-dark fw-bold text-uppercase" style="background-color:var(--accent-color); box-shadow:0 4px 15px rgba(16,185,129,0.35);">
                  Get Fare & Continue
                </button>

                <p class="text-center small mt-3 text-secondary" style="font-size:0.75rem;">
                  Pay <span class="text-success">$1</span> to confirm. Balance due after service.
                  <span class="text-success">10% Off</span> for cash payments.
                </p>
              </form>

            </div>
          </div>
        </div>

        <!-- IMAGE/COPY COLUMN -->
        <div class="col-lg-6 ps-lg-5">
          <div class="position-relative">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; border:2px solid var(--accent-color); z-index:-1;"></div>

            <img src="https://images.unsplash.com/photo-1485291571150-772bcfc10da5?q=80&w=1000&auto=format&fit=crop"
                 class="img-fluid rounded shadow-lg border border-secondary border-opacity-25 w-100" alt="Luxury car" style="object-fit:cover; height:600px; filter:brightness(0.84);">

            <div class="position-absolute bottom-0 start-0 bg-dark p-4 m-4 rounded shadow bg-opacity-75" style="backdrop-filter: blur(6px);">
              <h4 class="text-white fw-bold">Luxury & Comfort</h4>
              <p class="mb-0 text-light small">Professional chauffeurs, pristine vehicles, and on-time service guaranteed.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div>
        <h3 style="color:var(--accent-color);">NOIR.RIDE</h3>
        <p style="color:var(--text-muted)">Redefining luxury transportation with bold designs, sustainable practices, and uncompromising quality. Travel in style.</p>
        <div style="margin-top:10px;">
          <a href="#" class="social-link" aria-label="Facebook">F</a>
          <a href="#" class="social-link" aria-label="Twitter">T</a>
          <a href="#" class="social-link" aria-label="Instagram">I</a>
          <a href="#" class="social-link" aria-label="LinkedIn">L</a>
        </div>
      </div>

      <div>
        <h4 style="color:#fff">Services</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Airport Shuttle</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Corporate Travel</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Hourly Charter</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Weddings</a></li>
        </ul>
      </div>

      <div>
        <h4 style="color:#fff">Support</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Contact Us</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Fares</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">FAQ</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Lost &amp; Found</a></li>
        </ul>
      </div>

      <div>
        <h4 style="color:#fff">Legal</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Privacy Policy</a></li>
          <li><a href="#" style="color:var(--text-muted); text-decoration:none;">Terms of Service</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom" style="max-width:1200px; margin:0 auto; border-top:1px solid #1f3638; padding:20px; display:flex; justify-content:space-between; align-items:center; color:#555;">
      <p style="margin:0;">&copy; 2025 NOIR Rides. All rights reserved.</p>
      <div class="d-flex gap-2 fw-bold text-secondary">
        <span>VISA</span><span>MC</span><span>AMEX</span>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /* ======= CONSTANTS ======== */
    const AIRPORTS = [
      { value: 'logan', label: 'Logan Airport (BOS)' },
      { value: 'jfk', label: 'JFK Airport (NY)' },
      { value: 'lax', label: 'Los Angeles (LAX)' },
    ];

    /* ======= DOM HELPERS ======= */
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => Array.from(document.querySelectorAll(sel));

    /* ======= ELEMENT REFS ======= */
    const menuToggle = $('#menuToggle');
    const mobileNav = $('#mobileNav');
    const timeSelect = $('#time');
    const dateInput = $('#date');
    const messageBox = $('#messageBox');
    const reservationForm = $('#reservationForm');

    /* ======= MENU TOGGLE ======= */
    menuToggle.addEventListener('click', () => {
      const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', String(!expanded));
      mobileNav.classList.toggle('active');
      mobileNav.setAttribute('aria-hidden', String(!mobileNav.classList.contains('active')));
    });

    // hide mobile nav when a link is clicked
    $$('.mobile-nav-links a').forEach(a => a.addEventListener('click', () => {
      mobileNav.classList.remove('active');
      menuToggle.setAttribute('aria-expanded','false');
    }));

    /* ======= TIME OPTIONS ======= */
    function generateTimeOptions(stepMinutes = 15) {
      if (!timeSelect) return;
      // start with placeholder
      timeSelect.innerHTML = '<option value="">Select Time</option>';
      for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += stepMinutes) {
          const hh = String(h).padStart(2,'0');
          const mm = String(m).padStart(2,'0');
          const val = `${hh}:${mm}`;
          const ampm = h < 12 ? 'AM' : 'PM';
          const dh = h % 12 || 12;
          const display = `${dh}:${mm} ${ampm}`;
          const opt = document.createElement('option');
          opt.value = val;
          opt.textContent = display;
          timeSelect.appendChild(opt);
        }
      }
    }

    /* ======= DATE MINIMUM ======= */
    function setMinDate() {
      if (!dateInput) return;
      const today = new Date();
      const tzOffset = today.getTimezoneOffset() * 60000;
      const localISO = (new Date(today - tzOffset)).toISOString().split('T')[0];
      dateInput.min = localISO;
      if (!dateInput.value) dateInput.value = localISO;
    }

    /* ======= ADDRESS FIELDS (DYNAMIC) ======= */
    const fromLocation = $('#fromLocation');
    const toLocation = $('#toLocation');
    const fromLabel = $('#fromLabel');
    const toLabel = $('#toLabel');

    function buildAddressInput(id, placeholder = 'Street Address, City, Zip') {
      const wrapper = document.createElement('div');
      wrapper.className = 'position-relative';
      const input = document.createElement('input');
      input.type = 'text';
      input.id = id;
      input.className = 'form-control';
      input.placeholder = placeholder;
      input.required = true;
      wrapper.appendChild(input);
      return wrapper;
    }

    function buildAirportSelect(id) {
      const wrapper = document.createElement('div');
      wrapper.className = 'position-relative';
      const select = document.createElement('select');
      select.id = id;
      select.className = 'form-select dynamic-select';
      AIRPORTS.forEach(a => {
        const opt = document.createElement('option');
        opt.value = a.value;
        opt.textContent = a.label;
        select.appendChild(opt);
      });
      wrapper.appendChild(select);
      const icon = document.createElement('span');
      icon.className = 'input-icon';
      icon.textContent = '✈️';
      wrapper.appendChild(icon);
      return wrapper;
    }

    function updateAddressFields() {
      const selected = document.querySelector('input[name="tripType"]:checked');
      const type = selected ? selected.value : 'toAirport'; // default
      // clear
      fromLocation.innerHTML = '';
      toLocation.innerHTML = '';

      if (type === 'toAirport') {
        fromLabel.textContent = 'Pickup Address';
        toLabel.textContent = 'Dropoff Airport';
        fromLocation.appendChild(buildAddressInput('pickupAddr', 'Enter pickup address'));
        toLocation.appendChild(buildAirportSelect('dropoffAirport'));
      } else if (type === 'fromAirport') {
        fromLabel.textContent = 'Pickup Airport';
        toLabel.textContent = 'Dropoff Address';
        fromLocation.appendChild(buildAirportSelect('pickupAirport'));
        toLocation.appendChild(buildAddressInput('dropoffAddr', 'Enter dropoff address'));
      } else {
        fromLabel.textContent = 'Pickup Address';
        toLabel.textContent = 'Dropoff Address';
        fromLocation.appendChild(buildAddressInput('pickupAddr', 'Enter pickup address'));
        toLocation.appendChild(buildAddressInput('dropoffAddr', 'Enter dropoff address'));
      }
    }

    /* attach trip radio listeners */
    $$('.custom-radio-input').forEach(r => r.addEventListener('change', updateAddressFields));

    /* ======= EXTRAS CALC ======= */
    const extraIds = ['stopover','infantSeat','boosterSeat'];
    function calcTotalFare() {
      extraIds.forEach(id => {
        const el = document.getElementById(id);
        const display = document.getElementById(id + 'Total');
        if (!el || !display) return;
        const qty = parseInt(el.value || '0', 10);
        const price = parseInt(el.getAttribute('data-price') || '0', 10);
        display.textContent = '$' + (qty * price);
      });
    }

    extraIds.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('change', calcTotalFare);
    });

    /* ======= FORM VALIDATION & SUBMIT ======= */
    function showMessage(text, type = 'success') {
      messageBox.classList.remove('d-none','alert-success','alert-danger');
      messageBox.classList.add('alert','mt-4');
      messageBox.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
      messageBox.textContent = text;
      messageBox.scrollIntoView({behavior:'smooth', block:'center'});
    }

    reservationForm.addEventListener('submit', (ev) => {
      ev.preventDefault();
      // basic validation
      const dateVal = dateInput.value;
      const timeVal = timeSelect.value;
      const pickup = fromLocation.querySelector('input, select');
      const dropoff = toLocation.querySelector('input, select');

      if (!dateVal || !timeVal) {
        showMessage('Please select both date and time.', 'error');
        return;
      }
      if (!pickup || !pickup.value) {
        showMessage('Please complete the pickup field.', 'error');
        return;
      }
      if (!dropoff || !dropoff.value) {
        showMessage('Please complete the dropoff field.', 'error');
        return;
      }

      // success → here you could call your backend API
      showMessage('Validation successful. Calculating fare...', 'success');

      // example: calculate extras total
      let extrasTotal = 0;
      extraIds.forEach(id => { extrasTotal += (parseInt(document.getElementById(id).value || '0', 10) * parseInt(document.getElementById(id).getAttribute('data-price') || '0', 10)); });

      // build simple payload (for demonstration)
      const payload = {
        date: dateVal,
        time: timeVal,
        tripType: document.querySelector('input[name="tripType"]:checked')?.value || null,
        pickup: pickup.value,
        dropoff: dropoff.value,
        adults: $('#adults').value,
        children: $('#children').value,
        luggage: $('#luggage').value,
        childSeats: $('#childSeats').value,
        extrasTotal
      };

      // Simulate API call delay (replace with real fetch)
      setTimeout(() => {
        showMessage('Fare calculated: $' + (10 + extrasTotal) + ' — proceeding to confirmation.', 'success');
        // TODO: redirect to payment or next step
      }, 700);
    });

    /* ======= INIT ======= */
    window.addEventListener('DOMContentLoaded', () => {
      // default select "toAirport"
      const toAirportRadio = $('#toAirport');
      if (toAirportRadio) toAirportRadio.checked = true;

      generateTimeOptions();
      setMinDate();
      updateAddressFields();
      calcTotalFare();
    });

    /* accessibility: close mobile nav on Esc */
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
        mobileNav.classList.remove('active');
        menuToggle.setAttribute('aria-expanded','false');
      }
    });
  </script>
</body>
</html>
