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

    /* --- SERVICES/BANNER STYLES --- */
    .services-section {
        margin-top: 50px;
        margin-bottom: 60px;
    }

    /* Banner Container (Full Width) */
    .services-banner {
        width: 100%;
        margin-bottom: 40px;
        overflow: hidden;
        position: relative;
    }

    /* Image Styling */
    .services-banner img {
        width: 100%;
        height: 400px; /* Adjusted height for standard banner look */
        object-fit: cover;
        object-position: center;
        display: block;
        filter: brightness(0.7); /* Slightly darken image so text pops */
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
        letter-spacing: 2px;
    }

    /* --- BLOG GRID STYLES --- */
    .blog-card {
        margin-bottom: 30px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-img-container {
        width: 100%;
        height: 220px; /* Fixed height for uniformity */
        overflow: hidden;
        margin-bottom: 15px;
    }

    .blog-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .blog-img-container img:hover {
        transform: scale(1.05); /* Slight zoom effect on hover */
    }

    .blog-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a0dab; /* Standard Title Blue */
        margin-bottom: 8px;
        line-height: 1.4;
        text-decoration: none;
        display: block;
    }

    .blog-title:hover {
        text-decoration: underline;
        color: #1a0dab;
    }

    .blog-meta {
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 12px;
        font-weight: 400;
    }

    .read-more-btn {
        font-size: 0.85rem;
        color: #333;
        font-weight: 500;
        text-decoration: none;
        margin-top: auto; /* Pushes button to bottom if heights vary */
    }

    .read-more-btn:hover {
        text-decoration: underline;
        color: #000;
    }

    /* --- RESPONSIVE DESIGN --- */
    @media (max-width: 992px) {
        .banner-text-overlay h2 { font-size: 2.5rem; }
    }

    @media (max-width: 768px) {
        .services-banner img { height: 250px; }
        .banner-text-overlay h2 { font-size: 2rem; }
    }
</style>

<div class="services-banner">
    <img src="{{ asset('images/Airport taxi Service.webp') }}" alt="Airport taxi Service">

    <div class="banner-text-overlay">
        <h2>Blogs</h2>
    </div>
</div>

<div class="container">
    <div class="services-section">
        <div class="row g-4"> <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog.webp') }}" alt="Border Cafe">
                    </div>
                    <a href="#" class="blog-title">Explore Border Cafe in Burlington, MA 01803: A Local Gem</a>
                    <div class="blog-meta">admin /// September 20, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog.webp') }}" alt="Cary Memorial Library">
                    </div>
                    <a href="#" class="blog-title">Why Cary Memorial Library is a Must-Visit in Lexington</a>
                    <div class="blog-meta">admin /// September 18, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog.webp') }}" alt="Falmouth Hospital">
                    </div>
                    <a href="#" class="blog-title">Get to Falmouth Hospital, MA, Fast with Our Taxi Service</a>
                    <div class="blog-meta">admin /// August 25, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog.webp') }}" alt="Falmouth MA">
                    </div>
                    <a href="#" class="blog-title">Top Things to Do in Falmouth, MA – A Local's Guide</a>
                    <div class="blog-meta">admin /// August 18, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog.webp') }}" alt="Parkside Cafe">
                    </div>
                    <a href="#" class="blog-title">Visit Parkside Cafe in Falmouth, MA for Great Food</a>
                    <div class="blog-meta">admin /// August 8, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog6.jpg') }}" alt="Provincetown Vacation">
                    </div>
                    <a href="#" class="blog-title">Explore the Best Provincetown Vacation Rentals Cape Cod Today</a>
                    <div class="blog-meta">admin /// August 1, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

             <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog7.jpg') }}" alt="Minivan Airport Taxi">
                    </div>
                    <a href="#" class="blog-title">Why Choose a Minivan Airport Taxi for Your Next Trip?</a>
                    <div class="blog-meta">admin /// July 30, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

             <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog8.jpg') }}" alt="Child Ride Front Seat">
                    </div>
                    <a href="#" class="blog-title">When Can a Child Ride in The Front Seat of The Taxi</a>
                    <div class="blog-meta">admin /// July 28, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

             <div class="col-md-4">
                <div class="blog-card">
                    <div class="blog-img-container">
                        <img src="{{ asset('images/blog9.jpg') }}" alt="Minivan Airport Taxi">
                    </div>
                    <a href="#" class="blog-title">Why Choose a Minivan Airport Taxi for Your Next Trip?</a>
                    <div class="blog-meta">admin /// July 20, 2025 /// No Comments</div>
                    <a href="#" class="read-more-btn">Read More »</a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
