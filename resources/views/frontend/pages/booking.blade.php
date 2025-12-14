<style>
    .hero-section{
        margin-top: 25px;
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
                            <div class="col-6"><div class="input-group"><input type="date" id="date" name="date" class="form-control" required></div></div>
                            <div class="col-6"><div class="input-group"><select id="time" name="time" class="form-select" required><option value="">Time</option></select></div></div>
                        </div>

                        <div class="trip-type-container">
                            <div class="trip-option"><input type="radio" name="tripType" id="type_from" value="fromAirport" checked><label class="trip-card" for="type_from"><i class="fas fa-plane-arrival"></i><span>From Airport</span></label></div>
                            <div class="trip-option"><input type="radio" name="tripType" id="type_to" value="toAirport"><label class="trip-card" for="type_to"><i class="fas fa-plane-departure"></i><span>To Airport</span></label></div>
                            <div class="trip-option"><input type="radio" name="tripType" id="type_ptp" value="doorToDoor"><label class="trip-card" for="type_ptp"><i class="fas fa-map-marker-alt"></i><span>Door-to-Door</span></label></div>
                        </div>

                        <div class="mb-1" id="fromLocation"></div>
                        <div class="mb-1" id="toLocation"></div>

                        <div class="row g-1 mt-1">
                            <div class="col-6"><span class="mini-label">Adults (8+)</span><div class="input-group"><span class="input-group-text"><i class="fas fa-users"></i></span><select name="adults" class="form-select" required><option value="">Select</option>@for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor</select></div></div>
                            <div class="col-6"><span class="mini-label">Children (<7)</span><div class="input-group"><span class="input-group-text"><i class="fas fa-child"></i></span><select name="children" class="form-select"><option value="">Select</option>@for ($i = 1; $i <= 4; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor</select></div></div>
                        </div>

                        <div class="row g-1 mt-1">
                            <div class="col-6"><span class="mini-label">Luggage</span><div class="input-group"><span class="input-group-text"><i class="fas fa-suitcase"></i></span><select name="luggage" class="form-select" required><option value="">Select</option>@for ($i = 0; $i <= 12; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor</select></div></div>
                            <div class="col-6"><span class="mini-label">Child Seats</span><div class="input-group"><span class="input-group-text"><i class="fas fa-chair"></i></span><select name="seats_dummy" id="childSeatsTrigger" class="form-select"><option value="0">Select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></div></div>
                        </div>

                        <div class="mt-2">
                            <div class="extras-toggle" id="toggleExtrasBtn"><i class="fas fa-plus-circle"></i> Add Stops & Specific Seats</div>
                            <div id="extrasSection">
                                <div class="extra-row"><div class="extra-info"><div class="extra-label">Stopover/Pets <span class="extra-price-tag">$10</span></div></div><div class="d-flex align-items-center"><select id="stopover" name="stopover" data-price="10" class="extra-select-box"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><div id="stopoverDisplay" class="total-price-display">$0</div></div></div>
                                <div class="extra-row"><div class="extra-info"><div class="extra-label">Infant Seat <span class="extra-price-tag">$10</span></div></div><div class="d-flex align-items-center"><select id="infantSeat" name="infant_seat" data-price="10" class="extra-select-box"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><div id="infantSeatDisplay" class="total-price-display">$0</div></div></div>
                                <div class="extra-row"><div class="extra-info"><div class="extra-label">Front Facing <span class="extra-price-tag">$10</span></div></div><div class="d-flex align-items-center"><select id="frontSeat" name="front_seat" data-price="10" class="extra-select-box"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><div id="frontSeatDisplay" class="total-price-display">$0</div></div></div>
                                <div class="extra-row"><div class="extra-info"><div class="extra-label">Booster Seat <span class="extra-price-tag">$5</span></div></div><div class="d-flex align-items-center"><select id="boosterSeat" name="booster_seat" data-price="5" class="extra-select-box"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><div id="boosterSeatDisplay" class="total-price-display">$0</div></div></div>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAocOxnI1fKkcZPIDH-ir2iw8y2kBqk-H4&libraries=places"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    console.log("Document ready");

    const fromLoc = document.getElementById("fromLocation");
    const toLoc = document.getElementById("toLocation");

    // ---------------------------
    // 1. Load airports via AJAX
    // ---------------------------
    let airports = [];
    function loadAirports(callback) {
        // console.log("Fetching airports...");
        $.ajax({
            url: "/airports", // আপনার রাউট অনুযায়ী এটি ঠিক আছে কিনা চেক করবেন
            type: "GET",
            dataType: "json",
            success: function(data) {
                airports = data;
                callback(data);
            },
            error: function(xhr, status, error) {
                console.error("Failed to load airports:", error);
                callback([]);
            }
        });
    }

    // ---------------------------
    // 2. Helper: Build airport dropdown HTML
    // ---------------------------
    function buildAirportSelect(name) {
        let html = `<select name="${name}" class="form-select" required><option value="">Select Airport</option>`;
        airports.forEach(airport => {
            html += `<option value="${airport.id}" data-address="${airport.address}">${airport.name}</option>`;
        });
        html += `</select>`;
        return html;
    }

    // ---------------------------
    // 3. Helper: Google Maps Autocomplete
    // ---------------------------
    function initAutocomplete(id) {
        if (!document.getElementById(id)) return;
        const input = document.getElementById(id);
        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ["geocode"],
            componentRestrictions: { country: "us" }
        });
    }

    // ---------------------------
    // 4. Trip Type Logic (From/To/Door-to-Door)
    // ---------------------------
    function updateTrip() {
        const t = document.querySelector('input[name="tripType"]:checked').value;

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

    // ---------------------------
    // 5. Airport select -> hidden address fill
    // ---------------------------
    function attachAirportSelectEvent(selectName, inputId) {
        const sel = document.querySelector(`select[name="${selectName}"]`);
        if (!sel) return;
        sel.addEventListener("change", () => {
            const selectedOption = sel.options[sel.selectedIndex];
            const address = selectedOption.getAttribute("data-address") || "";

            // Hidden input তৈরি বা আপডেট করা
            if (!document.getElementById(inputId)) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.id = inputId;
                input.name = inputId; // backend এ address পাওয়ার জন্য name জরুরি
                input.value = address;
                sel.parentElement.appendChild(input);
            } else {
                document.getElementById(inputId).value = address;
            }
        });
    }

    // ---------------------------
    // 6. Initialize Logic
    // ---------------------------
    loadAirports(() => {
        updateTrip(); // প্রথমে একবার রান হবে
        document.querySelectorAll('input[name="tripType"]').forEach(r => r.addEventListener('change', updateTrip));
    });

    // ---------------------------
    // 7. Date & Time Setup
    // ---------------------------
    const dateInput = document.getElementById("date");
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    dateInput.min = `${yyyy}-${mm}-${dd}`; // পেছনের তারিখ ব্লক

    const timeSelect = document.getElementById("time");
    // 24 ঘন্টার লুপ
    for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += 15) {
            let hh = String(h).padStart(2, '0');
            let mmStr = String(m).padStart(2, '0');
            let ampm = h < 12 ? 'AM' : 'PM';
            let dH = h % 12 || 12;
            timeSelect.innerHTML += `<option value="${hh}:${mmStr}">${dH}:${mmStr} ${ampm}</option>`;
        }
    }

    // ---------------------------
    // 8. Extras & Pricing Logic
    // ---------------------------
    const toggleBtn = document.getElementById("toggleExtrasBtn");
    const section = document.getElementById("extrasSection");

    if(toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            section.classList.toggle("open");
            toggleBtn.classList.toggle("active");
        });
    }

    const items = [
        { id: 'stopover', price: 10 },
        { id: 'infantSeat', price: 10 },
        { id: 'frontSeat', price: 10 },
        { id: 'boosterSeat', price: 5 }
    ];

    // টোটাল ক্যালকুলেশন এবং ডিসপ্লে
    items.forEach(item => {
        const el = document.getElementById(item.id);
        if(el) {
            el.addEventListener("change", () => {
                const total = el.value * item.price;
                const displayEl = document.getElementById(item.id + "Display");
                if(displayEl) displayEl.innerText = "$" + total;

                // আপনি চাইলে এখানে টোটাল extras যোগ করে hidden field এ রাখতে পারেন
                calculateTotalExtras();
            });
        }
    });

    function calculateTotalExtras() {
        let total = 0;
        items.forEach(item => {
            const el = document.getElementById(item.id);
            if(el) total += (parseInt(el.value) || 0) * item.price;
        });
        document.getElementById("extrasTotalInput").value = total;
    }

    // ==========================================
    // 9. FINAL VALIDATION LOGIC
    // ==========================================
    const form = document.getElementById("reservationForm");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        // --- A. Missing Fields Check ---
        let missing = false;
        const requiredFields = ["date", "time", "adults", "luggage"];

        requiredFields.forEach(name => {
            const el = document.querySelector(`[name="${name}"]`);
            if (!el || !el.value) missing = true;
        });

        // ডায়নামিক এড্রেস ফিল্ড চেক
        const fromVal = document.querySelector('[name="from_airport"]')?.value || document.querySelector('[name="from_address"]')?.value;
        const toVal = document.querySelector('[name="to_airport"]')?.value || document.querySelector('[name="to_address"]')?.value;

        if (!fromVal || !toVal) missing = true;

        if (missing) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Details',
                text: 'Please fill in all required fields (Date, Time, Locations, Adult, Luggage).',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // --- B. Time Validation ---
        const dateVal = document.getElementById("date").value;
        const timeVal = document.getElementById("time").value;

        const selectedDateTime = new Date(dateVal + "T" + timeVal);
        const now = new Date();
        const selectedHour = selectedDateTime.getHours();

        // 1. Past Date Check (অতীতের সময় চেক)
        if (selectedDateTime < now) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Time',
                text: 'You cannot select a past date or time.',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // 2. Minimum 2 Hours Notice Check (২ ঘন্টার বাফার)
        const minBookingTime = new Date(now.getTime() + 2 * 60 * 60 * 1000);
        if (selectedDateTime < minBookingTime) {
            Swal.fire({
                icon: 'warning',
                title: 'Reservation Time Restriction',
                text: 'We cannot process a reservation within 2 Hours of departure. For urgent bookings please call: +18573319544',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // 3. Service Hours Validation (10 PM to 6 AM Closed)
        // এই লজিকটি শুধু "Booking Time" চেক করবে, ইউজার কখন ওয়েবসাইটে এসেছে তা দেখবে না।
        // if (selectedHour >= 22 || selectedHour < 6) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'Service Unavailable',
        //         text: 'Our service is closed from 10:00 PM to 6:00 AM. Please choose a time within our operating hours.',
        //         confirmButtonColor: '#f8bb86'
        //     });
        //     return;
        // }

        // --- C. Success ---
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

