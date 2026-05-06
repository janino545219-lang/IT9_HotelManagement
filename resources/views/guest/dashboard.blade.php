<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Hotel — Guest Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:        #C9A84C;
            --gold-light:  #E8D5A3;
            --gold-dim:    rgba(201,168,76,0.12);
            --dark:        #1a1a1a;
            --darker:      #111111;
            --cream:       #FAF8F3;
            --cream-dark:  #F0EDE6;
            --text-muted:  #888;
            --sidebar-w:   240px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--darker);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 20%, rgba(201,168,76,0.07) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Brand */
        .sidebar-brand {
            padding: 1.8rem 1.5rem 1.4rem;
            border-bottom: 1px solid rgba(201,168,76,0.15);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 1px solid rgba(201,168,76,0.4);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.8rem;
        }

        .brand-logo span {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gold);
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            font-weight: 500;
            color: #fff;
            letter-spacing: 0.03em;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 9px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-top: 2px;
        }

        /* Guest info */
        .sidebar-user {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: relative; z-index: 1;
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--gold-dim);
            border: 1px solid rgba(201,168,76,0.3);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: #fff;
            line-height: 1.3;
        }

        .user-role {
            font-size: 10px;
            color: var(--gold);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            position: relative; z-index: 1;
            overflow-y: auto;
        }

        .nav-section {
            padding: 0.5rem 1.5rem 0.3rem;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
            position: relative;
            cursor: pointer;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.04);
        }

        .nav-item.active {
            color: var(--gold);
            background: var(--gold-dim);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 2px;
            background: var(--gold);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 16px; height: 16px;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .nav-item.active .nav-icon,
        .nav-item:hover .nav-icon { opacity: 1; }

        /* Logout */
        .sidebar-footer {
            padding: 1rem 0;
            border-top: 1px solid rgba(255,255,255,0.05);
            position: relative; z-index: 1;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: color 0.2s;
        }

        .logout-btn:hover { color: #e74c3c; }

        /* ══════════════════════════════
           MAIN CONTENT
        ══════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--cream-dark);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 500;
            color: var(--dark);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-date {
            font-size: 11px;
            color: var(--text-muted);
        }

        .topbar-greeting {
            font-size: 12px;
            color: var(--text-muted);
        }

        .topbar-greeting span {
            color: var(--gold);
            font-weight: 500;
        }

        /* Content area */
        .content {
            padding: 2rem;
            flex: 1;
        }

        /* Welcome banner */
        .welcome-banner {
            background: var(--darker);
            border-radius: 10px;
            padding: 2rem 2.4rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 80% 50%, rgba(201,168,76,0.12) 0%, transparent 60%);
        }

        .welcome-banner::after {
            content: '"';
            position: absolute;
            right: 2rem; top: -0.5rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 8rem;
            color: rgba(201,168,76,0.08);
            line-height: 1;
        }

        .welcome-tag {
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.5rem;
            position: relative; z-index: 1;
        }

        .welcome-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 500;
            color: #fff;
            position: relative; z-index: 1;
            margin-bottom: 0.4rem;
        }

        .welcome-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            position: relative; z-index: 1;
        }

        /* Stat cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.4rem 1.6rem;
            border: 1px solid var(--cream-dark);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(to right, var(--gold), var(--gold-light));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover::before { opacity: 1; }

        .stat-label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
        }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 500;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-note {
            font-size: 11px;
            color: var(--text-muted);
        }

        .stat-icon {
            position: absolute;
            top: 1.2rem; right: 1.4rem;
            width: 32px; height: 32px;
            background: var(--gold-dim);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* Info section */
        .section-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid var(--cream-dark);
        }

        .info-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.6rem;
            border: 1px solid var(--cream-dark);
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px;
        }

        .info-row:last-child { border-bottom: none; }

        .info-key {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .info-val {
            color: var(--dark);
            font-weight: 400;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .badge-gold {
            background: var(--gold-dim);
            color: var(--gold);
            border: 1px solid rgba(201,168,76,0.25);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 2.5rem;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 13px;
            margin-top: 0.5rem;
        }

        /* Mobile toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .mobile-toggle { display: flex; }
            .stats-grid { grid-template-columns: 1fr; }
            .content { padding: 1.2rem; }
        }
        .topbar-profile { background: #1A1A1A; padding: 6px 20px 6px 6px; border-radius: 40px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-left: 1rem; }
        .topbar-avatar { width: 36px; height: 36px; background: #2A2A2A; border: 1.5px solid #C9A84C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; color: #C9A84C; font-size: 14px; font-weight: 600; flex-shrink: 0; }
        .topbar-name { font-size: 13px; font-weight: 500; color: #FFF; line-height: 1.1; }
        .topbar-role { font-size: 10px; color: #C9A84C; letter-spacing: 1.5px; text-transform: uppercase; line-height: 1.1; margin-top: 3px; display:block; }
        .topbar-user-info { display: flex; flex-direction: column; }
    </style>
</head>
<body>

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-logo"><span>H</span></div>
            <div class="brand-name">Grand Hotel</div>
            <div class="brand-sub">Guest Portal</div>
        </div>



        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>

            <a href="{{ url('/guest/dashboard') }}"
               class="nav-item {{ request()->is('guest/dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="nav-section">Reservations</div>

            <a href="{{ url('/guest/reservations') }}"
               class="nav-item {{ request()->is('guest/reservations') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                My Reservations
            </a>

            <a href="{{ url('/guest/reservations/create') }}"
               class="nav-item {{ request()->is('guest/reservations/create') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Book a Room
            </a>

            <div class="nav-section">Account</div>

            <a href="{{ url('/guest/invoices') }}"
               class="nav-item {{ request()->is('guest/invoices') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Invoices
            </a>

            <a href="{{ url('/guest/profile') }}"
               class="nav-item {{ request()->is('guest/profile') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('guest.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>

    </aside>

    <!-- ── MAIN ── -->
    <div class="main">

        <!-- Topbar -->
        <header class="topbar">
            <div style="display:flex; align-items:center; gap:1rem;">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="topbar-title">Guest Dashboard</span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date">{{ now()->format('F d, Y') }}</span>
                <div class="topbar-profile">
                    <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->guest->first_name ?? 'G', 0, 1)) }}</div>
                    <div class="topbar-user-info">
                        <span class="topbar-name">{{ Auth::user()->guest->first_name ?? '' }} {{ Auth::user()->guest->last_name ?? '' }}</span>
                        <span class="topbar-role">Guest</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">

            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-tag">Guest Portal</div>
                <div class="welcome-heading">
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
                    {{ Auth::user()->guest->first_name ?? 'valued guest' }}.
                </div>
                <div class="welcome-sub">Manage your reservations, invoices, and profile — all in one place.</div>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="#C9A84C" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="stat-label">Total Reservations</div>
                    <div class="stat-value">
                        {{ Auth::user()->guest ? Auth::user()->guest->reservations()->count() : 0 }}
                    </div>
                    <div class="stat-note">All time bookings</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="#C9A84C" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-label">Active Stays</div>
                    <div class="stat-value">
                        {{ Auth::user()->guest ? Auth::user()->guest->reservations()->where('status', 'checked_in')->count() : 0 }}
                    </div>
                    <div class="stat-note">Currently checked in</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="#C9A84C" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="stat-label">Pending Invoices</div>
                    <div class="stat-value">
                        {{ Auth::user()->guest ? Auth::user()->guest->invoices()->where('status', 'pending')->count() : 0 }}
                    </div>
                    <div class="stat-note">Awaiting payment</div>
                </div>
            </div>

            <!-- Guest Info -->
            <div class="section-heading">Your Profile</div>
            <div class="info-card">
                <div class="info-row">
                    <span class="info-key">Full Name</span>
                    <span class="info-val">
                        {{ Auth::user()->guest->first_name ?? '—' }} {{ Auth::user()->guest->last_name ?? '' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Email</span>
                    <span class="info-val">{{ Auth::user()->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Phone</span>
                    <span class="info-val">{{ Auth::user()->guest->phone ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Account Type</span>
                    <span class="info-val"><span class="badge badge-gold">Guest</span></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Member Since</span>
                    <span class="info-val">{{ Auth::user()->created_at->format('F d, Y') }}</span>
                </div>
            </div>

        </div>
    </div>

</body>
</html>