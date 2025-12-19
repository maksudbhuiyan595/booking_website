@extends('frontend.pages.master')

@section('content')

{{-- FontAwesome is required for icons. Uncomment if not loaded in master layout --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}

<style>
    /* --- GLOBAL STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
    }

    /* --- SERVICES SECTION STYLES --- */
    .services-section {
        margin-top: 50px;
        margin-bottom: 60px;
        text-align: center;
    }

    /* Banner Container (Full Width) */
    .services-banner {
        width: 100%;
        margin-bottom: 40px;
        overflow: hidden;
        position: relative;
    }

    /* Image Styling - FIXED HEIGHT 550PX */
    .services-banner img {
        width: 100%;
        height: 550px;       /* আপনার রিকোয়ারমেন্ট অনুযায়ী হাইট */
        object-fit: cover;   /* ইমেজ যাতে চ্যাপ্টা না হয় */
        object-position: center; /* ইমেজের মাঝখানের অংশ ফোকাস করবে */
        display: block;
    }

    /* Overlay Text Styling - CENTERED */
    .banner-text-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* একদম মাঝখানে আনার জন্য */
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        width: 90%;
        z-index: 2;
        text-align: center; /* টেক্সট সেন্টারে এলাইন করা হলো */
    }

    .banner-text-overlay h2 {
        font-weight: 800;
        font-size: 3.5rem;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .services-title {
        color: #3b82f6;
        font-weight: 700;
        margin-bottom: 40px;
        font-size: 24px;
    }

    .city-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .city-badge {
        background-color: #fbbf24;
        color: black;
        padding: 10px 15px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, background-color 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .city-badge:hover {
        background-color: #f59e0b;
        color: black;
        transform: translateY(-2px);
        text-decoration: none;
    }

    .city-badge i {
        margin-right: 8px;
        font-size: 14px;
    }

    /* --- RESPONSIVE DESIGN --- */
    @media (max-width: 992px) {
        .city-grid { grid-template-columns: repeat(3, 1fr); }
        .banner-text-overlay h2 { font-size: 2.5rem; }
    }

    @media (max-width: 768px) {
        .city-grid { grid-template-columns: repeat(2, 1fr); }
        .services-banner img { height: 350px; }
        .banner-text-overlay h2 { font-size: 2rem; }
    }

    @media (max-width: 576px) {
        .city-grid { grid-template-columns: repeat(1, 1fr); }
        .services-banner img { height: 250px; }
        .banner-text-overlay h2 { font-size: 1.5rem; }
    }
</style>

<div class="services-banner">
    <img src="{{ asset('images/Taxi Service.webp') }}" alt="Area We Services Banner">

    <div class="banner-text-overlay">
        <h2>Area We Service</h2>
    </div>
</div>

  @include('frontend.pages.rating')

<div class="container ">
    <div class="services-section">
        <h3 class="services-title">Popular Cities for Car Service in Boston Neighborhood</h3>

        <div class="city-grid">
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Cape Cod</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Barnstable Ma</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Salem</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Amherst MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Provincetown</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Eastham MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Merrimack NH</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Portland ME</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> North Truro</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Yarmouth MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Derry NH</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Lexington MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Falmouth MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Nashua NH</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Providence RI</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Boston MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Manchester NH</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Woburn MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Andover MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Greenfield MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Winchester MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Burlington Ma</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Wilmington MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Wakefield MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Lawrence MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Acton MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Concord MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Billerica MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Beverly MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Danvers MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Belmont MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Cambridge MA</a>

            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Stoneham MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Lynnfield MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Tewksbury MA</a>
            <a href="#" class="city-badge"><i class="fas fa-taxi"></i> Methuen MA</a>
        </div>
    </div>
</div>

@endsection
