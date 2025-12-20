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
        padding: 40px 0;
    }

    /* --- MAIN CONTENT STYLES --- */
    .blog-header {
        margin-bottom: 20px;
    }

    .blog-title {
        font-size: 2rem;
        font-weight: 800;
        color: #111;
        margin-bottom: 10px;
        line-height: 1.2;
        /* Ensure title wraps too */
        overflow-wrap: break-word;
    }

    .blog-meta {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap; /* Allows meta items to wrap on mobile */
    }

    .blog-meta i {
        color: #fbbf24;
        margin-right: 4px;
    }

    .featured-image {
        width: 100%;
        height: auto;
        max-height: 350px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* --- FIXED CONTENT STYLES --- */
    .article-content {
        font-size: 1.05rem;
        line-height: 1.7;
        color: #444;

        /* THE FIX: Force words to break and wrap */
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
        width: 100%;
        overflow-x: hidden; /* Hide scrollbar if something still persists */
    }

    .article-content p { margin-bottom: 20px; }

    .article-content h2, .article-content h3 {
        font-weight: 700;
        color: #111;
        margin-top: 30px;
        margin-bottom: 15px;
        font-size: 1.4rem;
    }

    .article-content ul { margin-bottom: 20px; padding-left: 20px; }
    .article-content li { margin-bottom: 8px; }
    .article-content img { max-width: 100%; height: auto; border-radius: 5px; margin: 20px 0; }

    /* Fix for <pre> tags if your CMS generates them */
    .article-content pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        background: #f4f4f4;
        padding: 15px;
        border-radius: 5px;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .blog-title { font-size: 1.8rem; }
    }
</style>

<div class="blog-details-container">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="blog-header">
                    <h1 class="blog-title">{{ $blog->title }}</h1>

                    <div class="blog-meta">
                        <span><i class="far fa-user"></i> By Admin</span>
                        <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog->published_at)->format('F d, Y') }}</span>
                    </div>
                </div>

                @if($blog->thumbnail)
                    <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}" class="featured-image">
                @else
                    <img src="{{ asset('images/blog.webp') }}" alt="Default Image" class="featured-image">
                @endif

                <div class="article-content">
                    {!! $blog->content !!}
                </div>

             {{-- Dynamic Tags --}}
                @if($blog->tags)
                <div class="mt-4 pt-3 border-top">
                    <strong class="me-2">Tags:</strong>

                    @php
                        $tags = [];

                        // Case 1: Laravel already cast it to an array (Most likely your case)
                        if (is_array($blog->tags)) {
                            $tags = $blog->tags;
                        }
                        // Case 2: It's a string (JSON or CSV)
                        elseif (is_string($blog->tags)) {
                            $decoded = json_decode($blog->tags, true);

                            // Check if it was valid JSON
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $tags = $decoded;
                            } else {
                                // Fallback: It's a comma-separated string
                                $tags = explode(',', $blog->tags);
                            }
                        }
                    @endphp

                    @foreach($tags as $tag)
                        <a href="#" class="badge bg-light text-dark text-decoration-none border me-1">
                            #{{ trim($tag) }}
                        </a>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
