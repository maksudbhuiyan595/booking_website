@extends('frontend.pages.master')

@section('content')

<style>
    /* --- PAGE STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    .terms-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 15px;
    }

    /* Main Heading */
    .terms-title {
        color: #111;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
    }

    /* Introduction Text */
    .intro-text {
        font-size: 1.05rem;
        line-height: 1.7;
        color: #555;
        margin-bottom: 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .note-text {
        font-size: 0.95rem;
        font-style: italic;
        color: #666;
        margin-bottom: 30px;
        text-align: center;
    }

    /* Section Headings */
    .section-heading {
        color: #111;
        font-weight: 700;
        font-size: 1.4rem;
        margin-top: 35px;
        margin-bottom: 15px;
        border-left: 5px solid #fbbf24; /* Yellow Accent */
        padding-left: 15px;
        line-height: 1.2;
    }

    /* Paragraph Text */
    .terms-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #444;
        margin-bottom: 15px;
    }

    /* Bullet Lists */
    .terms-list {
        list-style: none;
        padding-left: 10px;
        margin-bottom: 20px;
    }

    .terms-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #555;
        line-height: 1.5;
    }

    /* Square Bullets */
    .terms-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 6px;
        height: 6px;
        background-color: #fbbf24; /* Yellow Bullet */
        border-radius: 2px;
    }

    /* Links */
    .terms-text a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
    }
    .terms-text a:hover {
        text-decoration: underline;
    }

    /* Contact Section Styling */
    .contact-info-box {
        background-color: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-top: 40px;
        border: 1px solid #e9ecef;
    }

    .contact-info-box p {
        margin-bottom: 10px;
        font-weight: 500;
    }

    .contact-info-box a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 700;
    }

    .contact-info-box a:hover {
        text-decoration: underline;
    }
</style>

<div class="terms-container">

    <h1 class="terms-title">Terms & Conditions</h1>

    <p class="intro-text">
        You agree that you will use our site and the services under these terms. These rules contribute to a safe, secure, reliable, and transparent experience for all who travel and use the airport.
    </p>
    <p class="note-text">
        <strong>Note:</strong> Read this in full before booking a ride.
    </p>

    <h3 class="section-heading">Service Overview</h3>
    <p class="terms-text">
        <strong>Boston Logan Airport Taxi</strong> offers dependable airport and local transportation services. Booking means you specifically arrange your ride to Logan International Airport or an acceptable destination through your submission.
    </p>

    <h3 class="section-heading">Booking & Confirmation</h3>
    <p class="terms-text">
        Bookings can be done through our <a href="#">website</a>, on the <a href="#">phone</a>, or by <a href="#">email</a>. The appointment is not finalized until you hear from us by <strong>email</strong> or <strong>SMS</strong>. We have the right to decline or cancel any rides due to incorrect information, safety concerns, or policy violations.
    </p>

    <h3 class="section-heading">Fares & Payment</h3>
    <ul class="terms-list">
        <li>We offer <strong>fixed rates</strong> that include a predetermined base charge based on a trip distance or travel time.</li>
        <li>Accepted payment types: Cash, Visa/MasterCard/AmEx/Discover, PayPal, Apple Pay/Google Pay.</li>
        <li>Your reservation is secured by a <strong>$1</strong> booking deposit. You'll pay the full payment after your ride.</li>
    </ul>

    <h3 class="section-heading">Cleanliness & Passenger Conduct</h3>
    <ul class="terms-list">
        <li>The vehicles are completely sanitized after each journey.</li>
        <li>No smoking, drugs, or rowdy behavior.</li>
        <li>You will be held responsible for any damages or extra cleaning. If you break the rules, drivers may deny service.</li>
    </ul>

    <h3 class="section-heading">Cancellation & Refunds</h3>
    <ul class="terms-list">
        <li>Free cancellation is available up to <strong>4 hours</strong> before your pick up.</li>
        <li>Cancellations of 4 hours or no shows will forfeit the deposit or be charged the full fare.</li>
        <li>Refunds are expected to be issued within <strong>3-5 business days</strong>.</li>
    </ul>

    <h3 class="section-heading">Modifications</h3>
    <p class="terms-text">
        Pickup time or location changes can be made up to 4 hours before the scheduled ride. Not all last-minute changes can be accommodated, and fees may apply.
    </p>

    <h3 class="section-heading">Wait Time & Additional Fees</h3>
    <ul class="terms-list">
        <li>Passengers are allowed a <strong>10 minute</strong> grace period, after which drivers wait charging for every minute at a rate of <strong>$1</strong>.</li>
        <li>Additional charges may be added for extra luggage or heavy items, tolls, parking, holiday rates, or late night drives.</li>
    </ul>

    <h3 class="section-heading">Service Area & Limitations</h3>
    <p class="terms-text">
        We cover Boston, the burbs, Cape Cod, and southern New Hampshire. Trips outside the coverage areas may be subject to a surcharge. Please be sure to check our "Area we serve" page for all the details.
    </p>

    <h3 class="section-heading">Liability Disclaimer</h3>
    <p class="terms-text">
        We are not responsible for delays caused by reasons beyond our control, such as weather, road traffic, mechanical, or flight delays. We are liable only for any gross negligence in the fulfillment of our service delivery.
    </p>

    <h3 class="section-heading">Changes to Terms</h3>
    <p class="terms-text">
        We may revise these Terms at any time. We will notify you of any changes by posting the new Privacy Policy on this page, and the new Privacy Policy will take effect when you use our services.
    </p>

    <h3 class="section-heading">Contact Us</h3>
    <div class="contact-info-box">
        <p><i class="fas fa-phone-alt text-warning me-2"></i> <strong>Phone:</strong> <a href="tel:8573319544">857-331-9544</a></p>
        <p><i class="fas fa-envelope text-warning me-2"></i> <strong>Email:</strong> <a href="mailto:booking@bostonloganairporttaxi.com">booking@bostonloganairporttaxi.com</a></p>
        <p class="mt-3 small text-muted">In the following, you agree to these Terms & Conditions by arranging or using our services.</p>
    </div>

</div>

@endsection
