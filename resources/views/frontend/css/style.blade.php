
<style>
        :root {
            --primary-bg: #05110e;
            --form-bg: #112621;
            --form-border: #2a453d;
            --btn-green: #008f58;
            --btn-green-hover: #007a4d;
            --btn-shadow: #003d26;
            --input-bg: #ffffff;
            --text-white: #ffffff;
            --text-muted: #b0b0b0;
            --header-yellow: #ffc800;
            --header-yellow-hover: #e0b000;
            --footer-bg: #000000;
            --light-section-bg: #f4f7f6;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-white);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 66px;
        }

        /* --- NAVBAR --- */
        .navbar-custom {
            background-color: rgba(0, 0, 0, 0.95);
            padding: 8px 0;
            border-bottom: 1px solid #333;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .nav-link-custom {
            color: var(--header-yellow) !important;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 8px;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover { color: #fff !important; transform: translateY(-1px); }

        .btn-phone {
            background: linear-gradient(45deg, var(--header-yellow), #ffd54f);
            color: #000;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 4px;
            padding: 5px 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(255, 200, 0, 0.3);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-phone:hover {
            background: var(--header-yellow-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 200, 0, 0.4);
        }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            flex: 1;
            padding-top: 30px;
            padding-bottom: 60px; /* Extra padding for form overlap */
            background-color: #ffffff;
            z-index: 1;
        }

        .main-title {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1.1;
            text-transform: uppercase;
            margin-bottom: 30px;
            color: #000000;
            letter-spacing: -1px;
            text-align: center;
        }

        /* --- FLOATING RESERVATION CARD --- */
        .reservation-card {
            background-color: var(--form-bg);
            border: 1px solid var(--form-border);
            border-radius: 8px;
            padding: 31px 15px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* CRITICAL FOR "UPOR DIA JABE" (FLOATING) EFFECT */
            z-index: 100;
            transition: all 0.3s ease;
        }

        .form-header h3 { font-weight: 800; text-transform: uppercase; font-size: 1.1rem; text-align: center; margin-bottom: 2px; color: white; letter-spacing: 0.5px; }
        .form-header p { font-size: 0.75rem; text-align: center; color: #ccc; margin-bottom: 10px; font-weight: 300; }

        .form-control, .form-select {
            background-color: var(--input-bg) !important; border: 1px solid #ccc; border-radius: 4px;
            color: #333 !important; font-size: 0.85rem !important; padding: 6px 10px !important;
            margin-bottom: 0 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05); font-weight: 500; height: auto;
        }
        .input-group { margin-bottom: 0; }
        .input-group-text { padding: 6px 8px !important; font-size: 0.85rem !important; background-color: #f0f0f0; border: 1px solid #ccc; color: #555; }
        .form-control:focus, .form-select:focus { background-color: #fff !important; border-color: var(--btn-green); box-shadow: 0 0 0 3px rgba(0, 143, 88, 0.2); }

        /* --- TRIP TYPES --- */
        .trip-type-container { display: flex; justify-content: space-between; gap: 5px; margin: 8px 0 10px 0; }
        .trip-option { flex: 1; position: relative; }
        .trip-option input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .trip-card {
            display: flex; flex-direction: row; align-items: center; justify-content: center;
            background-color: rgba(255, 255, 255, 0.05); border: 1px solid #555; border-radius: 4px;
            padding: 8px 2px; cursor: pointer; transition: all 0.2s ease; color: #ccc; height: 100%; text-align: center;
        }
        .trip-card i { font-size: 0.9rem; margin-right: 6px; margin-bottom: 0; color: #888; }
        .trip-card span { font-size: 0.75rem; font-weight: 600; line-height: 1; white-space: nowrap; }
        .trip-card:hover { border-color: #888; background-color: rgba(255, 255, 255, 0.1); }
        .trip-option input[type="radio"]:checked + .trip-card { background-color: var(--header-yellow); border-color: var(--header-yellow); color: #000; box-shadow: 0 0 10px rgba(255, 200, 0, 0.3); }
        .trip-option input[type="radio"]:checked + .trip-card i { color: #000; }

        /* --- EXTRAS --- */
        .extras-toggle {
            cursor: pointer; font-weight: 700; color: var(--header-yellow); font-size: 0.85rem;
            display: flex; align-items: center; margin-bottom: 8px; padding-top: 5px; user-select: none; transition: color 0.3s;
        }
        .extras-toggle:hover { color: #fff; }
        .extras-toggle i { margin-right: 6px; font-size: 0.9rem; transition: transform 0.3s ease; }
        .extras-toggle.active i { transform: rotate(45deg); }

        #extrasSection { max-height: 0; overflow: hidden; opacity: 0; transition: max-height 0.5s ease, opacity 0.3s ease; }
        #extrasSection.open { max-height: 800px; opacity: 1; margin-bottom: 10px; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px; }

        .extra-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 4px; }
        .extra-label { font-size: 0.8rem; color: #fff; font-weight: 500; }
        .extra-price-tag { font-weight: 700; color: var(--header-yellow); }
        .extra-select-box { background-color: #fff; border: 1px solid #ccc; padding: 0px 5px; width: 45px; font-size: 0.8rem; height: 24px; cursor: pointer; margin-right: 8px; border-radius: 4px; font-weight: bold; }
        .total-price-display { font-size: 0.9rem; font-weight: 700; color: #fff; min-width: 30px; text-align: right; }

        .btn-get-fare {
            background-color: var(--btn-green); color: white; font-weight: 600; width: 100%;
            padding: 10px; font-size: 1rem; border-radius: 6px; border: none;
            box-shadow: 0 4px 0 var(--btn-shadow); transition: all 0.2s; margin-top: 10px;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btn-get-fare:hover { background-color: var(--btn-green-hover); color: white; }

        .footer-note { text-align: center; font-size: 0.7rem; color: #aaa; margin-top: 10px; line-height: 1.3; font-weight: 300; margin-bottom: 0; }
        .mini-label { font-size: 0.65rem; color: #aaa; margin-bottom: 2px; display: block; font-weight: 500; text-transform: uppercase; }

        /* --- DESKTOP LAYOUT (FLOATING EFFECT) --- */
        .hero-row {
            position: relative; /* Anchor for absolute positioning */
        }

        .description-column {
            display: flex;
            flex-direction: column;
            /* Add padding to the left so it doesn't touch the form area */
            padding-left: 20px;
        }

        .description-text {
            font-size: 0.95rem;
            color: #333333;
            margin-bottom: 15px;
            line-height: 1.5;
            margin-top: 0;
        }

        .hero-img-wrapper {
            width: 100%;
            height: 450px;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .hero-img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }

        /* --- DESKTOP SPECIFIC: MAKE FORM FLOAT --- */
        @media (min-width: 992px) {
            .form-column {
                position: relative;
                /* Do not set height here, let it be 0 effectively for flow, or just use absolute child */
            }

            .reservation-card {
                position: absolute; /* FLOATING! */
                top: 0;
                left: 12px; /* Adjust based on Bootstrap gutter */
                right: 12px;
                /* This ensures it expands ON TOP of content below */
            }

            /* Ensure the hero section has enough height for the closed form + image */
            .hero-section {
                /* Min height ensures the ratings section isn't covered by the CLOSED form */
                min-height: 600px;
            }
        }

        @media (max-width: 991px) {
            body { padding-top: 60px; }
            .hero-section { padding-top: 10px; min-height: auto; }
            .main-title { font-size: 1.8rem; margin-bottom: 10px; }
            .reservation-card { position: relative; width: 100%; margin-bottom: 30px; }
            .description-column { padding-left: 0; margin-top: 0; }
            .trip-card { padding: 10px 2px; }
            .hero-img-wrapper { height: 250px; }
        }

        /* --- SECTIONS --- */
        .content-section { background-color: #ffffff; color: #333; padding: 50px 0; }
        .bg-light { background-color: var(--light-section-bg) !important; }
        .section-title { font-weight: 800; margin-bottom: 20px; color: #111; text-transform: uppercase; letter-spacing: 0.5px; }
        .service-img { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .why-choose-list { list-style: none; padding-left: 0; }
        .why-choose-list li { position: relative; padding-left: 25px; margin-bottom: 10px; font-size: 0.95rem; color: #444; }
        .why-choose-list li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: var(--btn-green); position: absolute; left: 0; }
        .testimonial-box { background: #fff; padding: 25px; border-radius: 8px; height: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 4px solid var(--header-yellow); }
        .testimonial-text { font-style: italic; font-size: 0.9rem; color: #555; line-height: 1.6; }
        .customer-name { font-weight: 700; margin-top: 15px; color: #111; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
        .city-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; margin-top: 30px; }
        .city-tag { background-color: #222; color: #fff; padding: 12px 15px; border-radius: 4px; font-weight: 500; font-size: 0.85rem; display: flex; align-items: center; text-decoration: none; transition: all 0.3s; border-left: 4px solid var(--header-yellow); }
        .city-tag:hover { background-color: #000; color: var(--header-yellow); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .city-tag i { margin-right: 10px; color: var(--header-yellow); }
        .ratings-row img { max-height: 140px; margin: 0 10px; }
        .ratings-row img:hover { filter: grayscale(0%); opacity: 1; }

        /* Accordion & Footer */
        .accordion-item { border: 1px solid #e0e0e0; margin-bottom: 8px; border-radius: 4px !important; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .accordion-button { background-color: #fff; color: #222; font-weight: 600; padding-left: 3rem; position: relative; box-shadow: none !important; font-size: 1rem; padding-top: 12px; padding-bottom: 12px; }
        .accordion-button:not(.collapsed) { background-color: #f9fdfb; color: var(--btn-green); }
        .accordion-button::after { display: none; }
        .accordion-button::before { content: '+'; position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.2rem; font-weight: 300; color: var(--header-yellow); border: 1px solid #ddd; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s; }
        .accordion-button:not(.collapsed)::before { content: '-'; background-color: var(--btn-green); color: #fff; border-color: var(--btn-green); }
        .accordion-body { padding-left: 3rem; color: #555; line-height: 1.5; font-size: 0.95rem; }

        .blog-card { border: none; border-radius: 8px; background: #fff; overflow: hidden; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s; }
        .blog-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .blog-img-wrapper { position: relative; height: 180px; width: 100%; }
        .blog-img { width: 100%; height: 100%; object-fit: cover; }
        .blog-badge-overlay { position: absolute; top: 10px; right: 10px; background-color: var(--btn-green); color: white; padding: 4px 10px; font-size: 0.7rem; font-weight: 700; border-radius: 4px; text-transform: uppercase; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .blog-avatar-overlay { position: absolute; bottom: -20px; left: 20px; width: 40px; height: 40px; background-color: #ddd; border: 3px solid #fff; border-radius: 50%; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .blog-content { padding: 25px 20px 20px; }
        .blog-content h5 { font-size: 1rem; line-height: 1.4; margin-bottom: 8px; }
        .read-more-link { color: var(--btn-green); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; text-decoration: none; letter-spacing: 0.5px; }
        .read-more-link:hover { color: #005a38; text-decoration: underline; }

        .footer-section { background-color: var(--footer-bg); color: #aaa; padding-top: 40px; padding-bottom: 20px; border-top: 1px solid #222; }
        .footer-title { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: #fff; text-transform: uppercase; letter-spacing: 1px; border-left: 3px solid var(--header-yellow); padding-left: 10px; }
        .footer-links a { color: #aaa; transition: all 0.2s; text-decoration: none; font-size: 0.9rem; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a:hover { color: var(--header-yellow); }
        .contact-icon { color: var(--header-yellow); }
        .contact-info li { display: flex; gap: 10px; margin-bottom: 12px; font-size: 0.9rem; }
        .social-icons { display: flex; gap: 10px; margin-top: 15px; }
        .social-btn { border: 1px solid #333; color: #aaa; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; transition: all 0.3s; font-size: 0.9rem; }
        .social-btn:hover { background-color: var(--header-yellow); border-color: var(--header-yellow); color: #000; }
        .copyright-text { color: #555; font-size: 0.8rem; text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #222; }

        /* Popup */
        .custom-popup { position: fixed; top: 80px; left: 50%; transform: translateX(-50%) translateY(-20px); background-color: white; color: #333; padding: 10px 20px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); z-index: 9999; display: flex; align-items: center; gap: 15px; opacity: 0; visibility: hidden; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55); min-width: 300px; border-left: 6px solid; }
        .custom-popup.show { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .custom-popup.error { border-color: #dc3545; } .custom-popup.error i { color: #dc3545; }
        .custom-popup.success { border-color: #198754; } .custom-popup.success i { color: #198754; }
        .popup-icon { font-size: 1.2rem; } .popup-message { font-size: 0.9rem; font-weight: 600; }
    </style>
