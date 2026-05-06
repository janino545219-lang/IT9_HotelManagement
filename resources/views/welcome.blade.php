<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hotel Management') }} - Welcome</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600|playfair-display:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        :root {
            --primary: #d4af37; /* Gold */
            --primary-dark: #b5952f;
            --dark: #0f1115;
            --light: #fdfdfd;
            --glass-bg: rgba(15, 17, 21, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'inter', sans-serif;
            background-color: var(--dark);
            color: var(--light);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .logo {
            font-family: 'playfair display', serif;
        }

        /* Loading Animation */
        @keyframes fadeOut {
            from { opacity: 1; visibility: visible; }
            to { opacity: 0; visibility: hidden; }
        }

        .loader {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--dark);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeOut 1s ease 0.6s forwards;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 3px solid rgba(212, 175, 55, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Layout styling */
        .wrapper {
            position: relative;
            min-height: 100vh;
        }

        /* Background Image */
        .hero-bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1542314831-c53cd3816002?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            transform: scale(1.05); /* Slight scale for parallax trick / slow zoom optionally */
            animation: heroZoom 20s ease infinite alternate;
        }

        @keyframes heroZoom {
            from { transform: scale(1.02); }
            to { transform: scale(1.08); }
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                145deg, 
                rgba(15, 17, 21, 0.9) 0%, 
                rgba(15, 17, 21, 0.4) 50%, 
                rgba(15, 17, 21, 0.85) 100%
            );
            z-index: -1;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2.5rem 6%;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 100;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--light);
            text-transform: uppercase;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideDown 1s ease 0.8s forwards;
            text-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        .logo svg {
            filter: drop-shadow(0 2px 5px rgba(0,0,0,0.3));
        }

        .logo span {
            color: var(--primary);
            font-weight: 400;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideDown 1s ease 1s forwards;
        }

        /* Buttons */
        .btn {
            padding: 0.85rem 2.2rem;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            color: var(--light);
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark);
            border: 1px solid var(--primary);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.35);
        }

        /* Hero Content */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 180px 10% 80px;
            position: relative;
        }

        .hero-content {
            max-width: 850px;
            z-index: 10;
        }

        .subtitle {
            text-transform: uppercase;
            letter-spacing: 6px;
            font-size: 0.95rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: block;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 1s ease 1.2s forwards;
            font-weight: 500;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .hero-content h1 {
            font-size: clamp(3.5rem, 7vw, 6.5rem);
            line-height: 1.05;
            margin-bottom: 2rem;
            font-weight: 700;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 1s ease 1.4s forwards;
            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .hero-content p {
            font-size: 1.15rem;
            line-height: 1.9;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 3rem;
            max-width: 650px;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 1s ease 1.6s forwards;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .action-group {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            opacity: 0;
            transform: translateY(30px);
            animation: slideUp 1s ease 1.8s forwards;
        }

        /* Floating Cards / Glassmorphism Features */
        .features-dock {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 3rem 6% 2.5rem;
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            background: linear-gradient(to top, rgba(15,17,21,0.95), transparent);
            z-index: 20;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            padding: 1.8rem;
            border-radius: 16px;
            width: 280px;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            opacity: 0;
            transform: translateY(50px);
            transition: var(--transition);
            animation: fadeUp 1s ease 2s forwards;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .feature-card:nth-child(2) { animation-delay: 2.2s; }
        .feature-card:nth-child(3) { animation-delay: 2.4s; }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02) !important;
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            min-width: 54px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.05));
            border: 1px solid rgba(212, 175, 55, 0.2);
            color: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .feature-text h3 {
            font-size: 1.1rem;
            margin-bottom: 0.4rem;
            font-family: 'inter', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .feature-text p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.4;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: rgba(15, 17, 21, 0.95);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 3.5rem 3rem;
            width: 90%;
            max-width: 650px;
            position: relative;
            transform: translateY(30px) scale(0.95);
            transition: var(--transition);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            text-align: center;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
        }

        .modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: transparent;
            border: none;
            color: var(--light);
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
            transition: var(--transition);
            opacity: 0.6;
        }

        .modal-close:hover {
            opacity: 1;
            color: var(--primary);
            transform: rotate(90deg);
        }

        .modal-content h2 {
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            color: var(--primary);
        }

        .modal-content > p {
            color: rgba(255,255,255,0.7);
            margin-bottom: 2.5rem;
            font-size: 1.05rem;
        }

        .portal-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .portal-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 2.5rem 1.5rem;
            text-decoration: none;
            color: var(--light);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .portal-card:hover {
            background: rgba(212, 175, 55, 0.05); /* very light gold */
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.15);
        }

        .portal-card .portal-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            transition: var(--transition);
            font-size: 1.5rem;
        }

        .portal-card:hover .portal-icon {
            background: var(--primary);
            color: var(--dark);
        }

        .portal-card h3 {
            font-family: 'inter', sans-serif;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .portal-card p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.5;
        }

        .modal-footer {
            margin-top: 2.5rem;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1.5rem;
        }

        .modal-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .modal-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .portal-options {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes slideDown {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .features-dock {
                flex-wrap: wrap;
                justify-content: flex-start;
                padding-left: 10%;
            }
        }

        @media (max-width: 768px) {
            .features-dock {
                display: none; 
            }
            .nav-links {
                gap: 1rem;
            }
            .hero-content {
                /* adjusted for vertical space */
            }
            .hero-content h1 {
                font-size: 3rem;
            }
            .action-group {
                flex-direction: column;
            }
            .btn {
                text-align: center;
                width: 100%;
            }
            nav {
                flex-direction: column;
                gap: 1.5rem;
                padding: 2rem 5%;
            }
        }
    </style>
</head>
<body>
    <!-- Initial Loader -->
    <div class="loader">
        <div class="loader-spinner"></div>
    </div>

    <div class="wrapper">
        <div class="hero-bg"></div>

        <nav>
            <a href="/" class="logo">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                    <path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"></path>
                    <path d="M10 9h.01"></path>
                    <path d="M14 9h.01"></path>
                    <path d="M10 13h.01"></path>
                    <path d="M14 13h.01"></path>
                </svg>
                Grande<span>Luxe</span>
            </a>
            <div class="nav-links">
                <a href="#" class="btn btn-primary open-modal">Access Portals</a>
            </div>
        </nav>

        <header class="hero">
            <div class="hero-content">
                <span class="subtitle">A New Era of Hospitality</span>
                <h1>Redefining Elegance<br>and Comfort.</h1>
                <p>Welcome to GrandeLuxe Hotel Management System. Discover the perfect harmony of refined management operations and seamless guest experiences.</p>
                <div class="action-group">
                    <a href="#" class="btn btn-primary open-modal">Enter Portal</a>
                </div>
            </div>
        </header>


    </div>

    <!-- Portal Modal -->
    <div class="modal-overlay" id="portalModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModal">&times;</button>
            <h2>Select Portal</h2>
            <p>Please select the portal you wish to enter to continue.</p>
            
            <div class="portal-options">
                @if (Route::has('guest.login'))
                <a href="{{ route('guest.login') }}" class="portal-card">
                    <div class="portal-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h3>Guest & Visitor</h3>
                    <p>Manage reservations, invoices, and your user profile.</p>
                </a>
                @endif

                <a href="{{ route('login') }}" class="portal-card">
                    <div class="portal-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h3>Staff & Admin</h3>
                    <p>Secure access to the management and operational systems.</p>
                </a>
            </div>
            
            @if (Route::has('guest.register'))
            <div class="modal-footer">
                New to GrandeLuxe? <a href="{{ route('guest.register') }}">Register as a Guest</a>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('portalModal');
            const closeBtn = document.getElementById('closeModal');
            const openBtns = document.querySelectorAll('.open-modal');

            openBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.classList.add('active');
                });
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.remove('active');
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
