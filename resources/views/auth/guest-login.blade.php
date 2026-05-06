<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Hotel — Guest Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:        #C9A84C;
            --gold-light:  #E8D5A3;
            --gold-dim:    rgba(201,168,76,0.18);
            --dark:        #1a1a1a;
            --darker:      #111111;
            --cream:       #FAF8F3;
            --text-muted:  #888;
            --error:       #c0392b;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream);
            padding: 2rem;
        }

        .card {
            display: flex;
            width: 100%;
            max-width: 860px;
            min-height: 460px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
        }

        /* ── LEFT (branding) ── */
        .left {
            width: 48%;
            background: var(--darker);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2.5rem;
        }

        .left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 40%, rgba(201,168,76,0.08) 0%, transparent 68%);
            pointer-events: none;
        }

        .corner {
            position: absolute;
            width: 44px;
            height: 44px;
            border-color: rgba(201,168,76,0.35);
            border-style: solid;
        }
        .corner-tl { top: 1.2rem; left: 1.2rem; border-width: 1px 0 0 1px; }
        .corner-tr { top: 1.2rem; right: 1.2rem; border-width: 1px 1px 0 0; }
        .corner-bl { bottom: 1.2rem; left: 1.2rem; border-width: 0 0 1px 1px; }
        .corner-br { bottom: 1.2rem; right: 1.2rem; border-width: 0 1px 1px 0; }

        .logo-wrap { position: relative; z-index: 1; text-align: center; }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 1.5px solid rgba(201,168,76,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
        }

        .logo-icon {
            font-size: 2rem;
            color: var(--gold);
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
        }

        .hotel-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 500;
            color: #fff;
            letter-spacing: 0.04em;
            margin-bottom: 0.3rem;
        }

        .hotel-sub {
            font-size: 10px;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.2rem;
        }

        .gold-line {
            width: 32px;
            height: 1px;
            background: var(--gold);
            margin: 0.8rem auto;
            opacity: 0.5;
        }

        .tagline {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.9rem;
            font-style: italic;
            color: rgba(255,255,255,0.32);
            max-width: 210px;
            text-align: center;
            line-height: 1.75;
        }

        /* ── RIGHT (content) ── */
        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.8rem 2.6rem;
            background: #fff;
            position: relative;
        }

        .right::before {
            content: '';
            position: absolute;
            left: 0; top: 10%; bottom: 10%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, var(--gold-light), transparent);
        }

        .guest-tag {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .form-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 500;
            color: var(--dark);
            line-height: 1.15;
            margin-bottom: 0.3rem;
        }

        .form-subheading {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .btn-register {
            display: block;
            width: 100%;
            padding: 0.78rem;
            background: var(--dark);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-register:hover { background: #2e2e2e; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 12px;
            color: var(--gold);
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .back-link:hover { opacity: 0.75; }

        .footer-note {
            margin-top: 1.8rem;
            font-size: 11px;
            color: #bbb;
            text-align: center;
        }

        @media (max-width: 680px) {
            .card { flex-direction: column; max-width: 420px; }
            .left { width: 100%; min-height: 220px; }
            .right::before { display: none; }
        }
    </style>
</head>
<body>

    <div class="card">

        <!-- LEFT: Branding -->
        <div class="left">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="logo-wrap">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:80px;margin-bottom:1.2rem;"> --}}
                <div class="logo-placeholder">
                    <span class="logo-icon">H</span>
                </div>
                <h2 class="hotel-name">Grand Hotel</h2>
                <p class="hotel-sub">Guest Portal</p>
                <div class="gold-line"></div>
                <p class="tagline">"Where every detail is crafted for excellence."</p>
            </div>
        </div>

        <!-- RIGHT: Guest Register CTA -->
        <div class="right">
            <div class="guest-tag">Guest Access</div>
            <h1 class="form-heading">New here,<br>valued guest?</h1>
            <p class="form-subheading">Create a guest account to manage your reservations, check-in details, and preferences — all in one place.</p>

            <a href="{{ route('guest.register') }}" class="btn-register">Create a Guest Account</a>

            <a href="{{ route('login') }}" class="back-link">← Back to Staff Login</a>

            <p class="footer-note">© {{ date('Y') }} Grand Hotel. All rights reserved.</p>
        </div>

    </div>

</body>
</html>