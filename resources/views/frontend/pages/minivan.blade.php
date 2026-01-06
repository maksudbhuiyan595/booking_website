@extends('frontend.pages.master')

@section('content')

<style>
    /* --- PAGE STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    /* --- HERO BANNER STYLES --- */
    .hero-banner {
        position: relative;
        width: 100%;
        height: 500px; /* Banner Height */
        overflow: hidden;
        margin-bottom: 50px;
    }

    .hero-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Dark overlay for text readability */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
    }

    .banner-title {
        color: #ffffff;
        font-size: 3.5rem;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 25px;
        text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
    }

    .banner-phone-btn {
        background-color: #fbbf24; /* Taxi Yellow */
        color: black;
        font-size: 1.5rem;
        font-weight: 700;
        padding: 15px 40px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: transform 0.3s, background-color 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .banner-phone-btn:hover {
        background-color: #f59e0b;
        transform: scale(1.05);
        color: black;
    }

    .banner-phone-btn i {
        margin-right: 10px;
    }

    /* --- CONTENT STYLES --- */
    .service-page-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 15px 40px 15px;
    }

    .section-title {
        color: #111;
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .section-text {
        font-size: 16px;
        line-height: 1.6;
        color: #333;
        margin-bottom: 20px;
    }

    .section-text strong {
        color: #000;
        font-weight: 700;
    }

    .section-text a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 700;
    }

    .feature-list {
        list-style: none;
        padding-left: 10px;
    }

    .feature-list li {
        position: relative;
        padding-left: 20px;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    .feature-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        top: 0;
        color: #000;
        font-weight: bold;
    }

    .content-img {
        width: 100%;
        height: auto;
        border-radius: 4px;
        object-fit: cover;
        max-height: 350px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .cta-box {
        background-color: #f0f9ff;
        border-left: 5px solid #3b82f6;
        padding: 20px;
        margin-top: 30px;
        border-radius: 4px;
    }

    /* Spacing between sections */
    .content-row {
        margin-bottom: 50px;
        align-items: flex-start;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .banner-title { font-size: 2.2rem; }
        .banner-phone-btn { font-size: 1.2rem; padding: 12px 30px; }
        .hero-banner { height: 400px; }
    }
</style>

<div class="hero-banner">
    {{-- Banner Image --}}
    <img src="{{ asset('images/Toyota sienna 2020 white 1.png') }}" alt="Minivan Taxi Service Banner">

    <div class="banner-overlay">
        {{-- Corrected 'Boston Map' to 'Boston MA' --}}
        <h1 class="banner-title">Minivan Taxi Service Near Me | Airport Taxi Van In Boston MA</h1>
    </div>
</div>

{{-- Rating Component Included Here --}}
@include('frontend.pages.booking')
@include('frontend.pages.rating')

<div class="container">

    <div class="row content-row">
        <div class="col-lg-6">
            <p class="section-text">
                Looking for a large <strong>minivan taxi service</strong> in Boston that’s perfect for family trips with the kids, group travel, or airport transportation? At <a href="#">Boston Logan Airport Taxi</a>, we are the experts when it comes to clean, comfortable, and prompt minivan services as you travel throughout Boston and the surrounding areas. Whether you’re headed to Logan Airport or need a taxi for a large group journey, our minivan taxi provides the space, safety, and security that you demand, all with the convenience of easy booking and reliable service.
            </p>
            <p class="section-text">
                Say goodbye to last-minute “<strong>taxi near me, Boston</strong>” searches. We have the ultimate ride for all of Spot and the Pack.
            </p>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <img src="{{ asset('images/Minivan-Taxi-Service.webp') }}" alt="Minivan Taxi Service Boston" class="content-img">
        </div>
    </div>

    <div class="row content-row">
        <div class="col-12">
            <h2 class="section-title">Why Our Minivan Taxis Are the Best Option</h2>
            <p class="section-text">
                Our minivans are more than a vehicle. They’re a mobile comfort zone, perfect for busy travelers, large families, and group trips. Here’s what differentiates our service:
            </p>
            <ul class="feature-list">
                <li>6 to 7-passenger spacious minivan fleet</li>
                <li>Plentiful storage for luggage. Airport runs made easy with all that luggage space</li>
                <li>Family-friendly, with available baby/booster seats</li>
                <li>Disinfected and sanitized after every ride</li>
                <li>Easy booking via website or phone</li>
                <li>24/7 services, always on time</li>
            </ul>
            <p class="section-text mt-3">
                Whether you need a reliable taxi van or a <strong>minivan taxi service</strong>, you can rely on our fleet. We are committed to providing a dependable service that you can trust.
            </p>
        </div>
    </div>

    <div class="row content-row">
        <div class="col-lg-6">
            <h2 class="section-title">Airport Taxi Van in Boston – Easy Logan Transfers</h2>
            <p class="section-text">
                In the mood for a smooth ride to or from Logan Airport? Our <strong>airport taxi van in Boston, MA</strong>, is the ideal choice for those who have a little more luggage, or more people, or more comfort.
            </p>
            <ul class="feature-list">
                <li>Free airport transfers with live flight monitoring</li>
                <li>Door-to-door service between the terminals and hotels</li>
                <li>Luggage assistance from friendly drivers</li>
                <li>Optional child seats are available upon request</li>
            </ul>
            <p class="section-text">
                We understand the importance of time and comfort when flying, so we ensure your airport transfer is completely stress-free.
            </p>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <img src="{{ asset('images/Airport-Taxi-Van-in-Boston.webp') }}" alt="Airport Taxi Van Boston" class="content-img">
        </div>
    </div>

    <div class="row content-row">
        <div class="col-12">
            <h2 class="section-title">Minibus Taxi Service – Perfect for Group Travelling</h2>
            <p class="section-text">
                Traveling with a large group? No problem. Our <strong>minibus taxi service</strong> is ideal for:
            </p>
            <ul class="feature-list">
                <li>School and college pickups</li>
                <li>Sporting events and concerts</li>
                <li>Parties for weddings and family events</li>
                <li>Long drives around New England</li>
            </ul>
            <p class="section-text mt-3">
                All minivans have smooth, clean, and comfortable seats with air-conditioning and access to the best-maintained systems. Our drivers are expert navigators of the highways and byways of Boston, and we can handle getting you wherever you need to go, safely and quickly.
            </p>
        </div>
    </div>

    <div class="row content-row">
        <div class="col-lg-6">
            <img src="{{ asset('images/minivan-taxi.webp') }}" alt="Book Minivan Taxi" class="content-img">
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <h2 class="section-title">How to Book Our Minivan Taxi Service</h2>
            <p class="section-text">
                Booking a <strong>minivan taxi</strong> with <strong>Boston Logan Airport Taxi</strong> is fast and simple. Just follow these steps:
            </p>
            <ul class="feature-list">
                <li>Call us or book online at <strong>bostonloganairporttaxi.com</strong></li>
                <li>If you need child seats or a larger luggage capacity, please let us know</li>
                <li>Share where and when you need to be picked up</li>
                <li>Receive confirmation of your delivery and take it easy as your ride is on the way!</li>
            </ul>
            <p class="section-text mt-3">
                Whether you are booking in advance or you need same-day service, we’ve got you covered 24/7.
            </p>
        </div>
    </div>

    <div class="row content-row">
        <div class="col-12">
            <h2 class="section-title">Conclusion</h2>
            <p class="section-text">
                At <strong>Boston Logan Airport Taxi</strong>, we know that a big group needs more space, more leg room, and more reliability. That’s one of the reasons why our <strong>minivan taxi service</strong> is used by families, professionals, and groups throughout Boston. Whether you require an <strong>airport taxi van in Boston, MA</strong>, or a <strong>taxi cab van</strong> for anniversaries, we are prepared to serve with a safe, timely, clean, and spacious ride.
            </p>
            <p class="section-text">
                <strong>Book now</strong> for a comfortable and reliable trip with additional space for your comfort, because your trip deserves more than just a ride.
            </p>
        </div>
    </div>

</div>

@endsection
