<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations — Grand Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --gold: #C9A84C; --gold-light: #E8D5A3; --gold-dim: rgba(201,168,76,0.12);
            --dark: #1a1a1a; --darker: #111111; --cream: #FAF8F3;
            --cream-dark: #F0EDE6; --text-muted: #888; --sidebar-w: 240px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; display: flex; }
        .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--darker); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; transition: transform 0.3s ease; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 99; }
        .sidebar-overlay.active { display: block; }
        .sidebar-close-btn { display: none; background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.4); padding: 4px; line-height: 1; }
        .sidebar-close-btn:hover { color: #fff; }
        .mobile-toggle { display: none; background: none; border: none; cursor: pointer; padding: 0.4rem; color: var(--dark); }
        .sidebar::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 20%, rgba(201,168,76,0.07) 0%, transparent 60%); pointer-events: none; }
        .sidebar::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 20%, rgba(201,168,76,0.07) 0%, transparent 60%); pointer-events: none; }
        .sidebar-brand { padding: 1.8rem 1.5rem 1.4rem; border-bottom: 1px solid rgba(201,168,76,0.15); position: relative; z-index: 1; }
        .brand-logo { width: 42px; height: 42px; border-radius: 50%; border: 1px solid rgba(201,168,76,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 0.8rem; }
        .brand-logo span { font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; font-weight: 600; color: var(--gold); }
        .brand-name { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 500; color: #fff; }
        .brand-sub { font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); margin-top: 2px; }
        .sidebar-user { padding: 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 1; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--gold-dim); border: 1px solid rgba(201,168,76,0.3); display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; font-size: 1rem; color: var(--gold); font-weight: 600; margin-bottom: 0.5rem; }
        .user-name { font-size: 13px; font-weight: 500; color: #fff; }
        .user-role { font-size: 10px; color: var(--gold); letter-spacing: 0.1em; text-transform: uppercase; }
        .sidebar-nav { flex: 1; padding: 1rem 0; position: relative; z-index: 1; }
        .nav-section { padding: 0.5rem 1.5rem 0.3rem; font-size: 9px; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.2); }
        .nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 1.5rem; font-size: 13px; color: rgba(255,255,255,0.55); text-decoration: none; transition: color 0.2s, background 0.2s; position: relative; }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .nav-item.active { color: var(--gold); background: var(--gold-dim); }
        .nav-item.active::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 2px; background: var(--gold); border-radius: 0 2px 2px 0; }
        .nav-icon { width: 16px; height: 16px; opacity: 0.7; flex-shrink: 0; }
        .nav-item.active .nav-icon, .nav-item:hover .nav-icon { opacity: 1; }
        .sidebar-footer { padding: 1rem 0; border-top: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 1; }
        .logout-btn { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 1.5rem; font-size: 13px; color: rgba(255,255,255,0.4); text-decoration: none; width: 100%; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: color 0.2s; }
        .logout-btn:hover { color: #e74c3c; }
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #fff; border-bottom: 1px solid var(--cream-dark); padding: 0 2rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-title { font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; font-weight: 500; color: var(--dark); }
        .topbar-date { font-size: 11px; color: var(--text-muted); }
        .content { padding: 2rem; flex: 1; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 500; color: var(--dark); }
        .btn-book-new { padding: 0.6rem 1.2rem; background: var(--darker); color: #fff; border: none; border-radius: 6px; font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s; }
        .btn-book-new:hover { background: var(--gold); }

        .reservations-list { display: flex; flex-direction: column; gap: 1rem; }

        .res-card { background: #fff; border-radius: 10px; border: 1px solid var(--cream-dark); padding: 1.4rem 1.6rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .res-left { display: flex; gap: 1rem; align-items: center; flex: 1; }
        .res-img { width: 70px; height: 70px; border-radius: 8px; object-fit: cover; background: var(--cream-dark); flex-shrink: 0; }
        .res-img-placeholder { width: 70px; height: 70px; border-radius: 8px; background: var(--cream-dark); flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .res-info { flex: 1; }
        .res-room { font-family: 'Cormorant Garamond', serif; font-size: 1rem; font-weight: 500; color: var(--dark); }
        .res-type { font-size: 11px; color: var(--text-muted); margin-bottom: 0.4rem; }
        .res-dates { font-size: 12px; color: var(--dark); }
        .res-dates span { color: var(--text-muted); }
        .res-right { text-align: right; flex-shrink: 0; }
        .res-total { font-size: 1rem; font-weight: 500; color: var(--gold); margin-bottom: 0.4rem; }
        .res-nights { font-size: 11px; color: var(--text-muted); margin-bottom: 0.5rem; }

        .badge { display: inline-block; padding: 0.2rem 0.65rem; border-radius: 20px; font-size: 10px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
        .badge-pending { background: #fff8e1; color: #f57c00; border: 1px solid #ffe082; }
        .badge-confirmed { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .badge-checked_in { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
        .badge-checked_out { background: #f3e5f5; color: #6a1b9a; border: 1px solid #e1bee7; }
        .badge-cancelled { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

        .alert-success { background: #f0fff4; border: 1px solid #c8e6c9; color: #2e7d32; padding: 0.8rem 1rem; border-radius: 6px; font-size: 12px; margin-bottom: 1.5rem; }
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
        .empty-state p { font-size: 13px; margin-top: 0.5rem; }
        .empty-state a { display: inline-block; margin-top: 1rem; padding: 0.65rem 1.4rem; background: var(--darker); color: #fff; border-radius: 6px; text-decoration: none; font-size: 13px; transition: background 0.2s; }
        .empty-state a:hover { background: var(--gold); }
            .topbar-profile { background: #1A1A1A; padding: 6px 20px 6px 6px; border-radius: 40px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-left: 1rem; }
        .topbar-avatar { width: 36px; height: 36px; background: #2A2A2A; border: 1.5px solid #C9A84C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; color: #C9A84C; font-size: 14px; font-weight: 600; flex-shrink: 0; }
        .topbar-name { font-size: 13px; font-weight: 500; color: #FFF; line-height: 1.1; }
        .topbar-role { font-size: 10px; color: #C9A84C; letter-spacing: 1.5px; text-transform: uppercase; line-height: 1.1; margin-top: 3px; display:block; }
        .topbar-user-info { display: flex; flex-direction: column; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo"><span>H</span></div>
        <div class="brand-name">Grand Hotel</div>
        <div class="brand-sub">Guest Portal</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('guest.dashboard') }}" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <div class="nav-section">Reservations</div>
        <a href="{{ route('guest.reservations.index') }}" class="nav-item active">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            My Reservations
        </a>
        <a href="{{ route('guest.reservations.create') }}" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Book a Room
        </a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('guest.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <span class="topbar-title">My Reservations</span>
        <span class="topbar-date">{{ now()->format('F d, Y') }}</span>
    </header>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        <div class="page-header">
            <h2>Your Bookings</h2>
            <a href="{{ route('guest.reservations.create') }}" class="btn-book-new">
                + Book a Room
            </a>
        </div>

        @if(empty($reservations) || count($reservations) === 0)
            <div class="empty-state">
                <svg width="40" height="40" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p>You have no reservations yet.</p>
                <a href="{{ route('guest.reservations.create') }}">Book your first room</a>
            </div>
        @else
            <div class="reservations-list">
                @foreach($reservations as $res)
                @php
                    $nights = \Carbon\Carbon::parse($res->check_in_date)->diffInDays(\Carbon\Carbon::parse($res->check_out_date));
                @endphp
                <div class="res-card">
                    <div class="res-left">
                        @if($res->image)
                            <img src="{{ asset($res->image) }}" class="res-img" alt="{{ $res->room_number }}">
                        @else
                            <div class="res-img-placeholder">
                                <svg width="24" height="24" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                            </div>
                        @endif
                        <div class="res-info">
                            <div class="res-room">{{ $res->room_number }}</div>
                            <div class="res-type">{{ ucfirst($res->room_type) }}</div>
                            <div class="res-dates">
                                <span>Check-in:</span> {{ \Carbon\Carbon::parse($res->check_in_date)->format('M d, Y') }}
                                &nbsp;→&nbsp;
                                <span>Check-out:</span> {{ \Carbon\Carbon::parse($res->check_out_date)->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="res-right">
                        <div class="res-total">₱{{ number_format($res->total_amount, 2) }}</div>
                        <div class="res-nights">{{ $nights }} night(s) · {{ $res->num_guests }} guest(s)</div>
                        <span class="badge badge-{{ $res->status }}">{{ ucfirst(str_replace('_', ' ', $res->status)) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

</body>
</html>