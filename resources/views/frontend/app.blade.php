<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if (isset($seo))
        <title>{{ $seo->meta_title }}</title>
        <meta name="description" content="{{ $seo->meta_description }}">
    @else
        <title>Boston Logan Airport Taxi</title>
        <meta name="description" content="Reliable airport taxi service in Boston.">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
    @include('frontend.css.style')
    <link rel="canonical" href="{{ url()->current() }}" />



</head>


<body>
    <div id="validationPopup" class="custom-popup">
        <div class="popup-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="popup-message" id="popupText">Message goes here</div>
    </div>

    @include('frontend.pages.nav')
    @include('frontend.pages.booking')
    @include('frontend.pages.rating')

    <section class="content-section bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <p>Planning a trip to or from Logan International Airport? You deserve a clean, safe, and reliable
                        ride, and that’s precisely what we provide. <strong>Boston Logan Airport Taxi</strong> is here
                        with 24/7 professional airport transportation with licensed drivers, sanitized vehicles, and
                        flat-rate pricing. Whether you are on a business trip, on vacation with your family, or need a
                        hassle-free airport pickup, we’ve created our service to suit your comfort and peace of mind. We
                        service all towns in Boston and guarantee on-time pickup every time. Just let us manage your
                        ride so you can focus on your flight and relaxation.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <img src="{{ asset('images/Boston Logaqn Aorport Taxi Service.webp') }}" alt="White Van"
                        class="img-fluid rounded mb-3 w-100 service-img">
                    <h3 class="section-title h4">Safe and Trusted Logan Airport Taxi Service Across Greater Boston</h3>
                    <p>We prioritize your well-being and safety. So, after every ride, we clean and disinfect every car.
                        We clean door handles and seat belts, sanitize the seats and floor, and make every effort to
                        keep anything that may affect both you and your loved ones clean. We keep both our drivers
                        clean, but our cars also feel fresh and enjoyable for today’s discerning passenger on every
                        ride. Your safety and well-being will never be overshadowed by entrepreneurship as you travel
                        for any reason in and around Greater Boston.</p>
                </div>

                <div class="col-lg-6">
                    <h3 class="section-title h4">Why Choose Boston Logan Airport Taxi?</h3>
                    <ul class="why-choose-list">
                        <li>Flat-Rate, No-Surge Pricing</li>
                        <li>Vehicles Cleaned & Sanitized after Every Ride</li>
                        <li><a href="#" style="color:var(--btn-green); font-weight:bold;">Child Seats</a>
                            Available</li>
                        <li>Available 24/7, Including on Holidays</li>
                        <li>Licensed, Background-Checked Drivers</li>
                        <li>Long-Distance & Event Rides Available</li>
                        <li>Live Flight Tracking & Real-Time Adjustments</li>
                    </ul>
                    <div class="mt-4 position-relative">
                        <img src="{{ asset('images/Sanitized Vehicles.webp') }}" alt="Sanitizing"
                            class="img-fluid rounded w-100 service-img">
                        <div class="bg-white px-3 py-1 position-absolute bottom-0 start-50 translate-middle-x mb-3 rounded shadow-sm fw-bold"
                            style="border: 1px solid #ddd;">
                            Sanitized Vehicles after every Ride
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h3 class="section-title">CHILDREN'S SEATING CAPABILITIES</h3>
                    <p>Safety comes first, especially with our littlest rides. We offer properly installed infant,
                        toddler, and booster seats, upon request, so your child’s safety can be ensured. Just tell us
                        what you need when booking, and we will take care of the rest to ensure all your family can ride
                        together.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                    <img src="{{ asset('images/Child Seat.webp') }}" alt="Child Seat"
                        class="img-fluid rounded service-img">
                </div>
                <div class="col-lg-4">
                    <h3 class="section-title">TRAVEL WITH FAMILY OR LUGGAGE</h3>
                    <p>Additional bags, or are you with elderly parents, or traveling with a group? Our spacious sedans
                        and vans make traveling with a group of colleagues, family members, etc, comfortable and
                        convenient. You’ll enjoy the ride, whether your destination is near or far, with door-to-door
                        service, friendly drivers, and in our spacious, cruise-worthy vehicles.</p>
                </div>
            </div>

            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="section-title">Reliable Airport Service</h3>
                    <p>We’re the go-to transportation service for prompt trips to and from Logan Airport, offering
                        service to neighborhoods and towns such as <a href="#"
                            style="color:var(--btn-green);">Boston</a>, Cambridge, Somerville, <a href="#"
                            style="color:var(--btn-green);">Hanscom AFB</a>, Lexington, Waltham, Brookline, Burlington,
                        Belmont, Arlington, Haverhill, Worcester, Methuen, Nashua, and many more. Our professional
                        drivers are only a stone’s throw away, regardless of where you are. We monitor all flight delays
                        in real-time, and your driver will be waiting for you on arrival at no extra cost. We promise
                        one thing: on time, every time.</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/Boston Logan Airport.webp') }}" alt="Airport"
                        class="img-fluid rounded w-100 service-img">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h3 class="section-title">Long-Distance & Special Event Transfers</h3>
                    <p>Need a ride beyond Boston? We have long-distance trips to <a href="#"
                            style="color:var(--btn-green);">Cape Cod</a>, New York, New Hampshire, Yarmouth MA, and
                        others. Whether it’s business travel, weddings, or events, our dependable service ensures
                        comfort, promptness, and peace of mind. Leave the driving to us, and let us worry about
                        directions.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Here's What Our Customers Say...</h2>
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p class="testimonial-text">"Reliable and clean taxi service. Got to Logan Airport with plenty
                            of time to spare. Very professional driver. Highly recommend!"</p>
                        <div class="customer-name">Jessica Morgan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p class="testimonial-text">"Smooth ride with the car seat set the right way. The vehicle was
                            spotless. Great service. I'll book again!"</p>
                        <div class="customer-name">Graham Bronson</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p class="testimonial-text">"The driver was very polite and helped with our luggage, and the
                            reservation was quite simple. Five stars from me."</p>
                        <div class="customer-name">Liam O'Connor</div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h3 class="fw-bold">Book Your Ride with Confidence</h3>
                    <p class="w-75 mx-auto">Whether you are booking ahead or need a ride at this time, <strong>Boston
                            Logan Airport Taxi</strong> is ready for you! Booking is quick and easy, either give us a
                        call or fill in our short reservation form. One of our employees will verify your booking,
                        allocate a clean and modern car for your transfer, and guarantee a punctual driver. There is
                        never any reason why traveling should be difficult, and with us, it isn’t. Book your ride today!
                    </p>
                </div>
            </div>

            <h3 class="text-center fw-bold mt-5">Popular Cities for Car Service in Boston Neighborhood</h3>
            <div class="city-grid">
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Provincetown</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> North Truro</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Falmouth MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Barnstable MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Eastham MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Salem</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Merrimack</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Derry NH</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Providence RI</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Amherst MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Portland ME</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Boston MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Manchester NH</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Wilmington MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Wakefield MA</a>
                <a href="#" class="city-tag"><i class="fas fa-taxi"></i> Burlington Ma</a>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('area.we.serve') }}" class="btn btn-warning fw-bold px-4 shadow">Show More</a>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Frequently Asked Questions (FAQ)</h2>
            <div class="accordion mb-5" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h1"><button class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#c1">How early in advance should I reserve a
                            taxi to Logan Airport?</button></h2>
                    <div id="c1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">We advise all guests to book at least 24 hours in advance to ensure
                            availability, although same-day rides might still be available.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h2"><button class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c2">Do you clean your vehicles
                            often?</button></h2>
                    <div id="c2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Yes. Every vehicle is thoroughly disinfected after every ride to
                            keep both you and other passengers safe and healthy.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h3"><button class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c3">Do you have child seats in
                            your car?</button></h2>
                    <div id="c3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Absolutely. We do have infant, toddler, and booster seats. Ask for
                            it when you book and we’ll have it ready.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h4"><button class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c4">What if my flight is
                            running late?</button></h2>
                    <div id="c4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">No worries! We check your flight in real time, so your pickup time
                            automatically adjusts, at no extra charge for reasonable delays.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h5"><button class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c5">Are there hidden charges in
                            your shipping prices?</button></h2>
                    <div id="c5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Never. We provide open-book pricing with flat-rate rates. No hidden
                            fees, no surprises.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h6"><button class="accordion-button collapsed"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c6">Do you cater for
                            long-distance trips or event transit?</button></h2>
                    <div id="c6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Yes. We offer rides to Cape Cod, New York, and beyond. We also
                            provide for events such as weddings, company travel, and family vacations. Just let us know
                            your needs.</div>
                    </div>
                </div>
            </div>

            <h2 class="text-center fw-bold mb-4">Latest Blog</h2>
            <div class="container">
                <div class="services-section">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="blog-card">
                                <div class="blog-img-container">
                                    <img src="{{ asset('images/blog.webp') }}" alt="Border Cafe">
                                </div>
                                <a href="#" class="blog-title">Explore Border Cafe in Burlington, MA 01803: A
                                    Local Gem</a>
                                <div class="blog-meta">admin /// September 20, 2025 /// No Comments</div>
                                <a href="#" class="read-more-btn">Read More »</a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="blog-card">
                                <div class="blog-img-container">
                                    <img src="{{ asset('images/blog.webp') }}" alt="Cary Memorial Library">
                                </div>
                                <a href="#" class="blog-title">Why Cary Memorial Library is a Must-Visit in
                                    Lexington</a>
                                <div class="blog-meta">admin /// September 18, 2025 /// No Comments</div>
                                <a href="#" class="read-more-btn">Read More »</a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="blog-card">
                                <div class="blog-img-container">
                                    <img src="{{ asset('images/blog.webp') }}" alt="Falmouth Hospital">
                                </div>
                                <a href="#" class="blog-title">Get to Falmouth Hospital, MA, Fast with Our Taxi
                                    Service</a>
                                <div class="blog-meta">admin /// August 25, 2025 /// No Comments</div>
                                <a href="#" class="read-more-btn">Read More »</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.pages.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script>
        const Notify = (type, message) => {
            Swal.fire({
                toast: true,
                position: 'top-center',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        };
    </script> --}}

    {{-- @if (session('notify'))
        <script>
            Notify("{{ session('notify.type') }}", "{{ session('notify.message') }}");
        </script>
    @endif --}}
    @if(session()->has('notify'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Notify(
                "{{ session('notify.type') }}",
                "{{ session('notify.message') }}"
            );
        });
    </script>
    @endif

    <script>
    const Notify = (type, message) => {
        Swal.fire({
            toast: true,
            position: 'top-center',
            icon: type,
            title: message,
            showConfirmButton: true,     // ✅ OK button show
            confirmButtonText: 'OK',
            timer: null,                 // ❌ auto close off
            timerProgressBar: false,
            allowOutsideClick: false,    // optional (click outside won't close)
        });
    };
</script>

    @if(session('notify'))
<div class="alert alert-{{ session('notify.type') }} alert-dismissible fade show" role="alert">
    {{ session('notify.message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


</body>

</html>
