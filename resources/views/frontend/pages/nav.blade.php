<style>
    /* Mobile Phone Button Style */
    .mobile-phone-btn {
        background-color: #ffc107; /* Yellow background */
        color: #000;
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        border: none;
    }

    .mobile-phone-btn:hover {
        background-color: #e0a800;
        color: #000;
    }
    .navbar-custom {
        background-color: rgba(0, 0, 0, 0.95);
        padding: 0px 0;
        border-bottom: 1px solid #333;
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 400px) {
        .navbar-brand img {
            height: 45px;
        }
        .mobile-phone-btn {
            font-size: 12px;
            padding: 6px 8px;
        }
    }
</style>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img
                src="{{ !empty($seeting->site_logo)
                        ? asset('storage/' . $seeting->site_logo)
                        : asset('images/logo.png') }}"
                alt="Boston Logan Airport Taxi"
                height="80">
        </a>
        <a href="tel:+18573319544" class="mobile-phone-btn d-lg-none mx-auto">
            <i class="fas fa-phone-alt"></i> +1 857-331-9544
        </a>
        <button class="navbar-toggler bg-warning border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="mainNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('child.seat') }}">Child Seat</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('minivan') }}">Minivan/SUV</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('area.we.serve') }}">Area We Serve</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
        <div class="d-none d-lg-block">
            <a href="tel:+18573319544" class="btn-phone">
                <i class="fas fa-phone-alt me-2"></i> +1857-331-9544
            </a>
        </div>

    </div>
</nav>

