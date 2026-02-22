<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', 'Home')</title>
    <meta name="description" content="@yield('meta_description', 'Boston Logan Airport Taxi')">
    <meta name='robots' content='index, follow' />
    <meta name="google-site-verification" content="7KCLc8w_vDk2W_R7z-hXAcRRscV47KSUv2V0lislJgQ" />
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Boston Logan Airport Taxi Cab with Minivan and Child Seat Option">
    <meta property="og:description" content="Boston Logan Airport Taxi offers 24/7 professional airport transportation with licensed drivers, sanitized vehicles, child seats, and flat-rate pricing.">
    <meta property="og:url" content="{{ url()->current() . '/' }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:site_name" content="Boston Logan Airport Taxi">
    <!-- Facebook -->
    <meta property="og:see_also" content="https://www.facebook.com/bostonloganairporttaxi1">
    <!-- Twitter (X) Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Boston Logan Airport Taxi - 24/7 Airport Transportation">
    <meta name="twitter:description" content="Reliable and professional airport taxi service in Boston with child seat option and flat rates.">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    <meta name="twitter:site" content="@blairporttaxi">
    <!-- Pinterest Verification (Optional if needed) -->
    <meta name="pinterest" content="https://www.pinterest.com/blairporttaxi/">
    @yield(section: 'meta')
    <link rel="canonical" href="{{ rtrim(request()->url(), '/') . '/' }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W4NMSCF4');</script>
    <!-- End Google Tag Manager -->

    @yield('schema')
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
