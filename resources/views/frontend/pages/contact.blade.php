@extends('frontend.pages.master')

@section('content')

<style>

    /* Contact Section Styling (Images 3 & 4) */
    body {
        background-color: #ffffff !important;
    }
    .contact-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 50px;
    }

    .contact-info-box {
        background-color: #eab308; /* The specific yellow from the screenshot */
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .contact-info-box h2 {
        color: black;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        font-size: 16px;
        font-style: italic;
    }

    .contact-item i {
        margin-right: 10px;
        margin-top: 5px;
        width: 20px;
    }

    .map-box {
        padding: 0;
        min-height: 550px;
    }

    .map-box iframe {
        width: 100%;
        height: 100%;
        min-height: 350px;
        border: 0;
    }
</style>

<div class="container my-5">

    <div class="row contact-container">
        <div class="col-md-5 contact-info-box">
            <h2>Contact Us</h2>

            <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <span>Phone: +1 857-331-9544</span>
            </div>

            <div class="contact-item">
                <i class="far fa-envelope"></i>
                <span>Email: Booking@Bostonloganairporttaxi.Com</span>
            </div>

            <div class="contact-item">
                <i class="fab fa-whatsapp"></i>
                <span>WhatsApp: 857-331-9544</span>
            </div>
        </div>

        <div class="col-md-7 map-box">
            {{-- Embedding map based on address found in image: "3 Putnam Gardens, Cambridge, MA" --}}
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2948.375336338564!2d-71.11674492346765!3d42.36582533534571!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e3775a2f5592c7%3A0x7b513360216174!2s3%20Putnam%20Gardens%2C%20Cambridge%2C%20MA%2002139%2C%20USA!5e0!3m2!1sen!2sbd!4v1700000000000!5m2!1sen!2sbd"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

</div>

@endsection
