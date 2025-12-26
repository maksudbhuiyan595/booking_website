<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .hero-section {
        margin-top: 25px;
    }
    .flatpickr-input {
        background-color: white !important;
    }
     #date::placeholder {
        color: #333;
        opacity: 1;
    }
</style>

<section class="hero-section">
    <div class="container">
        <h1 class="main-title d-flex justify-content-center">Boston Logan Airport Taxi</h1>
        <div class="row hero-row">
            <div class="col-lg-5 form-column mb-4 mb-lg-0">
                <div class="reservation-card" id="floatingCard">
                    <div class="form-header">
                        <h3>Booking Reservation</h3>
                        <p>Instant Reservation EMAIL Confirmation</p>
                    </div>
                    <form id="reservationForm" action="{{ route('step2') }}" method="GET" novalidate>
                        <input type="hidden" name="extras_total" id="extrasTotalInput" value="0">

                        <div class="row g-1 mb-1">
                            {{-- DATE FIELD WITH ICON --}}
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input
                                        type="text"
                                        id="date"
                                        name="date"
                                        class="form-control flatpickr-input"
                                        placeholder="Date"
                                        readonly="readonly"
                                        required
                                    >
                                </div>
                            </div>

                            {{-- TIME FIELD WITH ICON --}}
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <select id="time" name="time" class="form-select" required>
                                        <option value="">Time</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="trip-type-container">
                            <div class="trip-option">
                                <input type="radio" name="tripType" id="type_from" value="fromAirport" checked>
                                <label class="trip-card" for="type_from"><i class="fas fa-plane-arrival"></i><span>From Airport</span></label>
                            </div>
                            <div class="trip-option">
                                <input type="radio" name="tripType" id="type_to" value="toAirport">
                                <label class="trip-card" for="type_to"><i class="fas fa-plane-departure"></i><span>To Airport</span></label>
                            </div>
                            <div class="trip-option">
                                <input type="radio" name="tripType" id="type_ptp" value="doorToDoor">
                                <label class="trip-card" for="type_ptp"><i class="fas fa-map-marker-alt"></i><span>Door-to-Door</span></label>
                            </div>
                        </div>

                        <div class="mb-1" id="fromLocation"></div>
                        <div class="mb-1" id="toLocation"></div>

                        <div class="row g-1 mt-1">
                            <div class="col-6">
                                <span class="mini-label">Adults (8+)</span>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <select name="adults" id="adults" class="form-select" required>
                                        <option value="">Select</option>
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <span class="mini-label">Children (<7)</span>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-child"></i></span>
                                    <select name="children" id="children" class="form-select">
                                        <option value="">Select</option>
                                        @for ($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-1 mt-1">
                            <div class="col-6">
                                <span class="mini-label">Luggage</span>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-suitcase"></i></span>
                                    <select name="luggage" id="luggage" class="form-select" required>
                                        <option value="">Select</option>
                                        @for ($i = 0; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <span class="mini-label">Child Seats</span>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-chair"></i></span>
                                    <select name="seats_dummy" id="childSeatsTrigger" class="form-select">
                                        <option value="0">Select</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div class="extras-toggle" id="toggleExtrasBtn">
                                <i class="fas fa-plus-circle"></i> Add Stops & Specific Seats
                            </div>
                            <div id="extrasSection">
                                <div class="extra-row">
                                    <div class="extra-info">
                                        <div class="extra-label">Stopover/Pets <span class="extra-price-tag">{{ $settings->stopover_fee ?? 0 }}</span></div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <select id="stopover" name="stopover" data-price="{{ $settings->stopover_fee ?? 0 }}" class="extra-select-box">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                        <div id="stopoverDisplay" class="total-price-display">$0</div>
                                    </div>
                                </div>
                                <div class="extra-row">
                                    <div class="extra-info">
                                        <div class="extra-label">Infant Seat <span class="extra-price-tag">{{ $settings->child_seat_fee ?? 0 }}</span></div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <select id="infantSeat" name="infant_seat" data-price="{{ $settings->child_seat_fee ?? 0 }}" class="extra-select-box">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                        <div id="infantSeatDisplay" class="total-price-display">$0</div>
                                    </div>
                                </div>
                                <div class="extra-row">
                                    <div class="extra-info">
                                        <div class="extra-label">Front Facing <span class="extra-price-tag">{{ $settings->regular_Seat_rules ?? 0 }}</span></div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <select id="frontSeat" name="front_seat" data-price="{{ $settings->regular_Seat_rules ?? 0 }}" class="extra-select-box">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                        <div id="frontSeatDisplay" class="total-price-display">$0</div>
                                    </div>
                                </div>
                                <div class="extra-row">
                                    <div class="extra-info">
                                        <div class="extra-label">Booster Seat <span class="extra-price-tag">{{ $settings->booster_seat_fee ?? 0 }}</span></div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <select id="boosterSeat" name="booster_seat" data-price="{{ $settings->booster_seat_fee ?? 0 }}" class="extra-select-box">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                        <div id="boosterSeatDisplay" class="total-price-display">$0</div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-get-fare">Get Fare & Reserve</button>
                            <p class="footer-note">Pay only $1 to confirm. Balance payable after service. 10% cash discount.</p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="description-column">
                    <p class="description-text">
                        One stop solutions for Boston Airport Transfer, Logan Ground Transportation, City Rides, Long Distance Car Services from/to Boston, Door to Door transfers and Chauffeured Cars for special occasions.
                    </p>
                    <div class="hero-img-wrapper">
                        <img src="{{ asset('images/car.jpg') }}" alt="Lexus" class="hero-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8jlhc5ZRDUU1SHHpxuwFh4dM0Ggq4n2Q&libraries=places&loading=async&callback=initMap"
    async
    defer>
</script>

<script>
    // 1. Google Maps Callback Logic
    let mapInitialized = false;

    function initMap() {
        console.log("Google Maps API Loaded");
        mapInitialized = true;
        // Re-trigger trip update to attach autocomplete if needed
        if(typeof window.updateTrip === 'function') {
            window.updateTrip();
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        console.log("Document ready");

        // ==========================================
        // 2. FLAT PICKER SETUP (Fixes Mobile "Set" Button)
        // ==========================================
        flatpickr("#date", {
            minDate: "today",       // Can't select past dates
            dateFormat: "Y-m-d",    // Format sent to server
            disableMobile: true,    // CRITICAL: Disables native mobile picker
            theme: "light",
            allowInput: true
        });

        // ==========================================
        // 3. Dynamic Luggage Capacity via AJAX
        // ==========================================
        const adultsSelect = document.getElementById('adults');
        const childrenSelect = document.getElementById('children');
        const luggageSelect = document.getElementById('luggage');

        function updateLuggageOption() {
            const valAdults = parseInt(adultsSelect.value) || 0;
            const valChildren = parseInt(childrenSelect.value) || 0;
            const totalPax = valAdults + valChildren;

            if (totalPax === 0) return;

            $.ajax({
                url: "/capacity-luggage",
                type: "GET",
                data: { passenger: totalPax },
                dataType: "json",
                success: function(response) {
                    const maxLuggage = (response && response.capacity_luggage) ? parseInt(response.capacity_luggage) : 12;
                    let html = '<option value="">Select</option>';
                    for (let i = 0; i <= maxLuggage; i++) {
                        html += `<option value="${i}">${i}</option>`;
                    }
                    luggageSelect.innerHTML = html;
                },
                error: function(xhr, status, error) {
                    let html = '<option value="">Select</option>';
                    for (let i = 0; i <= 12; i++) { html += `<option value="${i}">${i}</option>`; }
                    luggageSelect.innerHTML = html;
                }
            });
        }

        if (adultsSelect) adultsSelect.addEventListener('change', updateLuggageOption);
        if (childrenSelect) childrenSelect.addEventListener('change', updateLuggageOption);


        // ==========================================
        // 4. Trip Type, Airports & Autocomplete Logic
        // ==========================================
        const fromLoc = document.getElementById("fromLocation");
        const toLoc = document.getElementById("toLocation");
        let airports = [];

        // Load airports
        $.ajax({
            url: "/airports",
            type: "GET",
            dataType: "json",
            success: function(data) {
                airports = data;
                window.updateTrip(); // Initialize fields
            },
            error: function(xhr, status, error) {
                console.error("Failed to load airports:", error);
            }
        });

        function buildAirportSelect(name) {
            let html = `<select name="${name}" class="form-select" required><option value="">Select Airport</option>`;
            const targetAirport = "Boston Logan International Airport"; // Default select

            if(Array.isArray(airports)){
                airports.forEach(airport => {
                    let isSelected = (airport.name.trim() === targetAirport) ? "selected" : "";
                    html += `<option value="${airport.id}" data-address="${airport.address}" ${isSelected}>${airport.name}</option>`;
                });
            }
            html += `</select>`;
            return html;
        }

        function initAutocomplete(id) {
            if (!mapInitialized || !window.google || !window.google.maps || !window.google.maps.places) {
                setTimeout(() => initAutocomplete(id), 500);
                return;
            }
            const input = document.getElementById(id);
            if (!input) return;
            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ["geocode"],
                componentRestrictions: { country: "us" }
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === "Enter") e.preventDefault();
            });
        }

        window.updateTrip = function() {
            const tEl = document.querySelector('input[name="tripType"]:checked');
            if(!tEl) return;
            const t = tEl.value;

            if (t === 'fromAirport') {
                fromLoc.innerHTML = buildAirportSelect("from_airport");
                toLoc.innerHTML = `<input type="text" name="to_address" id="toAddress" class="form-control" placeholder="Drop Off Address" required>`;
                initAutocomplete("toAddress");
                attachAirportSelectEvent("from_airport", "fromAddress");
            } else if (t === 'toAirport') {
                fromLoc.innerHTML = `<input type="text" name="from_address" id="fromAddress" class="form-control" placeholder="Pick Up Address" required>`;
                toLoc.innerHTML = buildAirportSelect("to_airport");
                initAutocomplete("fromAddress");
                attachAirportSelectEvent("to_airport", "toAddress");
            } else { // Door to Door
                fromLoc.innerHTML = `<input type="text" name="from_address" id="fromAddress" class="form-control" placeholder="Pick Up Address" required>`;
                toLoc.innerHTML = `<input type="text" name="to_address" id="toAddress" class="form-control" placeholder="Drop Off Address" required>`;
                initAutocomplete("fromAddress");
                initAutocomplete("toAddress");
            }
        }

        function attachAirportSelectEvent(selectName, inputId) {
            const sel = document.querySelector(`select[name="${selectName}"]`);
            if (!sel) return;

            const updateHiddenAddress = () => {
                const selectedOption = sel.options[sel.selectedIndex];
                if (!selectedOption || !selectedOption.value) return;
                const address = selectedOption.getAttribute("data-address") || "";

                let input = document.getElementById(inputId);
                if (!input) {
                    input = document.createElement("input");
                    input.type = "hidden";
                    input.id = inputId;
                    input.name = inputId;
                    sel.parentElement.appendChild(input);
                }
                input.value = address;
            };

            sel.addEventListener("change", updateHiddenAddress);
            if (sel.value) {
                updateHiddenAddress();
            }
        }

        document.querySelectorAll('input[name="tripType"]').forEach(r => r.addEventListener('change', window.updateTrip));


        // ==========================================
        // 5. Time Setup
        // ==========================================
        const timeSelect = document.getElementById("time");
        for (let h = 0; h < 24; h++) {
            for (let m = 0; m < 60; m += 15) {
                let hh = String(h).padStart(2, '0');
                let mmStr = String(m).padStart(2, '0');
                let ampm = h < 12 ? 'AM' : 'PM';
                let dH = h % 12 || 12;
                timeSelect.innerHTML += `<option value="${hh}:${mmStr}">${dH}:${mmStr} ${ampm}</option>`;
            }
        }

        // ==========================================
        // 6. Extras & Pricing Logic
        // ==========================================
        const toggleBtn = document.getElementById("toggleExtrasBtn");
        const section = document.getElementById("extrasSection");

        if (toggleBtn) {
            toggleBtn.addEventListener("click", () => {
                section.classList.toggle("open");
                toggleBtn.classList.toggle("active");
            });
        }

        const childTrigger = document.getElementById("childSeatsTrigger");
        if (childTrigger) {
            childTrigger.addEventListener("change", function() {
                if (this.value !== "0") {
                    if (!section.classList.contains("open")) {
                        section.classList.add("open");
                        toggleBtn.classList.add("active");
                    }
                }
            });
        }

        const items = [
            { id: 'stopover', price: {{ $settings->stopover_fee ?? 0 }} },
            { id: 'infantSeat', price: {{ $settings->child_seat_fee ?? 0 }} },
            { id: 'frontSeat', price: {{ $settings->regular_Seat_rules ?? 0 }} },
            { id: 'boosterSeat', price: {{ $settings->booster_seat_fee ?? 0 }} }
        ];

        items.forEach(item => {
            const el = document.getElementById(item.id);
            if (el) {
                el.addEventListener("change", () => {
                    const total = el.value * item.price;
                    const displayEl = document.getElementById(item.id + "Display");
                    if (displayEl) displayEl.innerText = "$" + total;
                    calculateTotalExtras();
                });
            }
        });

        function calculateTotalExtras() {
            let total = 0;
            items.forEach(item => {
                const el = document.getElementById(item.id);
                if (el) total += (parseInt(el.value) || 0) * item.price;
            });
            document.getElementById("extrasTotalInput").value = total;
        }

        // ==========================================
        // 7. FINAL VALIDATION LOGIC
        // ==========================================
        const form = document.getElementById("reservationForm");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            let missing = false;
            const requiredFields = ["date", "time", "adults", "luggage"];
            requiredFields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el || !el.value) missing = true;
            });

            const fromVal = document.querySelector('[name="from_airport"]')?.value || document.querySelector('[name="from_address"]')?.value;
            const toVal = document.querySelector('[name="to_airport"]')?.value || document.querySelector('[name="to_address"]')?.value;

            if (!fromVal || !toVal) missing = true;

            if (missing) {
                Swal.fire({ icon: 'warning', title: 'Missing Details', text: 'Please fill in all required fields.', confirmButtonColor: '#d33' });
                return;
            }

            // Max Passenger
            const valAdults = parseInt(adultsSelect.value) || 0;
            const valChildren = parseInt(childrenSelect.value) || 0;
            const totalPax = valAdults + valChildren;

            if (totalPax > 14) {
                 Swal.fire({ icon: 'warning', title: 'Capacity Exceeded', html: `Total passengers cannot exceed 14.`, confirmButtonColor: '#d33' });
                return;
            }

            // Extras Count
            const requiredSeats = parseInt(childTrigger.value) || 0;
            const vStopover = parseInt(document.getElementById("stopover").value) || 0;
            const vInfant = parseInt(document.getElementById("infantSeat").value) || 0;
            const vFront = parseInt(document.getElementById("frontSeat").value) || 0;
            const vBooster = parseInt(document.getElementById("boosterSeat").value) || 0;
            const totalSelectedExtras = vStopover + vInfant + vFront + vBooster;

            if (requiredSeats > 0 && requiredSeats !== totalSelectedExtras) {
                 Swal.fire({ icon: 'error', title: 'Selection Mismatch', html: `Selected Child Seats (${requiredSeats}) does not match specific seats below.`, confirmButtonColor: '#d33' });
                return;
            }

            // Time Validation
            const dateVal = document.getElementById("date").value;
            const timeVal = document.getElementById("time").value;
            const selectedDateTime = new Date(dateVal + "T" + timeVal);
            const now = new Date();

            // 1. Past Date/Time Check
            if (selectedDateTime < now) {
                Swal.fire({ icon: 'error', title: 'Invalid Time', text: 'You cannot select a past date or time.', confirmButtonColor: '#d33' });
                return;
            }

            // 2. 2-Hour Notice Check
            const minBookingTime = new Date(now.getTime() + 2 * 60 * 60 * 1000);
            if (selectedDateTime < minBookingTime) {
                Swal.fire({ icon: 'warning', title: 'Reservation Time Restriction', text: 'Reservations must be made at least 2 hours prior to departure. For last-minute bookings call +1 (857) 331-9544', confirmButtonColor: '#d33' });
                return;
            }

            // ============================================================
            // 3. NEW VALIDATION: BLOCK TODAY 10 PM TO TOMORROW 6 AM
            // ============================================================

            // আজকের রাত ১০টা (Today 10:00 PM)
            let restrictedStart = new Date();
            restrictedStart.setHours(22, 0, 0, 0);

            // আগামীকাল ভোর ৬টা (Tomorrow 06:00 AM)
            let restrictedEnd = new Date();
            restrictedEnd.setDate(restrictedEnd.getDate() + 1); // ১ দিন যোগ করলাম
            restrictedEnd.setHours(6, 0, 0, 0);

            // চেক: যদি সিলেক্ট করা সময় আজকের রাত ১০টা থেকে কাল ভোর ৬টার মধ্যে হয়
            if (selectedDateTime >= restrictedStart && selectedDateTime <= restrictedEnd) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Service Offline',
                    text: 'Reservations cannot be processed between 10:00 PM and 6:00 AM. For urgent or same-day bookings, please contact us at +1 (857) 331-9544',
                    confirmButtonColor: '#d33'
                });
                return;
            }
            // ============================================================


            Swal.fire({
                title: 'Processing...',
                text: 'Checking availability',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false,
                willClose: () => {
                    form.submit();
                }
            });
        });
    });
</script>
