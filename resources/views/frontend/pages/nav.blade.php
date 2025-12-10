     <style>
        /* --- 1. VARIABLES & RESET --- */
        :root {
            --bg-dark: #0f172a;       /* Deep Navy */
            --bg-card: #1e293b;       /* Lighter Navy for cards */
            --bg-footer: #0b1120;     /* Darker Navy */
            --accent-gold: #d4af37;   /* Gold */
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        /* --- 2. NAVBAR --- */
        .navbar {
            padding: 10px 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            padding: 5px 0;
            background: rgba(15, 23, 42, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        /* LOGO FIX */
        .navbar-brand {
            padding: 0 !important;
            display: flex;
            align-items: center;
            margin-right: 2rem;
        }
        .navbar-brand img {
            height: 50px; /* Adjust height here */
            width: auto;
            object-fit: contain;
            display: block;
        }

        /* Links */
        .nav-link {
            color: var(--text-light) !important;
            font-size: 0.95rem;
            font-weight: 500;
            margin-left: 1.5rem;
            position: relative;
            transition: color 0.3s;
        }
        .nav-link:hover { color: var(--accent-gold) !important; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
            background-color: var(--accent-gold); transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }

        .navbar-toggler { border: none; color: var(--text-light); }
        .navbar-toggler:focus { box-shadow: none; }

        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: var(--bg-dark); padding: 20px; margin-top: 15px;
                border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);
            }
            .nav-link { margin-left: 0; margin-bottom: 10px; }
        }

        /* --- 3. SECTIONS UTILITIES --- */
        /* Standard Section Padding */
        .content-section {
            padding: 100px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Hero Specifics */
        .hero-section {
            /* Placeholder background - replace URL with your image */
            background: linear-gradient(rgba(15, 23, 42, 0.7), var(--bg-dark)),
                        url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 80vh; /* Takes 80% of screen height */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 80px;
        }

        /* Placeholder Border (To help you see the section) */
        .dummy-box {
            border: 2px dashed rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.02);
        }

        /* --- 4. FOOTER --- */
        footer {
            background-color: var(--bg-footer);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: auto;
        }
        .footer-link { text-decoration: none; color: var(--text-muted); display: block; margin-bottom: 10px; transition: 0.3s; }
        .footer-link:hover { color: var(--accent-gold); padding-left: 5px; }
        .social-icon {
            width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; color: var(--text-light);
            text-decoration: none; margin-right: 10px; transition: 0.3s;
        }
        .social-icon:hover { border-color: var(--accent-gold); color: var(--accent-gold); transform: translateY(-3px); }
    </style>
 <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/logo.png" alt="Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#premiumNav">
                <i class="fas fa-bars" style="font-size: 1.5rem;"></i>
            </button>

            <div class="collapse navbar-collapse" id="premiumNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#">Collections</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Atelier</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Journal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
