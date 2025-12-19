<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boston Logan Airport Taxi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
    @include('frontend.css.style')
    {{-- <style>
        :root {
            --primary-bg: #05110e;
            --form-bg: #112621;
            --form-border: #2a453d;
            --btn-green: #008f58;
            --btn-green-hover: #007a4d;
            --btn-shadow: #003d26;
            --input-bg: #ffffff;
            --text-white: #ffffff;
            --text-muted: #b0b0b0;
            --header-yellow: #ffc800;
            --header-yellow-hover: #e0b000;
            --footer-bg: #000000;
            --light-section-bg: #f4f7f6;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-white);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 66px;
        }

        /* --- NAVBAR --- */
        .navbar-custom {
            background-color: rgba(0, 0, 0, 0.95);
            padding: 8px 0;
            border-bottom: 1px solid #333;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .nav-link-custom {
            color: var(--header-yellow) !important;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 8px;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover { color: #fff !important; transform: translateY(-1px); }

        .btn-phone {
            background: linear-gradient(45deg, var(--header-yellow), #ffd54f);
            color: #000;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 4px;
            padding: 5px 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(255, 200, 0, 0.3);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-phone:hover {
            background: var(--header-yellow-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 200, 0, 0.4);
        }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            flex: 1;
            padding-top: 30px;
            padding-bottom: 60px; /* Extra padding for form overlap */
            background-color: #ffffff;
            z-index: 1;
        }

        .main-title {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1.1;
            text-transform: uppercase;
            margin-bottom: 30px;
            color: #000000;
            letter-spacing: -1px;
            text-align: center;
        }

        /* --- FLOATING RESERVATION CARD --- */
        .reservation-card {
            background-color: var(--form-bg);
            border: 1px solid var(--form-border);
            border-radius: 8px;
            padding: 31px 15px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* CRITICAL FOR "UPOR DIA JABE" (FLOATING) EFFECT */
            z-index: 100;
            transition: all 0.3s ease;
        }

        .form-header h3 { font-weight: 800; text-transform: uppercase; font-size: 1.1rem; text-align: center; margin-bottom: 2px; color: white; letter-spacing: 0.5px; }
        .form-header p { font-size: 0.75rem; text-align: center; color: #ccc; margin-bottom: 10px; font-weight: 300; }

        .form-control, .form-select {
            background-color: var(--input-bg) !important; border: 1px solid #ccc; border-radius: 4px;
            color: #333 !important; font-size: 0.85rem !important; padding: 6px 10px !important;
            margin-bottom: 0 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05); font-weight: 500; height: auto;
        }
        .input-group { margin-bottom: 0; }
        .input-group-text { padding: 6px 8px !important; font-size: 0.85rem !important; background-color: #f0f0f0; border: 1px solid #ccc; color: #555; }
        .form-control:focus, .form-select:focus { background-color: #fff !important; border-color: var(--btn-green); box-shadow: 0 0 0 3px rgba(0, 143, 88, 0.2); }

        /* --- TRIP TYPES --- */
        .trip-type-container { display: flex; justify-content: space-between; gap: 5px; margin: 8px 0 10px 0; }
        .trip-option { flex: 1; position: relative; }
        .trip-option input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .trip-card {
            display: flex; flex-direction: row; align-items: center; justify-content: center;
            background-color: rgba(255, 255, 255, 0.05); border: 1px solid #555; border-radius: 4px;
            padding: 8px 2px; cursor: pointer; transition: all 0.2s ease; color: #ccc; height: 100%; text-align: center;
        }
        .trip-card i { font-size: 0.9rem; margin-right: 6px; margin-bottom: 0; color: #888; }
        .trip-card span { font-size: 0.75rem; font-weight: 600; line-height: 1; white-space: nowrap; }
        .trip-card:hover { border-color: #888; background-color: rgba(255, 255, 255, 0.1); }
        .trip-option input[type="radio"]:checked + .trip-card { background-color: var(--header-yellow); border-color: var(--header-yellow); color: #000; box-shadow: 0 0 10px rgba(255, 200, 0, 0.3); }
        .trip-option input[type="radio"]:checked + .trip-card i { color: #000; }

        /* --- EXTRAS --- */
        .extras-toggle {
            cursor: pointer; font-weight: 700; color: var(--header-yellow); font-size: 0.85rem;
            display: flex; align-items: center; margin-bottom: 8px; padding-top: 5px; user-select: none; transition: color 0.3s;
        }
        .extras-toggle:hover { color: #fff; }
        .extras-toggle i { margin-right: 6px; font-size: 0.9rem; transition: transform 0.3s ease; }
        .extras-toggle.active i { transform: rotate(45deg); }

        #extrasSection { max-height: 0; overflow: hidden; opacity: 0; transition: max-height 0.5s ease, opacity 0.3s ease; }
        #extrasSection.open { max-height: 800px; opacity: 1; margin-bottom: 10px; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; }

        .extra-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 4px; }
        .extra-label { font-size: 0.8rem; color: #fff; font-weight: 500; }
        .extra-price-tag { font-weight: 700; color: var(--header-yellow); }
        .extra-select-box { background-color: #fff; border: 1px solid #ccc; padding: 0px 5px; width: 45px; font-size: 0.8rem; height: 24px; cursor: pointer; margin-right: 8px; border-radius: 4px; font-weight: bold; }
        .total-price-display { font-size: 0.9rem; font-weight: 700; color: #fff; min-width: 30px; text-align: right; }

        .btn-get-fare {
            background-color: var(--btn-green); color: white; font-weight: 600; width: 100%;
            padding: 10px; font-size: 1rem; border-radius: 6px; border: none;
            box-shadow: 0 4px 0 var(--btn-shadow); transition: all 0.2s; margin-top: 10px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btn-get-fare:hover { background-color: var(--btn-green-hover); color: white; }

        .footer-note { text-align: center; font-size: 0.7rem; color: #aaa; margin-top: 10px; line-height: 1.3; font-weight: 300; margin-bottom: 0; }
        .mini-label { font-size: 0.65rem; color: #aaa; margin-bottom: 2px; display: block; font-weight: 500; text-transform: uppercase; }

        /* --- DESKTOP LAYOUT (FLOATING EFFECT) --- */
        .hero-row {
            position: relative; /* Anchor for absolute positioning */
        }

        .description-column {
            display: flex;
            flex-direction: column;
            /* Add padding to the left so it doesn't touch the form area */
            padding-left: 20px;
        }

        .description-text {
            font-size: 0.95rem;
            color: #333333;
            margin-bottom: 15px;
            line-height: 1.5;
            margin-top: 0;
        }

        .hero-img-wrapper {
            width: 100%;
            height: 450px;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .hero-img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }

        /* --- DESKTOP SPECIFIC: MAKE FORM FLOAT --- */
        @media (min-width: 992px) {
            .form-column {
                position: relative;
                /* Do not set height here, let it be 0 effectively for flow, or just use absolute child */
            }

            .reservation-card {
                position: absolute; /* FLOATING! */
                top: 0;
                left: 12px; /* Adjust based on Bootstrap gutter */
                right: 12px;
                /* This ensures it expands ON TOP of content below */
            }

            /* Ensure the hero section has enough height for the closed form + image */
            .hero-section {
                /* Min height ensures the ratings section isn't covered by the CLOSED form */
                min-height: 600px;
            }
        }

        @media (max-width: 991px) {
            body { padding-top: 60px; }
            .hero-section { padding-top: 10px; min-height: auto; }
            .main-title { font-size: 1.8rem; margin-bottom: 10px; }
            .reservation-card { position: relative; width: 100%; margin-bottom: 30px; }
            .description-column { padding-left: 0; margin-top: 0; }
            .trip-card { padding: 10px 2px; }
            .hero-img-wrapper { height: 250px; }
        }

        /* --- SECTIONS --- */
        .content-section { background-color: #ffffff; color: #333; padding: 50px 0; }
        .bg-light { background-color: var(--light-section-bg) !important; }
        .section-title { font-weight: 800; margin-bottom: 20px; color: #111; text-transform: uppercase; letter-spacing: 0.5px; }
        .service-img { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .why-choose-list { list-style: none; padding-left: 0; }
        .why-choose-list li { position: relative; padding-left: 25px; margin-bottom: 10px; font-size: 0.95rem; color: #444; }
        .why-choose-list li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: var(--btn-green); position: absolute; left: 0; }
        .testimonial-box { background: #fff; padding: 25px; border-radius: 8px; height: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 4px solid var(--header-yellow); }
        .testimonial-text { font-style: italic; font-size: 0.9rem; color: #555; line-height: 1.6; }
        .customer-name { font-weight: 700; margin-top: 15px; color: #111; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
        .city-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; margin-top: 30px; }
        .city-tag { background-color: #222; color: #fff; padding: 12px 15px; border-radius: 4px; font-weight: 500; font-size: 0.85rem; display: flex; align-items: center; text-decoration: none; transition: all 0.3s; border-left: 4px solid var(--header-yellow); }
        .city-tag:hover { background-color: #000; color: var(--header-yellow); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .city-tag i { margin-right: 10px; color: var(--header-yellow); }
        .ratings-row img { max-height: 140px; margin: 0 10px; }
        .ratings-row img:hover { filter: grayscale(0%); opacity: 1; }

        /* Accordion & Footer */
        .accordion-item { border: 1px solid #e0e0e0; margin-bottom: 8px; border-radius: 4px !important; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .accordion-button { background-color: #fff; color: #222; font-weight: 600; padding-left: 3rem; position: relative; box-shadow: none !important; font-size: 1rem; padding-top: 12px; padding-bottom: 12px; }
        .accordion-button:not(.collapsed) { background-color: #f9fdfb; color: var(--btn-green); }
        .accordion-button::after { display: none; }
        .accordion-button::before { content: '+'; position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.2rem; font-weight: 300; color: var(--header-yellow); border: 1px solid #ddd; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s; }
        .accordion-button:not(.collapsed)::before { content: '-'; background-color: var(--btn-green); color: #fff; border-color: var(--btn-green); }
        .accordion-body { padding-left: 3rem; color: #555; line-height: 1.5; font-size: 0.95rem; }

        .blog-card { border: none; border-radius: 8px; background: #fff; overflow: hidden; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s; }
        .blog-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .blog-img-wrapper { position: relative; height: 180px; width: 100%; }
        .blog-img { width: 100%; height: 100%; object-fit: cover; }
        .blog-badge-overlay { position: absolute; top: 10px; right: 10px; background-color: var(--btn-green); color: white; padding: 4px 10px; font-size: 0.7rem; font-weight: 700; border-radius: 4px; text-transform: uppercase; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .blog-avatar-overlay { position: absolute; bottom: -20px; left: 20px; width: 40px; height: 40px; background-color: #ddd; border: 3px solid #fff; border-radius: 50%; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .blog-content { padding: 25px 20px 20px; }
        .blog-content h5 { font-size: 1rem; line-height: 1.4; margin-bottom: 8px; }
        .read-more-link { color: var(--btn-green); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; text-decoration: none; letter-spacing: 0.5px; }
        .read-more-link:hover { color: #005a38; text-decoration: underline; }

        .footer-section { background-color: var(--footer-bg); color: #aaa; padding-top: 40px; padding-bottom: 20px; border-top: 1px solid #222; }
        .footer-title { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: #fff; text-transform: uppercase; letter-spacing: 1px; border-left: 3px solid var(--header-yellow); padding-left: 10px; }
        .footer-links a { color: #aaa; transition: all 0.2s; text-decoration: none; font-size: 0.9rem; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a:hover { color: var(--header-yellow); }
        .contact-icon { color: var(--header-yellow); }
        .contact-info li { display: flex; gap: 10px; margin-bottom: 12px; font-size: 0.9rem; }
        .social-icons { display: flex; gap: 10px; margin-top: 15px; }
        .social-btn { border: 1px solid #333; color: #aaa; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; transition: all 0.3s; font-size: 0.9rem; }
        .social-btn:hover { background-color: var(--header-yellow); border-color: var(--header-yellow); color: #000; }
        .copyright-text { color: #555; font-size: 0.8rem; text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #222; }

        /* Popup */
        .custom-popup { position: fixed; top: 80px; left: 50%; transform: translateX(-50%) translateY(-20px); background-color: white; color: #333; padding: 10px 20px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); z-index: 9999; display: flex; align-items: center; gap: 15px; opacity: 0; visibility: hidden; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55); min-width: 300px; border-left: 6px solid; }
        .custom-popup.show { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .custom-popup.error { border-color: #dc3545; } .custom-popup.error i { color: #dc3545; }
        .custom-popup.success { border-color: #198754; } .custom-popup.success i { color: #198754; }
        .popup-icon { font-size: 1.2rem; } .popup-message { font-size: 0.9rem; font-weight: 600; }
    </style> --}}
</head>

<body>

    <div id="validationPopup" class="custom-popup">
        <div class="popup-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="popup-message" id="popupText">Message goes here</div>
    </div>

    @include('frontend.pages.nav')
    @include('frontend.pages.booking')

    <section class="content-section border-top border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 ratings-row">
                <div class="text-center"><img src="{{ asset('images/Google-Rating-1.webp') }}" alt="Google Rating"></div>
                <div class="text-center"><img src="{{ asset('images/Tripadvisor.webp') }}" alt="Tripadvisor"></div>
                <div class="text-center"><img src="{{ asset('images/Trustpilot.webp') }}" alt="Trustpilot"></div>
                <div class="text-center"><img src="{{ asset('images/Flux_Dev_highresolution_stock_photo_of_Create_an_image_with_th_1.webp') }}" alt="Yelp"></div>
            </div>
        </div>
    </section>

    <section class="content-section bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <p>Planning a trip to or from Logan International Airport? You deserve a clean, safe, and reliable ride, and that’s precisely what we provide. <strong>Boston Logan Airport Taxi</strong> is here with 24/7 professional airport transportation with licensed drivers, sanitized vehicles, and flat-rate pricing. Whether you are on a business trip, on vacation with your family, or need a hassle-free airport pickup, we’ve created our service to suit your comfort and peace of mind. We service all towns in Boston and guarantee on-time pickup every time. Just let us manage your ride so you can focus on your flight and relaxation.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <img src="{{ asset('images/Boston Logaqn Aorport Taxi Service.webp') }}" alt="White Van" class="img-fluid rounded mb-3 w-100 service-img">
                    <h3 class="section-title h4">Safe and Trusted Logan Airport Taxi Service Across Greater Boston</h3>
                    <p>We prioritize your well-being and safety. So, after every ride, we clean and disinfect every car. We clean door handles and seat belts, sanitize the seats and floor, and make every effort to keep anything that may affect both you and your loved ones clean. We keep both our drivers clean, but our cars also feel fresh and enjoyable for today’s discerning passenger on every ride. Your safety and well-being will never be overshadowed by entrepreneurship as you travel for any reason in and around Greater Boston.</p>
                </div>

                <div class="col-lg-6">
                    <h3 class="section-title h4">Why Choose Boston Logan Airport Taxi?</h3>
                    <ul class="why-choose-list">
                        <li>Flat-Rate, No-Surge Pricing</li>
                        <li>Vehicles Cleaned & Sanitized after Every Ride</li>
                        <li><a href="#" style="color:var(--btn-green); font-weight:bold;">Child Seats</a> Available</li>
                        <li>Available 24/7, Including on Holidays</li>
                        <li>Licensed, Background-Checked Drivers</li>
                        <li>Long-Distance & Event Rides Available</li>
                        <li>Live Flight Tracking & Real-Time Adjustments</li>
                    </ul>
                    <div class="mt-4 position-relative">
                        <img src="{{ asset('images/Sanitized Vehicles.webp') }}" alt="Sanitizing" class="img-fluid rounded w-100 service-img">
                        <div class="bg-white px-3 py-1 position-absolute bottom-0 start-50 translate-middle-x mb-3 rounded shadow-sm fw-bold" style="border: 1px solid #ddd;">
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
                    <p>Safety comes first, especially with our littlest rides. We offer properly installed infant, toddler, and booster seats, upon request, so your child’s safety can be ensured. Just tell us what you need when booking, and we will take care of the rest to ensure all your family can ride together.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                    <img src="{{ asset('images/Child Seat.webp') }}" alt="Child Seat" class="img-fluid rounded service-img">
                </div>
                <div class="col-lg-4">
                    <h3 class="section-title">TRAVEL WITH FAMILY OR LUGGAGE</h3>
                    <p>Additional bags, or are you with elderly parents, or traveling with a group? Our spacious sedans and vans make traveling with a group of colleagues, family members, etc, comfortable and convenient. You’ll enjoy the ride, whether your destination is near or far, with door-to-door service, friendly drivers, and in our spacious, cruise-worthy vehicles.</p>
                </div>
            </div>

            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="section-title">Reliable Airport Service</h3>
                    <p>We’re the go-to transportation service for prompt trips to and from Logan Airport, offering service to neighborhoods and towns such as <a href="#" style="color:var(--btn-green);">Boston</a>, Cambridge, Somerville, <a href="#" style="color:var(--btn-green);">Hanscom AFB</a>, Lexington, Waltham, Brookline, Burlington, Belmont, Arlington, Haverhill, Worcester, Methuen, Nashua, and many more. Our professional drivers are only a stone’s throw away, regardless of where you are. We monitor all flight delays in real-time, and your driver will be waiting for you on arrival at no extra cost. We promise one thing: on time, every time.</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/Boston Logan Airport.webp') }}" alt="Airport" class="img-fluid rounded w-100 service-img">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h3 class="section-title">Long-Distance & Special Event Transfers</h3>
                    <p>Need a ride beyond Boston? We have long-distance trips to <a href="#" style="color:var(--btn-green);">Cape Cod</a>, New York, New Hampshire, Yarmouth MA, and others. Whether it’s business travel, weddings, or events, our dependable service ensures comfort, promptness, and peace of mind. Leave the driving to us, and let us worry about directions.</p>
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
                        <p class="testimonial-text">"Reliable and clean taxi service. Got to Logan Airport with plenty of time to spare. Very professional driver. Highly recommend!"</p>
                        <div class="customer-name">Jessica Morgan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p class="testimonial-text">"Smooth ride with the car seat set the right way. The vehicle was spotless. Great service. I'll book again!"</p>
                        <div class="customer-name">Graham Bronson</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-box">
                        <p class="testimonial-text">"The driver was very polite and helped with our luggage, and the reservation was quite simple. Five stars from me."</p>
                        <div class="customer-name">Liam O'Connor</div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h3 class="fw-bold">Book Your Ride with Confidence</h3>
                    <p class="w-75 mx-auto">Whether you are booking ahead or need a ride at this time, <strong>Boston Logan Airport Taxi</strong> is ready for you! Booking is quick and easy, either give us a call or fill in our short reservation form. One of our employees will verify your booking, allocate a clean and modern car for your transfer, and guarantee a punctual driver. There is never any reason why traveling should be difficult, and with us, it isn’t. Book your ride today!</p>
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
                <button class="btn btn-warning fw-bold px-4 shadow">Show More</button>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Frequently Asked Questions (FAQ)</h2>
            <div class="accordion mb-5" id="faqAccordion">
                <div class="accordion-item"><h2 class="accordion-header" id="h1"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c1">How early in advance should I reserve a taxi to Logan Airport?</button></h2><div id="c1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">We advise all guests to book at least 24 hours in advance to ensure availability, although same-day rides might still be available.</div></div></div>
                <div class="accordion-item"><h2 class="accordion-header" id="h2"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">Do you clean your vehicles often?</button></h2><div id="c2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Yes. Every vehicle is thoroughly disinfected after every ride to keep both you and other passengers safe and healthy.</div></div></div>
                <div class="accordion-item"><h2 class="accordion-header" id="h3"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">Do you have child seats in your car?</button></h2><div id="c3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Absolutely. We do have infant, toddler, and booster seats. Ask for it when you book and we’ll have it ready.</div></div></div>
                <div class="accordion-item"><h2 class="accordion-header" id="h4"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c4">What if my flight is running late?</button></h2><div id="c4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">No worries! We check your flight in real time, so your pickup time automatically adjusts, at no extra charge for reasonable delays.</div></div></div>
                <div class="accordion-item"><h2 class="accordion-header" id="h5"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c5">Are there hidden charges in your shipping prices?</button></h2><div id="c5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Never. We provide open-book pricing with flat-rate rates. No hidden fees, no surprises.</div></div></div>
                <div class="accordion-item"><h2 class="accordion-header" id="h6"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c6">Do you cater for long-distance trips or event transit?</button></h2><div id="c6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Yes. We offer rides to Cape Cod, New York, and beyond. We also provide for events such as weddings, company travel, and family vacations. Just let us know your needs.</div></div></div>
            </div>

            <h2 class="text-center fw-bold mb-4">Latest Blog</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://placehold.co/400x300/orange/white?text=Cafe" class="blog-img" alt="Blog 1">
                            <span class="blog-badge-overlay">Travel Tips</span>
                            <div class="blog-avatar-overlay"></div>
                        </div>
                        <div class="blog-content">
                            <h5 class="fw-bold mt-2">Explore Border Cafe in Burlington, MA 01803: A Local Gem</h5>
                            <p class="text-muted small">If you are looking for a cozy spot to enjoy...</p>
                            <a href="#" class="read-more-link">READ MORE »</a>
                            <div class="mt-3 text-muted small" style="font-size: 0.7rem;">September 22, 2025 • No Comments</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://placehold.co/400x300/brown/white?text=Library" class="blog-img" alt="Blog 2">
                            <span class="blog-badge-overlay">Travel Tips</span>
                            <div class="blog-avatar-overlay"></div>
                        </div>
                        <div class="blog-content">
                            <h5 class="fw-bold mt-2">Why Cary Memorial Library is a Must-Visit in Lexington</h5>
                            <p class="text-muted small">Libraries are special places where people come to learn, read...</p>
                            <a href="#" class="read-more-link">READ MORE »</a>
                            <div class="mt-3 text-muted small" style="font-size: 0.7rem;">September 18, 2025 • No Comments</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="blog-card">
                        <div class="blog-img-wrapper">
                            <img src="https://placehold.co/400x300/green/white?text=Hospital" class="blog-img" alt="Blog 3">
                            <span class="blog-badge-overlay">Taxi Service Tips</span>
                            <div class="blog-avatar-overlay"></div>
                        </div>
                        <div class="blog-content">
                            <h5 class="fw-bold mt-2">Get to Falmouth Hospital, MA, Fast with Our Taxi Service</h5>
                            <p class="text-muted small">Going to a hospital can be stressful, even if it...</p>
                            <a href="#" class="read-more-link">READ MORE »</a>
                            <div class="mt-3 text-muted small" style="font-size: 0.7rem;">August 25, 2025 • No Comments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.pages.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
    </script>

    @if(session('notify'))
    <script>
        Notify("{{ session('notify.type') }}", "{{ session('notify.message') }}");
    </script>
    @endif


</body>
</html>
