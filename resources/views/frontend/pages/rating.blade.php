<style>
    /* --- Section Style --- */
    .content-section {
        background-color: #fff;
        padding: 40px 0;
        border-bottom: 1px solid #eee;
        overflow: hidden; /* Side overflow prevent korar jonno */
    }

    /* --- Logo Styles --- */
    .rating-logo {
        height: 150px;
        width: auto;
        object-fit: contain;
        max-width: 100%;
        display: block;
        margin: 0 auto;
    }

    /* --- Mobile Responsive (Max Width 768px) --- */
    @media (max-width: 768px) {
        .content-section {
            padding: 25px 0;
        }

        /* Container er default padding komanor jonno */
        .content-section .container {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
        }

        .rating-logo {
            /* Height bariye deya hoyeche jate icon boro dekhay */
            height: 80px !important;
        }

        .ratings-row {
            gap: 5px !important;
            display: flex !important;
            flex-nowrap: nowrap !important;
            justify-content: space-around !important;
        }

        .ratings-row > div {
            flex: 1;
            padding: 0 2px;
        }
    }

    /* Choto mobile (iPhone SE style) er jonno */
    @media (max-width: 400px) {
        .rating-logo {
            height: 55px !important;
        }
    }
</style>

<section class="content-section">
    <div class="container">

        <div class="d-flex flex-nowrap justify-content-between align-items-center ratings-row">

            {{-- 1. Google --}}
            <div class="text-center">
                <img src="{{ asset('images/Google-Rating-1.webp') }}"
                     alt="Google Rating"
                     class="rating-logo">
            </div>

            {{-- 2. Tripadvisor --}}
            <div class="text-center">
                <a href="https://www.tripadvisor.com/Attraction_Review-g60745-d33371741-Reviews-Boston_Logan_Airport_Taxi-Boston_Massachusetts.html" target="_blank">
                    <img src="{{ asset('images/Tripadvisor.webp') }}"
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
                    <img src="{{ asset('images/Flux_Dev_highresolution_stock_photo_of_Create_an_image_with_th_1.webp') }}"
                         alt="Yelp"
                         class="rating-logo">
                </a>
            </div>

        </div>
    </div>
</section>
