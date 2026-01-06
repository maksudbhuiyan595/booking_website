<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="86x_Pxdx_MMID1zG3q322wIJHpeZOXtFCRYeghepuOc" />
    <link rel="canonical" href="{{ rtrim(request()->url(), '/') . '/' }}">

    @if (isset($seo))
        <title>{{ $seo->meta_title }}</title>
        <meta name="description" content="{{ $seo->meta_description }}">
    @else
        <title>Boston Logan Airport Taxi</title>
        <meta name="description" content="Reliable airport taxi service in Boston.">
    @endif
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap"
        rel="stylesheet">
    @include('frontend.css.style')
    @stack('schema')
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
                        <li><a href="{{ route('child.seat') }}" style="color:var(--btn-green); font-weight:bold;">Child
                                Seats</a> Available</li>
                        <li>Available 24/7, Including on Holidays</li>
                        <li>Licensed, Background-Checked Drivers</li>
                        <li>Long-Distance & Event Rides Available</li>
                        <li>Live Flight Tracking & Real-Time Adjustments</li>
                    </ul>
                    <div class="mt-4 position-relative">
                        <img src="{{ asset('images/Sanitized Vehicles.webp') }}" alt="Sanitizing"
                            class="img-fluid rounded w-100 service-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Info Section --}}
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

    {{-- Testimonials Section --}}
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
                    @foreach ($cities as $city)
                        <a href="{{ url($city->url) }}" class="city-tag">
                              <i class="fas fa-taxi"></i> {{ Str::limit($city->name, 15, '..') }}
                        </a>
                    @endforeach
            </div>
            @if ($cities->count() > 36)
                <div class="text-center mt-4">
                    <a href="{{ route('area.we.serve') }}"
                    class="btn btn-warning fw-bold px-4 shadow">
                        Show More
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- FAQ & Blog Section --}}
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

                {{-- Question 2 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2" aria-expanded="false" aria-controls="c2">
                            Do you clean your vehicles often?
                        </button>
                    </h2>
                    <div id="c2" class="accordion-collapse collapse" aria-labelledby="h2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes. Every vehicle is thoroughly disinfected after every ride to keep both you and other passengers safe and healthy.
                        </div>
                    </div>
                </div>

                {{-- Question 3 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3" aria-expanded="false" aria-controls="c3">
                            Do you have child seats in your car?
                        </button>
                    </h2>
                    <div id="c3" class="accordion-collapse collapse" aria-labelledby="h3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely. We do have infant, toddler, and booster seats. Ask for it when you book and we’ll have it ready.
                        </div>
                    </div>
                </div>

                {{-- Question 4 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c4" aria-expanded="false" aria-controls="c4">
                            What if my flight is running late?
                        </button>
                    </h2>
                    <div id="c4" class="accordion-collapse collapse" aria-labelledby="h4" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            No worries! We check your flight in real time, so your pickup time automatically adjusts, at no extra charge for reasonable delays.
                        </div>
                    </div>
                </div>

                {{-- Question 5 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="h5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c5" aria-expanded="false" aria-controls="c5">
                            Are there hidden charges in your pricing?
                        </button>
                    </h2>
                    <div id="c5" class="accordion-collapse collapse" aria-labelledby="h5" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Never. We provide open-book pricing with flat-rate rates. No hidden fees, no surprises.
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-center fw-bold mb-4">Latest Blog</h2>
            <div class="container">
                <div class="services-section">
                    <div class="row g-4">
                        @forelse($blogs as $blog)
                            <div class="col-md-4">
                                <div class="blog-card h-100">
                                    <div class="blog-img-container">
                                        @if ($blog->thumbnail)
                                            <img src="{{ asset('storage/' . $blog->thumbnail) }}"
                                                alt="{{ $blog->title }}">
                                        @else
                                            <img src="{{ asset('images/blog.webp') }}" alt="Default Image">
                                        @endif
                                    </div>
                                    <a href="{{ route( 'blog.details',$blog->slug) }}" class="blog-title">
                                        {{ Str::limit($blog->title, 60) }}
                                    </a>
                                    <div class="blog-meta">
                                        admin /// {{ \Carbon\Carbon::parse($blog->published_at)->format('F d, Y') }}
                                        /// No Comments
                                    </div>
                                    <a href="{{ url($blog->slug) }}" class="read-more-btn">Read
                                        More »</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <p class="text-muted">No recent blog posts available.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.pages.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Notification Scripts (MERGED & FIXED) --}}
    @if (session()->has('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Custom Notify Function
                if (typeof Notify === 'function') {
                    Notify(
                        "{{ session('notify.type') }}",
                        "{{ session('notify.message') }}"
                    );
                }

                // 2. SweetAlert
                Swal.fire({
                    toast: true,
                    position: 'top-center',
                    icon: "{{ session('notify.type') }}",
                    title: "{{ session('notify.message') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                });
            });
        </script>

        {{-- 3. Bootstrap Alert Banner --}}
        <div class="container fixed-top mt-3" style="z-index: 9999">
            <div class="alert alert-{{ session('notify.type') }} alert-dismissible fade show shadow" role="alert">
                {{ session('notify.message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    {{-- Schema Markup (Using Push) --}}
    {{-- @push('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TaxiService",
      "name": "Boston Logan Airport Taxi",
      "image": "{{ asset('images/logo.png') }}",
      "@id": "{{ url('/') }}",
      "url": "{{ url('/') }}",
      "telephone": "+1857-331-9544",
      "email": "blairporttaxicab@gmail.com",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "3 Putnam Gardens Apt 22",
        "addressLocality": "Cambridge",
        "addressRegion": "MA",
        "postalCode": "02139",
        "addressCountry": "US"
      },
      "areaServed": [
        {"@type": "City", "name": "Boston"},
        {"@type": "City", "name": "Cambridge"},
        {"@type": "City", "name": "Somerville"},
        {"@type": "Airport", "name": "Boston Logan International Airport"}
      ],
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
        "opens": "00:00",
        "closes": "23:59"
      },
      "sameAs": [
        "https://www.facebook.com/bostonloganairporttaxi1",
        "https://twitter.com/blairporttaxi"
      ]
    } --}}
    {{-- </script> --}}
    {{-- @endpush --}}

</body>

</html>
