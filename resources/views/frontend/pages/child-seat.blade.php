@extends('frontend.pages.master')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* --- GENERAL SETTINGS --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333333;
    }

    p {
        margin-bottom: 1rem;
        line-height: 1.7;
        color: #444;
    }

    /* --- HERO SECTION --- */
    .child-seat-hero {
        position: relative;
        width: 100%;
        height: 400px;
        /* Updated Background with Blade asset helper */
        background: url('{{ asset("images/Airport Taxi with Child Car Seats.png") }}') no-repeat center center scroll;
        background-size: cover;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        margin-bottom: 50px;
    }

    /* Dark gradient overlay for text readability */
    .child-seat-hero::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
        pointer-events: none;
    }

    .hero-title {
        position: relative;
        z-index: 2;
        color: #ffffff;
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 30px;
        text-transform: capitalize;
        text-align: center;
        text-shadow: 0px 2px 10px rgba(0, 0, 0, 0.5);
    }

    /* --- TYPOGRAPHY & CONTENT --- */
    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111111;
        margin-bottom: 15px;
        margin-top: 25px;
    }

    /* List Styling */
    ul.feature-list {
        list-style-type: disc;
        padding-left: 20px;
        margin-bottom: 25px;
    }
    ul.feature-list li {
        margin-bottom: 8px;
        color: #444;
        line-height: 1.6;
    }

    /* --- IMAGES --- */
    .content-img-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        width: 100%;
        margin-bottom: 20px;
    }

    .content-img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    .highlight-link {
        color: #0066cc;
        text-decoration: none;
        font-weight: 600;
    }
    .highlight-link:hover { text-decoration: underline; }

    /* =========================================
       MOBILE RESPONSIVE QUERIES
    ========================================= */
    @media (max-width: 991px) {
        .child-seat-hero { height: 300px; }
        .hero-title { font-size: 2.8rem; }

        /* Stack images on tablet/mobile */
        .content-img-container { margin-top: 20px; }
    }

    @media (max-width: 768px) {
        .child-seat-hero { height: 220px; }
        .hero-title { font-size: 2rem; margin-bottom: 15px; }
        .section-title { font-size: 1.4rem; }
    }
</style>

<div class="child-seat-hero">
    <h1 class="hero-title">Child Seat Taxi Service</h1>
</div>
  @include('frontend.pages.rating')

<div class="container" style="background-color: #ffffff;">

    <div class="row align-items-center mb-5">
        <div class="col-lg-7">
            <p>
                Traveling in a busy city like Boston with a child can be a challenge, especially when you need a safe and secure ride. At <strong>Boston Logan Airport Taxi</strong>, we provide specialized <strong>taxi infant car seat</strong> services to accommodate your needs. Whether you’re heading to Logan Airport or returning home from the hospital with a newborn, or taking your toddler on a visit to the city, we’ve got the <strong>Taxi Baby Seat</strong> and <strong>newborn taxi</strong> services to safely and comfortably transport your family.
            </p>

            <h3 class="section-title">Why Choose Our Taxi with Infant Car Seat in Boston?</h3>
            <ul class="feature-list">
                <li>Clean, sanitized infant, toddler & booster seats</li>
                <li>Option for rear- and forward-facing car seats</li>
                <li>Drivers who are adequately trained in the installation of child seats</li>
                <li>24/7 support, including last-minute travel assistance</li>
                <li>Easy booking by phone or online</li>
            </ul>
        </div>
        <div class="col-lg-5">
            <div class="content-img-container">
                <img src="{{ asset('images/Taxi-with-Infant-Car-seat-Boston.webp') }}" alt="Smiling Baby in Car Seat" class="content-img">
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <p>
                Our <strong>taxi with a car seat Boston</strong> option complies with Massachusetts car seat safety laws and is designed for a worry-free experience for both you and your child, every time.
            </p>

            <h3 class="section-title">Airport Taxi with Car Seat</h3>
            <p>
                Traveling with children and flying in or out of Logan? We provide professional <strong>airport taxi with a car seat</strong> for a hassle-free and safe ride. We follow flight schedules, arrive on time, and bring the appropriate car seat that corresponds to your child’s age and weight.
            </p>
            <p>
                We will take care of it all from stowing your stroller and bags to ensuring your child is securely buckled in before we pull away.
            </p>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-lg-5 order-lg-2"> <div class="content-img-container">
                <img src="{{ asset('images/Airport-Taxi-with-Car-Seat.webp') }}" alt="Mother and Newborn in Car" class="content-img">
            </div>
        </div>
        <div class="col-lg-7 order-lg-1">
            <h3 class="section-title">Newborn Taxi For Your Baby's First Ride Home</h3>
            <p>
                Our <strong>newborn taxi</strong> is made just for new parents. We offer the highest quality car seats that are clean and safe, and are waiting for you. Whether you require transportation home from the hospital or need a check-up, our drivers are experienced at providing exactly the extra support and sensitivity you deserve.
            </p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3 class="section-title">How to Book a Taxi Infant Car Seat in Boston</h3>
            <p>
                Booking is simple! Contact us or place a request on our website for a ride. Just let us know:
            </p>
            <ul class="feature-list">
                <li>Your child’s age or weight</li>
                <li>Pickup and drop-off locations</li>
                <li>Preferred time and date</li>
            </ul>
            <p>
                We'll take care of the rest. No need to carry your own seat or worry about installation.
            </p>

            <h3 class="section-title">Conclusion</h3>
            <p>
                At <strong>Boston Logan Airport Taxi</strong>, we value nothing more than the safety of your child. That's why families from all over Boston trust our <strong>taxi infant car seat service</strong>. Whether it’s the drive home from the hospital or a trip across town to Grandma's, we’re here to help you feel more confident and assured about safety on the go.
            </p>
            <p>
                <a href="#" class="highlight-link">Book now</a> and you can travel with confidence, because your child deserves the very best.
            </p>
        </div>
    </div>

    <div class="mb-5"></div>

</div>

@endsection
