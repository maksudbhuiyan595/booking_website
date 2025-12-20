@extends('frontend.pages.master')

@section('content')

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

    /* Image Styling - FIXED HEIGHT */
    .services-banner img {
        width: 100%;
        height: 550px;       /* Fixed height */
        object-fit: cover;   /* Prevents stretching */
        object-position: center;
        display: block;
    }

    /* Overlay Text Styling - CENTERED */
    .banner-text-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        width: 90%;
        z-index: 2;
        text-align: center;
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

<div class="container">
    <div class="services-section">
        <h3 class="services-title">Popular Cities for Car Service in Boston Neighborhood</h3>

       <div class="city-grid">
            @forelse($cities as $city)
                {{-- Updated Link to use 'service.details' route --}}
                <a href="{{ route('service.details', $city->slug) }}" class="city-badge" title="{{ $city->name }}">
                    <i class="fas fa-taxi"></i> {{ Str::limit($city->name, 15, '..') }}
                </a>
            @empty
                <div class="col-12">
                    <p class="text-muted">No service areas found.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Links --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $cities->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
