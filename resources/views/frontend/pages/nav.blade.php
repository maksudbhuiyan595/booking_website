 <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Boston Logan Airport Taxi" height="65">
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
                <div class="d-lg-none mt-3 text-center">
                    <a href="tel:+18573319544" class="btn-phone w-100 justify-content-center">
                        <i class="fas fa-phone-alt me-2"></i> +1857-331-9544
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-block">
                <a href="tel:+18573319544" class="btn-phone">
                    <i class="fas fa-phone-alt me-2"></i> +1857-331-9544
                </a>
            </div>
        </div>
    </nav>
