<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold: #C9A84C;
            --gold-light: #E8D5A3;
            --dark: #1a1a1a;
            --darker: #111111;
            --cream: #FAF8F3;
            --text-muted: #888;
            --error: #c0392b;
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

        .left {
            width: 48%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.8rem;
            background: #fff;
            position: relative;
        }

        .left::after {
            content: '';
            position: absolute;
            right: 0; top: 10%; bottom: 10%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, var(--gold-light), transparent);
        }

        .brand-tag {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.2rem;
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
            margin-bottom: 1.6rem;
        }

        .field-group { margin-bottom: 1rem; }

        label {
            display: block;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--dark);
            background: #fafafa;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--gold);
            background: #fff;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember input[type="checkbox"] { accent-color: var(--gold); }

        .forgot {
            font-size: 12px;
            color: var(--gold);
            text-decoration: none;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: var(--dark);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover { background: #333; }

        .error-msg {
            background: #fdf0ef;
            border-left: 3px solid var(--error);
            color: var(--error);
            font-size: 12px;
            padding: 0.6rem 0.8rem;
            border-radius: 2px;
            margin-bottom: 1rem;
        }

        .guest-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1rem 0 0.6rem;
            color: #ccc;
            font-size: 10px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .guest-divider::before,
        .guest-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ececec;
        }

        .guest-link-wrap {
            text-align: center;
        }

        .guest-link-wrap span {
            font-size: 12px;
            color: var(--text-muted);
        }

        .guest-link-wrap a {
            font-size: 12px;
            color: var(--gold);
            text-decoration: none;
            margin-left: 4px;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .guest-link-wrap a:hover { opacity: 0.75; }

        .footer-note {
            margin-top: 1.2rem;
            font-size: 11px;
            color: #bbb;
            text-align: center;
        }

        .right {
            width: 52%;
            background: var(--darker);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 2.5rem;
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
            color: rgba(255,255,255,0.35);
            max-width: 220px;
            text-align: center;
            line-height: 1.7;
        }

        @media (max-width: 680px) {
            .card { flex-direction: column; max-width: 420px; }
            .left, .right { width: 100%; }
            .left::after { display: none; }
            .right { min-height: 220px; }
        }
    </style>
</head>
<body>

    <div class="card">

        <!-- LEFT: Login Form -->
        <div class="left">
            <div class="brand-tag">Hotel Management System</div>
            <h1 class="form-heading">Welcome<br>back.</h1>
            <p class="form-subheading">Sign in to access the management system.</p>

            @if ($errors->any())
                <div class="error-msg">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="field-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="you@hotel.com"
                           required autofocus />
                </div>

                <div class="field-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required />
                </div>

                <div class="field-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="guest-divider">or</div>
            <div class="guest-link-wrap">
                <span>Are you a guest?</span>
                <a href="{{ route('guest.login') }}">Sign in here</a>
            </div>

            <p class="footer-note">© {{ date('Y') }} Hotel Management System</p>
        </div>

        <!-- RIGHT: Branding -->
        <div class="right">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="logo-wrap">
                <div class="logo-placeholder">
                    <span class="logo-icon">H</span>
                </div>
                <h2 class="hotel-name">Grand Hotel</h2>
                <p class="hotel-sub">Management System</p>
                <div class="gold-line"></div>
                <p class="tagline">"Where every detail is crafted for excellence."</p>
            </div>
        </div>

    </div>

</body>
</html>