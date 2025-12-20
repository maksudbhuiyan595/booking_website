@extends('frontend.pages.master')

@section('content')

<style>
    /* --- GLOBAL & EXISTING STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9 !important; /* Slightly darker bg for contrast */
    }

    /* --- BANNER STYLES --- */
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
        filter: brightness(0.6); /* Darken image for better text readability */
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
        font-size: 3rem;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .banner-text-overlay p {
        font-size: 1.2rem;
        font-weight: 500;
        opacity: 0.9;
    }

    /* --- CONTENT AREA --- */
    .service-content-wrapper {
        background: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 40px;
    }

    .service-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
    }

    /* Fix images inside the content editor */
    .service-body img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 20px 0;
    }

    .service-body h2, .service-body h3 {
        color: #111;
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    /* --- FAQ SECTION --- */
    .faq-section {
        margin-top: 50px;
    }

    .faq-title {
        font-weight: 700;
        margin-bottom: 25px;
        color: #111;
        text-align: center;
    }

    .accordion-item {
        border: none;
        margin-bottom: 10px;
        border-radius: 8px !important;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .accordion-button {
        font-weight: 600;
        color: #333;
        background-color: #fff;
    }

    .accordion-button:not(.collapsed) {
        color: #0d6efd; /* Bootstrap Primary Blue */
        background-color: #e7f1ff;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .services-banner { height: 350px; }
        .banner-text-overlay h1 { font-size: 2rem; }
        .service-content-wrapper { padding: 20px; }
    }
</style>

{{-- 1. DYNAMIC BANNER --}}
<div class="services-banner">
    {{-- Check if city has a specific cover image, otherwise use default --}}
    @if($city->cover_image)
        <img src="{{ asset('storage/' . $city->cover_image) }}" alt="Taxi Service in {{ $city->name }}">
    @else
        <img src="{{ asset('images/Taxi Service.webp') }}" alt="Default Service Banner">
    @endif

    <div class="banner-text-overlay">
        <h1>Taxi Service in {{ $city->name }}</h1>
        <p>Reliable & Affordable Airport Transfers</p>
    </div>
</div>

@include('frontend.pages.rating')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- 2. MAIN CONTENT --}}
            <div class="service-content-wrapper">
                <div class="service-body">
                    {{-- Render HTML content from database --}}
                    {!! $city->content !!}
                </div>
            </div>

            {{-- 3. FAQ SECTION (Dynamic JSON Handling) --}}
            @if($city->faqs)
                <div class="faq-section">
                    <h3 class="faq-title">Frequently Asked Questions about {{ $city->name }} Transfer</h3>

                    <div class="accordion" id="cityFaqAccordion">
                        @php
                            // Check if FAQs are already array (casted in model) or string (needs decode)
                            $faqs = is_string($city->faqs) ? json_decode($city->faqs, true) : $city->faqs;
                        @endphp

                        @if(is_array($faqs) || is_object($faqs))
                            @foreach($faqs as $index => $faq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $index }}">
                                            {{-- Handle different JSON keys (question/q/title) --}}
                                            {{ $faq['question'] ?? $faq['title'] ?? 'Question' }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                         aria-labelledby="heading{{ $index }}" data-bs-parent="#cityFaqAccordion">
                                        <div class="accordion-body">
                                            {!! $faq['answer'] ?? $faq['description'] ?? 'Answer' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            {{-- 4. CTA BUTTON --}}
            <div class="text-center mt-5">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow">
                    Book a Ride to {{ $city->name }}
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
