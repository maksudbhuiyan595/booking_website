@extends('frontend.pages.master')

{{-- 1. SEO SECTION (Meta Title, Description & Tags) --}}
@section('title', $page->meta_title ?? $page->route_name)

@section('meta')
    <meta name="description" content="{{ $page->meta_description ?? Str::limit(strip_tags($page->content), 150) }}">

    {{-- FIX: Check if tags is an array and implode it, otherwise use it directly or fallback --}}
    <meta name="keywords" content="{{ is_array($page->tags) ? implode(', ', $page->tags) : ($page->tags ?? 'taxi service, airport transfer, cab booking') }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:title" content="{{ $page->meta_title ?? $page->route_name }}">
    <meta property="og:description" content="{{ $page->meta_description ?? Str::limit(strip_tags($page->content), 150) }}">
    <meta property="og:image" content="{{ !empty($page->cover_image) ? asset('storage/' . $page->cover_image) : asset('images/default-taxi.webp') }}">
@endsection

@section('content')

<style>
    /* --- GLOBAL STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9 !important;
    }

    /* --- BANNER SECTION --- */
    .services-banner {
        width: 100%;
        margin-bottom: 50px;
        position: relative;
        height: 500px;
        overflow: hidden;
    }

    .services-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        filter: brightness(0.6);
        transition: transform 0.5s ease;
    }

    .services-banner:hover img {
        transform: scale(1.02);
    }

    .banner-text-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        text-align: center;
        width: 90%;
        z-index: 2;
    }

    .banner-text-overlay h1 {
        font-weight: 800;
        font-size: 3.5rem;
        text-transform: uppercase;
        margin-bottom: 15px;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }

    .banner-text-overlay p {
        font-size: 1.3rem;
        font-weight: 500;
        text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
    }

    /* --- CONTENT AREA --- */
    .service-content-wrapper {
        background: white;
        padding: 50px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        margin-bottom: 40px;
    }

    .service-body {
        font-size: 1.15rem;
        line-height: 1.9;
        color: #444;
    }

    /* Editor Image Responsive */
    .service-body img {
        max-width: 100%;
        height: auto !important;
        border-radius: 8px;
        margin: 25px 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .service-body h2 {
        color: #222;
        font-weight: 700;
        margin-top: 40px;
        font-size: 2rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    /* --- FAQ SECTION --- */
    .faq-section {
        margin-top: 60px;
    }

    .faq-title {
        font-weight: 800;
        margin-bottom: 35px;
        color: #111;
        text-align: center;
        text-transform: uppercase;
    }

    .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 10px !important;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    }

    .accordion-button:not(.collapsed) {
        color: #0d6efd;
        background-color: #f0f7ff;
        box-shadow: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .services-banner { height: 300px; }
        .banner-text-overlay h1 { font-size: 2rem; }
        .service-content-wrapper { padding: 25px; }
    }
</style>


<div class="services-banner">
    @if(!empty($page->cover_image) && file_exists(public_path('storage/' . $page->cover_image)))
        <img src="{{ asset('storage/' . $page->cover_image) }}" alt="{{ $page->route_name }}">
    @else

        <img src="{{ asset('images/Taxi Service.webp') }}" alt="{{ $page->route_name }}">
    @endif

    <div class="banner-text-overlay">
        <h1>{{ $page->route_name }}</h1>
        <p>Premium & Reliable Transportation Service</p>
    </div>
</div>

@include('frontend.pages.rating')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- 3. MAIN CONTENT (from 'content' column) --}}
            <div class="service-content-wrapper">
                <div class="service-body">
                    {!! $page->content !!}
                </div>
            </div>

            {{-- 4. FAQ SECTION (from 'faqs' json column) --}}
            @if(!empty($page->faqs))
                <div class="faq-section">
                    <h3 class="faq-title">Frequently Asked Questions</h3>

                    <div class="accordion" id="pageFaqAccordion">
                        @php
                            // JSON Decode
                            $faqs = is_string($page->faqs) ? json_decode($page->faqs, true) : $page->faqs;
                        @endphp

                        @if(is_array($faqs) || is_object($faqs))
                            @foreach($faqs as $index => $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $index }}">
                                            {{-- JSON Key Check --}}
                                            {{ $faq['question'] ?? $faq['title'] ?? 'Question' }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                         aria-labelledby="heading{{ $index }}" data-bs-parent="#pageFaqAccordion">
                                        <div class="accordion-body">
                                            {!! $faq['answer'] ?? $faq['description'] ?? 'Answer Not Available' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            {{-- 5. CTA BUTTON --}}
            <div class="text-center mt-5 mb-5">
                <h4 class="mb-3 fw-bold">Need a ride for this route?</h4>
                <a href="{{ route('home', ['service' => $page->route_name]) }}"
                   class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow rounded-pill">
                    Book Now: <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
