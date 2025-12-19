@extends('frontend.pages.master')

@section('content')

<style>
    /* --- PAGE STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    .blog-details-container {
        padding: 60px 0;
    }

    /* --- MAIN CONTENT STYLES --- */
    .blog-header {
        margin-bottom: 30px;
    }

    .blog-category-badge {
        background-color: #fbbf24; /* Yellow Accent */
        color: #000;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 15px;
        text-decoration: none;
    }

    .blog-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .blog-meta {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .blog-meta i {
        color: #fbbf24;
        margin-right: 5px;
    }

    .featured-image {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .article-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
        margin-bottom: 25px;
    }

    .article-content h3 {
        font-weight: 700;
        color: #111;
        margin-top: 40px;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    .article-content ul {
        margin-bottom: 25px;
        padding-left: 20px;
    }

    .article-content li {
        margin-bottom: 10px;
        font-size: 1.05rem;
    }

    .blockquote-custom {
        border-left: 5px solid #fbbf24;
        background-color: #f9fafb;
        padding: 25px;
        font-style: italic;
        font-size: 1.2rem;
        color: #555;
        margin: 40px 0;
        border-radius: 0 8px 8px 0;
    }

    /* --- SIDEBAR STYLES --- */
    .sidebar-widget {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-bottom: 30px;
    }

    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ddd;
        position: relative;
    }

    .widget-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: #fbbf24;
    }

    /* Recent Posts in Sidebar */
    .recent-post-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        text-decoration: none;
    }

    .recent-post-item:last-child { margin-bottom: 0; }

    .rp-img {
        width: 80px;
        height: 80px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 15px;
    }

    .rp-content h5 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        line-height: 1.4;
        transition: color 0.2s;
    }

    .recent-post-item:hover .rp-content h5 {
        color: #fbbf24; /* Yellow hover */
    }

    .rp-date {
        font-size: 0.75rem;
        color: #888;
    }

    /* Categories List */
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li {
        border-bottom: 1px solid #eee;
    }

    .category-list li:last-child { border-bottom: none; }

    .category-list a {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        color: #555;
        text-decoration: none;
        font-weight: 500;
        transition: padding-left 0.2s, color 0.2s;
    }

    .category-list a:hover {
        padding-left: 5px;
        color: #000;
    }

    .cat-count {
        background-color: #e9ecef;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        color: #666;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .blog-title { font-size: 2rem; }
    }
</style>

<div class="blog-details-container">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">

                <div class="blog-header">
                    <h1 class="blog-title">Explore Border Cafe in Burlington, MA 01803: A Local Gem</h1>
                    <div class="blog-meta">
                        <span><i class="far fa-user"></i> By Admin</span>
                        <span><i class="far fa-calendar-alt"></i> September 22, 2025</span>
                        <span><i class="far fa-comments"></i> No Comments</span>
                    </div>
                </div>

                {{-- Replace with your dynamic image variable --}}
                <img src="{{ asset('images/blog1.jpg') }}" alt="Border Cafe Burlington" class="featured-image">

                <div class="article-content">
                    <p>
                        If you are looking for a cozy spot to enjoy authentic Tex-Mex cuisine while visiting Burlington, MA, look no further than the iconic Border Cafe. Known for its lively atmosphere and delicious food, it has been a favorite for locals and travelers alike. Whether you are stopping by for a quick lunch or a family dinner, the experience never disappoints.
                    </p>

                    <h3>Why Visit Border Cafe?</h3>
                    <p>
                        Located conveniently in the heart of Burlington, this restaurant offers more than just food; it offers an experience. From their freshly made tortilla chips to their signature margaritas, every item on the menu is crafted to perfection.
                    </p>

                    <div class="blockquote-custom">
                        "The best way to experience a city is through its local flavors. Border Cafe brings a unique zest to the Burlington neighborhood that is hard to match."
                    </div>

                    <h3>Menu Highlights</h3>
                    <ul>
                        <li><strong>Fajitas:</strong> Sizzling hot and served with fresh toppings.</li>
                        <li><strong>Cajun Shrimp:</strong> A spicy treat for seafood lovers.</li>
                        <li><strong>Homemade Salsa:</strong> You can't eat just one chip!</li>
                    </ul>

                    <p>
                        Getting there is easy with <strong>Boston Logan Airport Taxi</strong>. We provide reliable drop-off and pick-up services directly to the restaurant, ensuring you don't have to worry about parking or navigating traffic. Book your ride with us today and enjoy a stress-free dining experience.
                    </p>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <strong>Tags:</strong>
                    <a href="#" class="text-muted text-decoration-none me-2">#Burlington</a>
                    <a href="#" class="text-muted text-decoration-none me-2">#Food</a>
                    <a href="#" class="text-muted text-decoration-none">#Travel</a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
