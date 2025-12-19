@extends('frontend.pages.master')

@section('content')

<style>
    /* --- PAGE STYLES --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff !important;
        color: #333;
    }

    .privacy-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 15px;
    }

    /* Main Heading */
    .privacy-title {
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
        margin-bottom: 30px;
        text-align: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
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
    .policy-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #444;
        margin-bottom: 15px;
    }

    /* Bullet Lists */
    .policy-list {
        list-style: none;
        padding-left: 10px;
        margin-bottom: 20px;
    }

    .policy-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #555;
        line-height: 1.5;
    }

    .policy-list li strong {
        color: #000;
        font-weight: 600;
    }

    /* Square Bullets */
    .policy-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 6px;
        height: 6px;
        background-color: #fbbf24; /* Yellow Bullet */
        border-radius: 2px;
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

<div class="privacy-container">

    <h1 class="privacy-title">Privacy Policy</h1>

    <p class="intro-text">
        Your privacy matters to us. <strong>Boston Logan Airport Taxi</strong>, we safeguard the information you share. We collect only the data that is needed to serve you better and use industry-standard tools to keep it secure.
    </p>

    <h3 class="section-heading">Information We Collect</h3>
    <ul class="policy-list">
        <li><strong>Personal Information:</strong> Your name, email address, phone number, flights, and pick up and drop off details used during the booking process.</li>
        <li><strong>Payment Information:</strong> Your card details or your PayPal account are used to make a deposit or a prepayment. Processed securely and never stored on our servers.</li>
        <li><strong>Usage Data:</strong> IP addresses, browser details, and information on how you interact with our site to improve your experience on our site.</li>
        <li><strong>Cookies:</strong> Tiny files stored in your web browser to make users' online browsing experience more efficient and your preferences.</li>
    </ul>

    <h3 class="section-heading">How We Use Your Data</h3>
    <ul class="policy-list">
        <li>To verify bookings and to process payments.</li>
        <li>To deliver ride-related notifications (via email/SMS) such as confirmation or warning messages.</li>
        <li>To provide customer inquiries, refunds, or support requests.</li>
        <li>For administrative purposes, such as site functionality, to enforce the site's terms and policy, and to prevent fraudulent activity.</li>
    </ul>

    <h3 class="section-heading">How We Share Your Data</h3>
    <p class="policy-text">
        We may share your personal data with others to whom we license or sublicense it, including licensors and sub-licensors of the MSP products, subject to the limitations of this Privacy Policy. We may disclose your information to:
    </p>
    <ul class="policy-list">
        <li>Service providers (such as, for example, payment processors like Stripe, PayPal, and SMS gateways).</li>
        <li>Applicable Authorities, if compelled by law (for example, for safety, law enforcement, or legal reasons).</li>
    </ul>

    <h3 class="section-heading">Your Data Rights</h3>
    <p class="policy-text">Under U.S. privacy laws, you have the following rights:</p>
    <ul class="policy-list">
        <li>Ask to access your personal data.</li>
        <li>Correct or update inaccurate information.</li>
        <li>Ask us to erase your personal information (subject to legal obligations).</li>
        <li>Opt-out or limit some uses (for example, for marketing purposes).</li>
    </ul>

    <h3 class="section-heading">How We Protect Your Data</h3>
    <p class="policy-text">We use secure technical measures:</p>
    <ul class="policy-list">
        <li>SSL/TLS encryption (256-bit) on the entire website.</li>
        <li>Reliable payment gateways (Stripe, PayPal) for secure transactions.</li>
        <li>We perform periodic reviews and updates to prevent unauthorized access.</li>
    </ul>

    <h3 class="section-heading">Data Retention</h3>
    <p class="policy-text">We keep data for as long as it is required:</p>
    <ul class="policy-list">
        <li>Further, the airline may keep booking and payment records for the previous 7 years (for tax/accounting reasons).</li>
        <li>Logs and Analytics (1 year to improve your experience).</li>
        <li>Data is securely deleted or anonymized after the retention period.</li>
    </ul>

    <h3 class="section-heading">Cookies & Tracking Technologies</h3>
    <p class="policy-text">We use:</p>
    <ul class="policy-list">
        <li>Essential cookies for booking and security reasons.</li>
        <li>Performance cookies are used to analyze site usage and to enhance the site's functionality.</li>
        <li>You can also opt out of non-essential cookies by using your browser's settings, but this may disable some features on the site.</li>
    </ul>

    <h3 class="section-heading">Children's Privacy</h3>
    <p class="policy-text">
        We cater to adults (18+) only. We do not knowingly gather personal information from children under 13.
    </p>

    <h3 class="section-heading">Changes to this Policy</h3>
    <p class="policy-text">
        We may revise this Privacy Policy from time to time. We put the date at the top and will notify you of significant changes. If you continue to use it after updates, you have accepted the changes.
    </p>

    <h3 class="section-heading">Questions & Contact</h3>
    <div class="contact-info-box">
        <p>For privacy-related questions or concerns:</p>
        <p><i class="fas fa-phone-alt text-warning me-2"></i> <strong>Phone:</strong> <a href="tel:8573319544">857-331-9544</a></p>
        <p><i class="fas fa-envelope text-warning me-2"></i> <strong>Email:</strong> <a href="mailto:booking@bostonloganairporttaxi.com">booking@bostonloganairporttaxi.com</a></p>
        <p class="mt-3 small text-muted">Your confidence is our utmost concern. Great travel begins with a sense of security. Thanks for choosing <strong>Boston Logan Airport Taxi</strong>.</p>
    </div>

</div>

@endsection
