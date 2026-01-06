<style>
    /* --- Section Style --- */
    .content-section {
        background-color: #fff;
        padding: 40px 0;
        border-bottom: 1px solid #eee;
    }

    /* --- Logo Styles --- */
    .rating-logo {
        height: 150px;
        width: auto;
        object-fit: contain;
        max-width: 100%;
        display: block;
    }

    /* --- Mobile Responsive (Max Width 768px) --- */
    @media (max-width: 768px) {
        .content-section {
            padding: 20px 0;
        }

        .rating-logo {
            height: 45px;
        }

        .ratings-row {
            gap: 8px !important;
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
