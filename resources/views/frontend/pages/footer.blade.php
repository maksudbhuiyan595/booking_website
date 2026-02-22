<footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">About</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('term.conditions') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('payment.policy') }}">Payment Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Helpful Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('child.seat') }}">Child Seat</a></li>
                        <li><a href="{{ route('minivan') }}">Minivan/SUV</a></li>
                        <li><a href="{{ route('area.we.serve') }}">Area we Serve</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Contact Information</h5>
                    <ul class="footer-links contact-info">
                        <li><i class="fas fa-map-marker-alt contact-icon"></i> <div>{{ $settings->company_address }}</div></li>
                        <li><i class="fas fa-phone-alt contact-icon"></i> <div>{{ $settings->company_phone }}</div></li>
                        <li><i class="fas fa-envelope contact-icon"></i> <div>{{ $settings->company_email }}</div></li>

                    </ul>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/bostonloganairporttaxi1" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/blairporttaxi" class="social-btn"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/bostonloganairporttaxi/" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.pinterest.com/blairporttaxi/" class="social-btn"><i class="fab fa-pinterest-p"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright-text">
                Copyright © 2025. Logan Airport Taxi All Rights Reserved | Designed by Virtual Click USA
            </div>
        </div>
    </footer>
