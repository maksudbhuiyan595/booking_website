@extends('frontend.pages.master')

@section('content')

<style>
    /* --- PAGE STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    .policy-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 15px;
    }

    /* Main Heading */
    .policy-title {
        color: #111;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 40px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Section Headings */
    .policy-section-title {
        color: #111;
        font-weight: 700;
        font-size: 1.4rem;
        margin-top: 35px;
        margin-bottom: 15px;
        border-left: 5px solid #fbbf24; /* Taxi Yellow Accent */
        padding-left: 15px;
        line-height: 1.2;
    }

    /* Paragraph Text */
    .policy-text {
        font-size: 1rem;
        line-height: 1.7;
        color: #555;
        margin-bottom: 15px;
    }

    /* List Styling */
    .policy-list {
        list-style: none;
        padding-left: 5px;
        margin-bottom: 20px;
    }

    .policy-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
        font-size: 1rem;
        color: #444;
        line-height: 1.5;
    }

    /* Custom Bullet Points */
    .policy-list li::before {
        content: '\f054'; /* FontAwesome Chevron Right */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 4px;
        color: #fbbf24; /* Taxi Yellow */
        font-size: 0.8rem;
    }

    /* Highlighted Text */
    .highlight {
        font-weight: 700;
        color: #000;
    }

    /* Contact Box at Bottom */
    .contact-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 30px;
        margin-top: 50px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .contact-box a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: bold;
    }

    .contact-box a:hover {
        text-decoration: underline;
    }
</style>

<div class="policy-container">

    <h1 class="policy-title">Payment Policy</h1>

    <p class="policy-text">
        We want your ride with <span class="highlight">Boston Logan Airport Taxi</span> to be as simple as possible, including the payment process. Whether you like to keep cash on hand or prefer to use your card, we have a safe, flexible solution to fit your comfort level. Additionally, we offer a <strong>10% discount for cash transactions</strong>, as opposed to credit card transactions, which often incur processing fees.
    </p>

    <h3 class="policy-section-title">Accepted Payment Methods</h3>
    <p class="policy-text">We currently accept:</p>
    <ul class="policy-list">
        <li><span class="highlight">Cash</span> (preferred for discount eligibility)</li>
        <li>All major credit cards (Visa, MasterCard, American Express, Discover)</li>
        <li>PayPal for prepaid bookings</li>
        <li>Apple Pay & Google Pay (depending on driver availability)</li>
    </ul>
    <p class="policy-text small text-muted fst-italic">All transactions are handled securely in USD.</p>

    <h3 class="policy-section-title">Secure Transactions</h3>
    <p class="policy-text">
        Payments made online are processed via reliable gateways such as <strong>Authorize.net</strong> and <strong>PayPal</strong>. 256-bit SSL encryption is used to protect your financial information. We don’t retain any credit card information.
    </p>

    <h3 class="policy-section-title">Deposits & Balances</h3>
    <ul class="policy-list">
        <li><span class="highlight">$1.00</span> is deposited at the time of booking to reserve your ride.</li>
        <li>The remaining balance is settled after the journey with the driver.</li>
    </ul>

    <h3 class="policy-section-title">Cash Discount</h3>
    <ul class="policy-list">
        <li>Pay in cash at the end of the trip and receive a <span class="highlight">10% discount!</span></li>
        <li>There are no additional fees or card surcharges on cash fares.</li>
    </ul>

    <h3 class="policy-section-title">Fare Transparency</h3>
    <p class="policy-text">Your charge may be billed at mileage or hourly rates.</p>
    <ul class="policy-list">
        <li>Bridge tolls, airport parking (if required), gratuity, and late night/holiday surcharges (if required).</li>
        <li>Additional charges must be paid directly to the driver in cash.</li>
    </ul>

    <h3 class="policy-section-title">International Card Payments</h3>
    <p class="policy-text">
        Foreign cards are also accepted, but some banks may impose currency conversion fees. So, you should check with your provider.
    </p>

    <h3 class="policy-section-title">Cancellations & Refunds</h3>
    <ul class="policy-list">
        <li>Free cancellation is available up to <span class="highlight">4 hours</span> before pickup.</li>
        <li>There are no refunds for late cancellations (less than 4 hours before pickup).</li>
        <li>The full fare will be charged for passengers who do not show up.</li>
        <li>If your refund is approved, it will be processed with the original payment method within 3-5 business days.</li>
    </ul>

    <div class="contact-box">
        <h3 class="h4 fw-bold mb-3">Need Help?</h3>
        <p class="mb-2 text-muted">For any queries, contact us at:</p>
        <p class="mb-1 fs-5"><i class="fas fa-phone-alt me-2 text-warning"></i> Phone: <a href="tel:+18573319544">857-331-9544</a></p>
        <p class="fs-5"><i class="fas fa-envelope me-2 text-warning"></i> Email: <a href="mailto:booking@bostonloganairporttaxi.com">booking@bostonloganairporttaxi.com</a></p>
    </div>

</div>

@endsection
