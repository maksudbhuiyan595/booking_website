<style>
    /* --- Mobile Responsive (Max Width 768px) --- */
@media (max-width: 768px) {
    .content-section {
        padding: 15px 0; /* প্যাডিং আরও কমানো হয়েছে */
    }

    .content-section .container {
        padding-left: 5px !important;
        padding-right: 5px !important;
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
    }

    .ratings-row {
        display: flex !important;
        flex-wrap: nowrap !important; /* এক লাইনে রাখার জন্য */
        justify-content: space-around !important;
        gap: 5px !important;
    }

    .ratings-row > div {
        flex: 1;
        max-width: 25%; /* ৪টি লোগোর জন্য ২৫% জায়গা */
    }

    .rating-logo {
        /* নির্দিষ্ট হাইট দিলে ইমেজ চ্যাপ্টা হয়ে যেতে পারে, তাই অটো হাইট ভালো */
        height: auto !important;
        max-height: 60px !important; /* মোবাইলে ৬০-৭০px এর বেশি দিলে দেখতে খারাপ লাগে */
        width: 100% !important;
        object-fit: contain;
    }
}

/* অতি ক্ষুদ্র ডিভাইসের জন্য (যেমনঃ iPhone SE) */
@media (max-width: 400px) {
    .rating-logo {
        max-height: 50px !important; /* স্ক্রিন খুব ছোট হলে হাইট কিছুটা কমিয়ে আনা */
    }
}
</style>

<section class="content-section">
    <div class="container">

        <div class="d-flex flex-nowrap justify-content-between align-items-center ratings-row">

            {{-- 1. Google --}}
            <div class="text-center">
                <a href="https://www.google.com/maps/place//data=!4m3!3m2!1s0x6d2fa2315524a15:0xa7d62692b0494a24!12e1?source=g.page.m.ia._&laa=nmx-review-solicitation-ia2" target="_blank">
                    <img src="{{ asset('images/google.png') }}"
                         alt="Google Rating"
                         class="rating-logo">

                </a>
            </div>

            {{-- 2. Tripadvisor --}}
            <div class="text-center">
                <a href="https://www.tripadvisor.com/Attraction_Review-g60745-d33371741-Reviews-Boston_Logan_Airport_Taxi-Boston_Massachusetts.html" target="_blank">
                    <img src="{{ asset('images/trip.png') }}"
                         alt="Tripadvisor"
                         class="rating-logo">
                </a>
            </div>

            {{-- 3. Trustpilot --}}
            <div class="text-center">
                <a href="https://trustpilot.com/review/bostonloganairporttaxi.com" target="_blank">
                    <img src="{{ asset('images/Trustpilot.webp') }}"
                         alt="Trustpilot"
                         class="rating-logo">
                </a>
            </div>

            {{-- 4. Yelp --}}
            <div class="text-center">
                <a href="https://biz.yelp.com/r2r/qBKa9HpNhb7tt4h8bCsoqA" target="_blank">
                    <img src="{{ asset('images/yelp.png') }}"
                         alt="Yelp"
                         class="rating-logo">
                </a>
            </div>

        </div>
    </div>
</section>
