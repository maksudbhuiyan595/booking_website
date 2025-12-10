<style>
    :root {
        --accent-color: #10b981;
        --light-accent: #34d399;
        --bg-dark: #111827;
        --card-bg: #1f2937;
        --input-border: #374151;
        --text-white: #ffffff;
    }

    /* --- SECTION LAYOUT --- */
    .reservation-section {
        background-color: var(--bg-dark);
        /* Mobile: Allow scrolling, auto height */
        min-height: auto;
        padding-top: 2rem;
        padding-bottom: 2rem;
        display: flex;
        align-items: center;
    }

    /* Desktop: Full height centering */
    @media (min-width: 992px) {
        .reservation-section {
            min-height: 100vh;
            padding-top: 0;
            padding-bottom: 0;
        }
    }

    .reservation-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    /* --- FORM INPUTS --- */
    .form-control, .form-select {
        background-color: #111827 !important;
        color: #f3f4f6 !important;
        border: 1px solid var(--input-border) !important;
        padding: 12px 15px;
        font-size: 16px; /* Prevents zoom on iPhone */
        border-radius: 8px;
    }

    .form-control::placeholder { color: #6b7280; }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15) !important;
        border-color: var(--accent-color) !important;
        outline: none;
    }

    /* --- ICONS --- */
    .position-relative { position: relative; }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #9ca3af;
        z-index: 5;
    }

    /* Fix for labeled inputs */
    .labeled-input-wrapper .input-icon {
        top: 38px; /* Precise adjustment for inputs with labels */
        transform: none;
    }

    .date-icon svg, .time-icon svg { width: 20px; height: 20px; }

    /* Date Input Specifics */
    input[type="date"] {
        padding-right: 40px !important;
        color-scheme: dark;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: 100%;
        z-index: 10;
    }

    /* --- CUSTOM RADIO --- */
    .custom-radio-input {
        appearance: none;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #6b7280;
        background: transparent;
        margin-right: 10px;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 3px;
    }
    .custom-radio-input:checked {
        border-color: var(--accent-color);
        background: var(--accent-color);
        box-shadow: 0 0 10px rgba(16,185,129,0.3);
    }

    .radio-card {
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 8px;
        padding: 10px;
        height: 100%;
        transition: 0.3s;
    }
    .radio-card:hover {
        background: rgba(255,255,255,0.02);
        border-color: rgba(255,255,255,0.1);
    }
    .radio-label { cursor: pointer; display: flex; align-items: flex-start; width: 100%; }

    /* --- EXTRAS & IMAGE --- */
    .extras-section { display: none; }
    .toggle-extras { cursor: pointer; user-select: none; }

    .hero-image-container {
        height: 100%;
        min-height: 550px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
    }
    .hero-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Mobile Banner Image */
    .mobile-banner {
        height: 150px;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        position: relative;
        margin-bottom: -10px; /* Overlap slightly */
        z-index: 0;
    }
    .mobile-banner img { width: 100%; height: 100%; object-fit: cover; }
</style>

