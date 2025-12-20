@extends('frontend.pages.master')

@section('content')

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
        border-radius: 5px; /* Added subtle border radius */
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
        <div class="row g-4">

            {{-- Loop through the blogs passed from the Controller --}}
            @forelse ($blogs as $blog)
                <div class="col-md-4">
                    <div class="blog-card">
                        <div class="blog-img-container">
                            {{-- Check if thumbnail exists, otherwise show default --}}
                            @if($blog->thumbnail)
                                <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}">
                            @else
                                <img src="{{ asset('images/blog_image.png') }}" alt="Default Blog Image">
                            @endif
                        </div>

                        {{-- Link to details page --}}
                        <a href="{{ route('blog.details', $blog->slug) }}" class="blog-title">
                            {{ $blog->title }}
                        </a>

                        <div class="blog-meta">
                            admin ///
                            {{ \Carbon\Carbon::parse($blog->published_at)->format('F d, Y') }}
                            /// No Comments
                        </div>

                        <a href="{{ route('blog.details', $blog->slug) }}" class="read-more-btn">Read More »</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3>No blog posts available at the moment.</h3>
                </div>
            @endforelse

        </div>

        {{-- Optional: Pagination Links if you are paginating in the controller --}}
        {{-- <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
        </div> --}}
    </div>
</div>

@endsection
