<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17544692032"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-17544692032');
    </script>
    @php
        $taxiSchema = [
            "@context" => "https://schema.org",
            "@type" => ["TaxiService", "LocalBusiness"],
            "@id" => route('home') . "#taxi",
            "name" => "Boston Logan Airport Taxi",
            "url" => route('home'),
            "logo" => asset('frontend/images/logo.png'),
            "image" => asset('frontend/images/logo.png'),
            "telephone" => "+1857-331-9544",
            "priceRange" => "$$",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "Boston Logan International Airport",
                "addressLocality" => "Boston",
                "addressRegion" => "MA",
                "postalCode" => "02128",
                "addressCountry" => "US"
            ],
            "areaServed" => [
                "@type" => "AdministrativeArea",
                "name" => "Greater Boston Area"
            ],
            "serviceType" => "Airport Taxi Service",
            "openingHoursSpecification" => [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                "opens" => "00:00",
                "closes" => "23:59"
            ]
        ];

        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "@id" => route('home') . "#website",
            "name" => "Boston Logan Airport Taxi",
            "url" => route('home'),
            "publisher" => [
                "@type" => "Organization",
                "name" => "Boston Logan Airport Taxi",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => asset('frontend/images/logo.png')
                ]
            ],
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => route('home') . "?s={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ];
    @endphp

    <script type="application/ld+json">
    {!! json_encode($taxiSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>

    <!-- Website Schema -->
    <script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>
    @if (isset($seo))
        <title>{{ $seo->meta_title }}</title>
        <meta name="description" content="{{ $seo->meta_description }}">
        @else
            <title>Boston Logan Airport Taxi</title>
            <meta name="description" content="Reliable airport taxi service in Boston.">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="canonical" href="{{ url()->current() }}" />
    @include('frontend.css.style')
</head>

<body>

    <div id="validationPopup" class="custom-popup">
        <div class="popup-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="popup-message" id="popupText">Message goes here</div>
    </div>

    @include('frontend.pages.nav')

    @yield('content')

    @include('frontend.pages.footer')
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