<section class="reservation-section">
    <div class="container">

        <div class="text-center mb-4 d-lg-none text-white pt-4">
            <h1 class="h4 fw-bold">BOSTON AIRPORT CAR SERVICE</h1>
            <p class="text-secondary small">Premium Rides, Flat Rates</p>
        </div>

        <div class="row align-items-stretch justify-content-center">

            <div class="col-lg-6 mb-4 mb-lg-0">

                <div class="mobile-banner d-block d-lg-none">
                    <img src="{{ asset('images/car.jpg') }}" alt="Premium Car">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, var(--bg-dark));"></div>
                </div>

                <div class="p-1 border border-secondary border-opacity-25 rounded shadow-lg h-100 position-relative" style="z-index: 2; background: var(--bg-dark);">
                    <div class="p-3 p-md-5 reservation-card h-100 d-flex flex-column justify-content-center">

                        <header class="text-center mb-4">
                            <h2 class="fs-4 fw-bold text-white text-uppercase font-monospace" style="letter-spacing:2px">
                                Reserve Your Ride
                            </h2>
                            <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Online Exclusive</span>
                                <span class="small text-secondary">Instant Confirmation</span>
                            </div>
                        </header>

                        <form id="reservationForm" action="{{ route('step2') }}" method="GET" novalidate>
                            @csrf
                            <input type="hidden" name="extras_total" id="extrasTotalInput" value="0">

                            <div class="row g-3 mb-3">
                                <div class="col-6 labeled-input-wrapper position-relative">
                                    <label class="form-label small text-secondary fw-semibold text-uppercase mb-1" style="font-size: 0.7rem;">Date</label>
                                    <input type="date" id="date" name="date" required class="form-control" value="{{ old('date') }}" />
                                    <span class="input-icon date-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    </span>
                                </div>
                                <div class="col-6 labeled-input-wrapper position-relative">
                                    <label class="form-label small text-secondary fw-semibold text-uppercase mb-1" style="font-size: 0.7rem;">Time</label>
                                    <select id="time" name="time" required class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    <span class="input-icon time-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row g-2">
                                    <div class="col-12 col-sm-4">
                                        <div class="radio-card">
                                            <label class="radio-label">
                                                <input class="custom-radio-input" type="radio" name="tripType" value="fromAirport">
                                                <div>
                                                    <span class="d-block text-white small fw-bold">From Airport</span>
                                                    <span class="d-block text-secondary" style="font-size: 0.65rem;">Flight Tracking</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="radio-card">
                                            <label class="radio-label">
                                                <input class="custom-radio-input" type="radio" name="tripType" value="toAirport" checked>
                                                <div>
                                                    <span class="d-block text-white small fw-bold">To Airport</span>
                                                    <span class="d-block text-secondary" style="font-size: 0.65rem;">Timely Dropoff</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="radio-card">
                                            <label class="radio-label">
                                                <input class="custom-radio-input" type="radio" name="tripType" value="doorToDoor">
                                                <div>
                                                    <span class="d-block text-white small fw-bold">Point-to-Point</span>
                                                    <span class="d-block text-secondary" style="font-size: 0.65rem;">City Transfer</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label id="fromLabel" class="form-label small text-secondary fw-semibold text-uppercase ms-1 mb-1" style="font-size: 0.7rem;">Pickup</label>
                                <div id="fromLocation" class="mb-3 position-relative"></div>

                                <label id="toLabel" class="form-label small text-secondary fw-semibold text-uppercase ms-1 mb-1" style="font-size: 0.7rem;">Destination</label>
                                <div id="toLocation" class="position-relative"></div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3 labeled-input-wrapper position-relative">
                                    <label class="small text-secondary mb-1" style="font-size: 0.7rem;">Adults</label>
                                    <select id="adults" name="adults" class="form-select ps-2">
                                        @for($i=1; $i<=10; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 labeled-input-wrapper position-relative">
                                    <label class="small text-secondary mb-1" style="font-size: 0.7rem;">Children</label>
                                    <select id="children" name="children" class="form-select ps-2">
                                        @for($i=0; $i<=5; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 labeled-input-wrapper position-relative">
                                    <label class="small text-secondary mb-1" style="font-size: 0.7rem;">Luggage</label>
                                    <select id="luggage" name="luggage" class="form-select ps-2">
                                        @for($i=0; $i<=8; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 labeled-input-wrapper position-relative">
                                    <label class="small text-secondary mb-1" style="font-size: 0.7rem;">Seats</label>
                                    <select id="childSeats" name="childSeats" class="form-select ps-2">
                                        <option value="0">None</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="border-top border-secondary border-opacity-25 pt-3 mt-3">
                                <div class="d-flex align-items-center mb-2 toggle-extras">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2 text-white" style="width:24px; height:24px">
                                        <span class="transition-icon fw-bold" style="line-height:0; margin-top:-2px">+</span>
                                    </div>
                                    <h3 class="fs-6 fw-semibold text-white mb-0">Add Stops & Seats</h3>
                                </div>

                                <div id="extrasSection" class="extras-section ps-1 pe-1">
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                                        <div>
                                            <label class="small text-light fw-semibold">Stopover</label>
                                            <span class="small text-secondary d-block" style="font-size: 0.7rem;">$25 / stop</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <select id="stopover" name="stopover" data-price="25" class="form-select form-select-sm seat-select me-2" style="width: 60px;">
                                                <option value="0">0</option><option value="1">1</option><option value="2">2</option>
                                            </select>
                                            <span id="stopoverTotal" class="fw-bold text-success small text-end" style="width: 30px;">$0</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                                        <div>
                                            <label class="small text-light fw-semibold">Infant Seat</label>
                                            <span class="small text-secondary d-block" style="font-size: 0.7rem;">$10 (0-2 yrs)</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <select id="infantSeat" name="infant_seat" data-price="10" class="form-select form-select-sm seat-select me-2" style="width: 60px;">
                                                <option value="0">0</option><option value="1">1</option><option value="2">2</option>
                                            </select>
                                            <span id="infantSeatTotal" class="fw-bold text-success small text-end" style="width: 30px;">$0</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <div>
                                            <label class="small text-light fw-semibold">Booster Seat</label>
                                            <span class="small text-secondary d-block" style="font-size: 0.7rem;">$5 (5-7 yrs)</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <select id="boosterSeat" name="booster_seat" data-price="5" class="form-select form-select-sm seat-select me-2" style="width: 60px;">
                                                <option value="0">0</option><option value="1">1</option><option value="2">2</option>
                                            </select>
                                            <span id="boosterSeatTotal" class="fw-bold text-success small text-end" style="width: 30px;">$0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div id="messageBox" class="alert d-none text-center fw-medium p-2 small mb-3"></div>
                                <button type="submit" class="btn w-100 text-dark fw-bold text-uppercase py-3"
                                    style="background-color: var(--accent-color); box-shadow:0 4px 15px rgba(16,185,129,0.25); letter-spacing: 1px;">
                                    Get Price & Book
                                </button>
                                <p class="text-center small mt-3 text-secondary" style="font-size: 0.8rem;">
                                    <i class="fas fa-lock me-1"></i> Secure • <span class="text-success">10% Off</span> for cash
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ps-lg-5 d-none d-lg-block">
                <div class="hero-image-container">
                    <img src="{{ asset('images/car.jpg') }}" alt="Premium Luxury Car">
                    <div class="position-absolute bottom-0 start-0 w-100 p-5" style="background: linear-gradient(to top, rgba(0,0,0,0.95), transparent);">
                        <h1 class="text-white fw-bold display-5">Travel in Comfort</h1>
                        <p class="text-light opacity-75 lead">Experience the best airport transfer service in Boston. Flat rates, professional drivers, and luxury vehicles.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. CONFIGURATION ---
        const AIRPORTS = [
            { value: 'logan', label: 'Logan Airport (BOS)' },
            { value: 'jfk', label: 'JFK Airport (NY)' },
            { value: 'pvd', label: 'T.F. Green (PVD)' },
            { value: 'mht', label: 'Manchester (MHT)' }
        ];

        // --- 2. DOM ELEMENTS ---
        const dateInput = document.getElementById("date");
        const timeSelect = document.getElementById("time");
        const reservationForm = document.getElementById("reservationForm");
        const messageBox = document.getElementById("messageBox");

        // --- 3. INIT DATE & TIME ---
        const today = new Date().toISOString().split("T")[0];
        dateInput.min = today;
        if(!dateInput.value) dateInput.value = today;

        function generateTimeOptions() {
            timeSelect.innerHTML = '<option value="">Select Time</option>';
            for (let h = 0; h < 24; h++) {
                for (let m = 0; m < 60; m += 15) {
                    const hh = String(h).padStart(2, "0");
                    const mm = String(m).padStart(2, "0");
                    const period = h < 12 ? "AM" : "PM";
                    const displayH = h % 12 || 12;
                    const label = `${displayH}:${mm} ${period}`;
                    timeSelect.innerHTML += `<option value="${hh}:${mm}">${label}</option>`;
                }
            }
        }
        generateTimeOptions();

        // --- 4. DYNAMIC ADDRESS LOGIC ---
        const fromLocation = document.getElementById("fromLocation");
        const toLocation = document.getElementById("toLocation");
        const fromLabel = document.getElementById("fromLabel");
        const toLabel = document.getElementById("toLabel");

        function buildInput(id, name, placeholder) {
            return `
            <input type="text" id="${id}" name="${name}" class="form-control" placeholder="${placeholder}" required>
            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
            `;
        }

        function buildAirportSelect(id, name) {
            let options = AIRPORTS.map(a => `<option value="${a.value}">${a.label}</option>`).join("");
            return `
            <select id="${id}" name="${name}" class="form-select">${options}</select>
            <span class="input-icon"><i class="fas fa-plane"></i></span>
            `;
        }

        function updateAddressFields() {
            const type = document.querySelector('input[name="tripType"]:checked').value;

            if (type === "toAirport") {
                fromLabel.textContent = "Pickup Address";
                toLabel.textContent = "Dropoff Airport";
                fromLocation.innerHTML = buildInput("pickup_address", "pickup_address", "City, Zip, Street");
                toLocation.innerHTML = buildAirportSelect("dropoff_airport", "dropoff_airport");
            } else if (type === "fromAirport") {
                fromLabel.textContent = "Pickup Airport";
                toLabel.textContent = "Dropoff Address";
                fromLocation.innerHTML = buildAirportSelect("pickup_airport", "pickup_airport");
                toLocation.innerHTML = buildInput("dropoff_address", "dropoff_address", "City, Zip, Street");
            } else {
                fromLabel.textContent = "Pickup Address";
                toLabel.textContent = "Dropoff Address";
                fromLocation.innerHTML = buildInput("pickup_address", "pickup_address", "Enter Pickup");
                toLocation.innerHTML = buildInput("dropoff_address", "dropoff_address", "Enter Dropoff");
            }
        }

        document.querySelectorAll(".custom-radio-input").forEach(r => r.addEventListener("change", updateAddressFields));
        updateAddressFields();

        // --- 5. EXTRAS CALCULATION ---
        const extraIds = ["stopover", "infantSeat", "boosterSeat"];

        function calcExtras() {
            let total = 0;
            extraIds.forEach(id => {
                const el = document.getElementById(id);
                if(el) {
                    const price = parseInt(el.dataset.price);
                    const qty = parseInt(el.value);
                    const sub = price * qty;
                    total += sub;
                    document.getElementById(id + "Total").textContent = "$" + sub;
                }
            });
            document.getElementById("extrasTotalInput").value = total;
        }
        extraIds.forEach(id => document.getElementById(id)?.addEventListener("change", calcExtras));

        // Toggle extras section
        const toggleExtras = document.querySelector(".toggle-extras");
        const extrasSection = document.getElementById("extrasSection");
        const toggleIcon = toggleExtras.querySelector(".transition-icon");

        toggleExtras.addEventListener("click", () => {
            if (extrasSection.style.display === "none" || extrasSection.style.display === "") {
                extrasSection.style.display = "block";
                toggleIcon.textContent = "−";
            } else {
                extrasSection.style.display = "none";
                toggleIcon.textContent = "+";
            }
        });

        // --- 6. VALIDATION ---
        reservationForm.addEventListener("submit", function (e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                messageBox.classList.remove("d-none", "alert-success");
                messageBox.classList.add("alert", "alert-danger");
                messageBox.textContent = "Please fill in all required fields.";
                return;
            }
            if (!dateInput.value || !timeSelect.value) {
                e.preventDefault();
                messageBox.classList.remove("d-none");
                messageBox.classList.add("alert", "alert-danger");
                messageBox.textContent = "Please select Date & Time.";
                return;
            }
            messageBox.classList.remove("d-none", "alert-danger");
            messageBox.classList.add("alert", "alert-success");
            messageBox.textContent = "Calculating fare...";
        });
    });
</script>
