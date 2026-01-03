@extends('frontend.pages.master')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    /* ======================= HERO SECTION ======================= */
    .about-hero {
        position: relative;
        width: 100%;
        height: 450px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin-bottom: 50px;
    }

    .about-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.55), rgba(0,0,0,0.15));
    }

    .hero-title {
        position: relative;
        z-index: 2;
        font-size: 3.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 40px;
        text-shadow: 0px 4px 25px rgba(0,0,0,0.9);
    }

    /* ======================= TRUST LOGOS ======================= */
    .trust-section {
        padding: 40px 0;
        text-align: center;
        border-bottom: 1px solid #eee;
        margin-bottom: 60px;
    }

    .trust-img {
        height: 40px;
        opacity: 0.85;
        transition: .3s ease;
        filter: grayscale(10%);
    }

    .trust-img:hover {
        transform: scale(1.1);
        opacity: 1;
        filter: grayscale(0);
    }

    /* ======================= CONTENT ======================= */
    .section-title {
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .text-content {
        font-size: 1.05rem;
        color: #555;
        line-height: 1.75;
    }

    /* ======================= IMAGE CARD ======================= */
    .diff-img-container {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transition: .3s ease;
    }

    .diff-img-container:hover {
        transform: translateY(-6px);
    }

    .diff-img {
        width: 100%;
        display: block;
        object-fit: cover;
    }

    .highlight-link {
        color: #0074d9;
        font-weight: 600;
        border-bottom: 2px solid rgba(0,116,217,0.25);
        text-decoration: none;
    }

    .highlight-link:hover {
        border-bottom-color: #0074d9;
    }

    /* ======================= RESPONSIVE ======================= */
    @media (max-width: 768px) {
        .about-hero { height: 260px; }
        .hero-title { font-size: 2.4rem; margin-bottom: 25px; }
        .text-content { font-size: .95rem; }
    }
</style>

<!-- ======================= HERO SECTION ======================= -->
<div class="about-hero" style="background-image: url('{{ asset('images/Boston Logaqn Aorport Taxi Service.webp') }}');">
    <h1 class="hero-title">About Us</h1>
</div>

  @include('frontend.pages.booking')
  @include('frontend.pages.rating')

<div class="container">

    <!-- ======================= INTRO ======================= -->
    <div class="row mb-5">
        <p class="lead-intro text-start" style="font-size:1.15rem; color:#444; line-height:1.8;">
            At Boston Logan Airport Taxi, we know that airport transportation is more than just a ride.
            Comfortable and enjoyable service helps travelers have a successful trip. Whether you're
            a professional worker, a parent traveling with children, a first-time flyer, or a frequent
            traveler — we make your journey smooth, safe, and stress-free.
        </p>
    </div>

    <!-- ======================= MISSION ======================= -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="section-title">Our Mission</h3>
            <p class="text-content">
                Our mission is simple: provide reliable, comfortable, and safe transportation to and from Logan Airport.
                With professional chauffeurs, clean vehicles, and flat-rate pricing, we ensure your journey is the one
                thing you never have to stress about.
            </p>
        </div>
    </div>

    <!-- ======================= WHAT MAKES US DIFFERENT ======================= -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-7">
            <h3 class="section-title">What Makes Us Different</h3>
            <p class="text-content">
                We are not a rideshare service — we are airport transportation specialists.
                We understand flight schedules, travel stress, luggage needs, and traffic patterns.
                <br><br>
                Whether you're headed home or catching a flight, we offer a dependable
                <a href="#" class="highlight-link">Boston Logan Airport taxi service</a> that prioritizes comfort and punctuality.
            </p>
        </div>

        <div class="col-lg-5">
            <div class="diff-img-container">
                <img src="{{ asset('images/Child Seat.webp') }}" class="diff-img" alt="Child Seat">
            </div>
        </div>
    </div>

    <!-- ======================= FAMILY OPTIONS ======================= -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="section-title">Family & Group-Friendly Options</h3>
            <p class="text-content">
                We provide infant seats, toddler seats, booster seats, and spacious vans for large groups.
                Simply mention your needs during booking — we take care of the rest.
            </p>
        </div>
    </div>

    <!-- ======================= TRUSTED SERVICE ======================= -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="section-title">Trusted, Transparent, Professional</h3>
            <p class="text-content">
                No hidden fees. All our drivers are background-checked and trained in safety, customer relations,
                and navigation. Vehicles are sanitized after every trip.
            </p>
        </div>
    </div>

    <!-- ======================= AREAS WE SERVE ======================= -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="section-title">Serving Beyond Boston</h3>
            <p class="text-content">
                We proudly serve Logan Airport, Greater Boston, Cambridge, Brookline, Waltham, Lexington, Burlington,
                New Hampshire, and Cape Cod. Wherever you're going — we are ready.
            </p>
        </div>
    </div>

</div>

@endsection
