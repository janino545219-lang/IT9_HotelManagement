<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Grand Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #F5F2ED; display: flex; min-height: 100vh; color: #2C2C2C; }

        /* SIDEBAR */
       .sidebar { width: 240px; height: 100vh; background: #1A1A1A; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 100; overflow-y: auto; transition: transform 0.3s ease; }
        .sidebar-brand { padding: 28px 24px 20px; border-bottom: 1px solid #2E2E2E; }
        .brand-logo { width: 46px; height: 46px; background: #2A2A2A; border: 1.5px solid #C9A84C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; color: #C9A84C; font-size: 18px; font-weight: 600; margin-bottom: 12px; }
        .brand-name { font-family: 'Playfair Display', serif; color: #FFF; font-size: 16px; font-weight: 500; }
        .brand-sub { font-size: 10px; color: #C9A84C; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
        .admin-badge { padding: 16px 24px; border-bottom: 1px solid #2E2E2E; display: flex; align-items: center; gap: 12px; }
        .admin-avatar { width: 38px; height: 38px; background: #2A2A2A; border: 1.5px solid #C9A84C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; color: #C9A84C; font-size: 14px; font-weight: 600; flex-shrink: 0; }
        .admin-name { font-size: 13px; font-weight: 500; color: #FFF; }
        .admin-role { font-size: 10px; color: #C9A84C; letter-spacing: 1.5px; text-transform: uppercase; }
        .nav-section { padding: 18px 0 6px; }
        .nav-label { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #555; padding: 0 24px 8px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 24px; color: #888; text-decoration: none; font-size: 13.5px; transition: all 0.2s; border-left: 3px solid transparent; }
        .nav-item:hover { color: #E8E0D0; background: #242424; }
        .nav-item.active { color: #C9A84C; border-left-color: #C9A84C; background: #242424; }
        .nav-item i { width: 16px; font-size: 13px; text-align: center; }
        .sidebar-footer { margin-top: auto; border-top: 1px solid #2E2E2E; padding: 16px 0; }
        .sidebar-footer button { width: 100%; background: none; cursor: pointer; border: none; text-align: left; }

        /* MAIN */
        .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #F5F2ED; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #E5E0D8; position: sticky; top: 0; z-index: 50; gap: 12px; }
        .topbar-left { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .hamburger-btn { display: none; background: none; border: none; cursor: pointer; padding: 6px; border-radius: 6px; color: #555; }
        .hamburger-btn:hover { background: #E5E0D8; }
        .topbar-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 500; color: #1A1A1A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-right { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .topbar-date { font-size: 13px; color: #888; font-weight: 500; }
        .topbar-profile { background: #1A1A1A; padding: 6px 14px 6px 6px; border-radius: 40px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .topbar-avatar { width: 34px; height: 34px; background: #2A2A2A; border: 1.5px solid #C9A84C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; color: #C9A84C; font-size: 13px; font-weight: 600; flex-shrink: 0; }
        .topbar-name { font-size: 12px; font-weight: 500; color: #FFF; line-height: 1.1; }
        .topbar-role { font-size: 10px; color: #C9A84C; letter-spacing: 1.5px; text-transform: uppercase; line-height: 1.1; margin-top: 2px; }
        .content { padding: 24px 20px; flex: 1; }

        /* OVERLAY */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 99; }
        .sidebar-overlay.active { display: block; }

        /* CLOSE BUTTON inside sidebar (mobile) */
        .sidebar-close-btn { display: none; background: none; border: none; cursor: pointer; color: #888; padding: 4px; border-radius: 4px; }
        .sidebar-close-btn:hover { color: #FFF; }


        /* STATS */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: #FFF; border-radius: 8px; padding: 24px; border: 1px solid #EAE6DF; position: relative; }
        .stat-card .icon { position: absolute; top: 20px; right: 20px; width: 36px; height: 36px; background: #FDF8EF; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #C9A84C; font-size: 14px; }
        .stat-label { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #999; margin-bottom: 10px; }
        .stat-value { font-size: 32px; font-weight: 300; color: #1A1A1A; line-height: 1; margin-bottom: 6px; }
        .stat-sub { font-size: 12px; color: #AAA; }

        /* SECTION CARDS */
        .section-card { background: #FFF; border-radius: 8px; border: 1px solid #EAE6DF; margin-bottom: 24px; }
        .section-header { padding: 20px 24px; border-bottom: 1px solid #F0EBE3; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .section-title { font-size: 14px; font-weight: 600; color: #1A1A1A; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 6px; font-size: 12.5px; font-weight: 500; cursor: pointer; text-decoration: none; border: none; transition: all 0.2s; font-family: 'DM Sans', sans-serif; }
        .btn-gold { background: #C9A84C; color: #FFF; }
        .btn-gold:hover { background: #B8962E; color: #fff; }
        .btn-outline { background: transparent; border: 1px solid #D8D2C8; color: #555; }
        .btn-outline:hover { border-color: #C9A84C; color: #C9A84C; }
        .btn-danger { background: transparent; border: 1px solid #E8BCBC; color: #C0392B; }
        .btn-danger:hover { background: #FDF0F0; }
        .btn-sm { padding: 5px 12px; font-size: 11.5px; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        thead th { font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: #999; padding: 12px 24px; text-align: left; border-bottom: 1px solid #F0EBE3; }
        tbody td { padding: 14px 24px; font-size: 13.5px; color: #333; border-bottom: 1px solid #F8F5F0; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #FDFAF6; }

        /* BADGE */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
        .badge-green  { background: #EBF7F0; color: #2E7D52; }
        .badge-yellow { background: #FEF9EC; color: #B7860B; }
        .badge-red    { background: #FDF0F0; color: #C0392B; }
        .badge-blue   { background: #EEF3FD; color: #2B5EAF; }
        .badge-grey   { background: #F0F0F0; color: #666; }

        /* FORMS */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #888; font-weight: 500; }
        input, select, textarea { padding: 10px 14px; border: 1px solid #DDD8D0; border-radius: 6px; font-size: 13.5px; font-family: 'DM Sans', sans-serif; background: #FDFAF6; color: #2C2C2C; transition: border 0.2s; outline: none; width: 100%; }
        input:focus, select:focus, textarea:focus { border-color: #C9A84C; background: #FFF; }
        textarea { resize: vertical; min-height: 90px; }
        .form-actions { display: flex; gap: 12px; padding-top: 8px; flex-wrap: wrap; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .hamburger-btn { display: flex; }
            .sidebar-close-btn { display: flex; align-items: center; justify-content: center; }
            .topbar-date { display: none; }
            .content { padding: 16px; }
            .topbar { padding: 12px 16px; }
            .topbar-title { font-size: 17px; }
            .stat-grid { grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
            .stat-card { padding: 16px; }
            .stat-value { font-size: 26px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: auto; }
            thead th, tbody td { padding: 10px 12px; font-size: 12px; }
            .section-header { padding: 14px 16px; }
            .btn { padding: 7px 12px; font-size: 12px; }
            .detail-label { width: 130px; font-size: 10px; }
            .topbar-name { max-width: 80px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .topbar-profile { padding: 5px 10px 5px 5px; gap: 7px; }
        }
        @media (max-width: 480px) {
            .stat-grid { grid-template-columns: 1fr; }
            .filter-bar { flex-direction: column; }
            table { display: block; overflow-x: auto; white-space: nowrap; }
        }

        /* ALERTS */
        .alert { padding: 12px 18px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #EBF7F0; color: #2E7D52; border: 1px solid #C3E8D5; }
        .alert-error   { background: #FDF0F0; color: #C0392B; border: 1px solid #F5C6C6; }

        /* MISC */
        .empty-state { text-align: center; padding: 60px 24px; color: #AAA; }
        .empty-state i { font-size: 36px; margin-bottom: 12px; color: #D8D2C8; display: block; }
        .filter-bar { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .search-wrap { position: relative; flex: 1; min-width: 200px; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #AAA; font-size: 13px; }
        .search-wrap input { padding-left: 36px; }
        .pagination-wrap { padding: 16px 24px; border-top: 1px solid #F0EBE3; }
        .pagination-wrap nav { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .pagination-wrap nav > div:first-child { font-size: 12px; color: #999; }
        .pagination-wrap nav > div:last-child { display: flex; justify-content: center; }
        .pagination-wrap nav > div:last-child > div { display: none; }
        .pagination-wrap nav span[aria-label], .pagination-wrap nav a[aria-label] { display: none; }
        .pagination-wrap svg { display: none; }
        .pagination-wrap nav > div:last-child > span { display: flex; align-items: center; gap: 4px; }
        .pagination-wrap nav > div:last-child > span > a,
        .pagination-wrap nav > div:last-child > span > span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 10px;
            border-radius: 6px; font-size: 13px; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .pagination-wrap nav > div:last-child > span > a {
            background: #FFF; border: 1px solid #E5E0D8; color: #555; cursor: pointer;
        }
        .pagination-wrap nav > div:last-child > span > a:hover {
            background: #C9A84C; border-color: #C9A84C; color: #FFF;
        }
        .pagination-wrap nav > div:last-child > span > span:not([aria-disabled]) {
            background: #C9A84C; border: 1px solid #C9A84C; color: #FFF;
        }
        .pagination-wrap nav > div:last-child > span > span[aria-disabled="true"] {
            background: #F5F2ED; border: 1px solid #E5E0D8; color: #CCC; cursor: default;
        }
        /* Simple pagination (Previous / Next only) */
        .pagination-wrap > nav > div > a,
        .pagination-wrap > nav > div > span.cursor-default {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .pagination-wrap > nav > div > a {
            background: #FFF; border: 1px solid #E5E0D8; color: #555; cursor: pointer;
        }
        .pagination-wrap > nav > div > a:hover {
            background: #C9A84C; border-color: #C9A84C; color: #FFF;
        }
        .pagination-wrap > nav > div > span.cursor-default {
            background: #F5F2ED; border: 1px solid #E5E0D8; color: #CCC; cursor: default;
        }
        .detail-row { display: flex; border-bottom: 1px solid #F8F5F0; padding: 14px 24px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #999; width: 200px; flex-shrink: 0; padding-top: 1px; }
        .detail-value { font-size: 13.5px; color: #2C2C2C; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="adminSidebar">
    <div class="sidebar-brand" style="display:flex;align-items:flex-start;justify-content:space-between;">
        <div>
            <div class="brand-logo">H</div>
            <div class="brand-name">Grand Hotel</div>
            <div class="brand-sub">Admin Panel</div>
        </div>
        <button class="sidebar-close-btn" onclick="closeSidebar()" title="Close menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>



    <nav>
        <div class="nav-section">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-dropdown-toggle {{ request()->routeIs('admin.rooms.*', 'admin.reservations.*', 'admin.walkins.*') ? 'open' : '' }}" onclick="toggleDropdown(this)">
                <span><i class="fas fa-hotel" style="width:16px;text-align:center;margin-right:8px;"></i> Hotel</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="nav-dropdown-menu {{ request()->routeIs('admin.rooms.*', 'admin.reservations.*', 'admin.walkins.*') ? 'show' : '' }}">
                <a href="{{ route('admin.rooms.index') }}" class="nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i> Rooms
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="nav-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Reservations
                </a>
                <a href="{{ route('admin.walkins.index') }}" class="nav-item {{ request()->routeIs('admin.walkins.*') ? 'active' : '' }}">
                    <i class="fas fa-person-walking"></i> Walk-Ins
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-dropdown-toggle {{ request()->routeIs('admin.guests.*', 'admin.employees.*', 'admin.staff.*') ? 'open' : '' }}" onclick="toggleDropdown(this)">
                <span><i class="fas fa-users" style="width:16px;text-align:center;margin-right:8px;"></i> People</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="nav-dropdown-menu {{ request()->routeIs('admin.guests.*', 'admin.employees.*', 'admin.staff.*') ? 'show' : '' }}">
                <a href="{{ route('admin.guests.index') }}" class="nav-item {{ request()->routeIs('admin.guests.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Guests
                </a>
                <a href="{{ route('admin.employees.index') }}" class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <i class="fas fa-id-badge"></i> Employees
                </a>
                <a href="{{ route('admin.staff.index') }}" class="nav-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i> Staff
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-dropdown-toggle {{ request()->routeIs('admin.invoices.*', 'admin.payments.*') ? 'open' : '' }}" onclick="toggleDropdown(this)">
                <span><i class="fas fa-coins" style="width:16px;text-align:center;margin-right:8px;"></i> Finance</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="nav-dropdown-menu {{ request()->routeIs('admin.invoices.*', 'admin.payments.*') ? 'show' : '' }}">
                <a href="{{ route('admin.invoices.index') }}" class="nav-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i> Invoices
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-dropdown-toggle {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}" onclick="toggleDropdown(this)">
                <span><i class="fas fa-chart-bar" style="width:16px;text-align:center;margin-right:8px;"></i> Reports</span>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="nav-dropdown-menu {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}">
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </div>
        </div>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item">
                <i class="fas fa-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger-btn" onclick="openSidebar()" title="Open menu">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">{{ now()->format('F j, Y') }}</div>
            <div class="topbar-profile">
                <div class="topbar-avatar">A</div>
                <div class="topbar-user">
                    <div class="topbar-name">Administrator</div>
                    <div class="topbar-role">Admin</div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

<style>
    /* Dropdown Navigation */
    .nav-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 24px;
        color: #999;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }
    .nav-dropdown-toggle:hover {
        color: #E8E0D0;
        background: #222;
    }
    .nav-dropdown-toggle.open {
        color: #C9A84C;
    }
    .nav-dropdown-toggle span {
        display: flex;
        align-items: center;
    }
    .dropdown-arrow {
        font-size: 10px;
        transition: transform 0.3s ease;
    }
    .nav-dropdown-toggle.open .dropdown-arrow {
        transform: rotate(180deg);
    }
    .nav-dropdown-menu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease;
    }
    .nav-dropdown-menu.show {
        max-height: 300px;
    }
    .nav-dropdown-menu .nav-item {
        padding-left: 48px;
        font-size: 13px;
    }
</style>

@stack('scripts')
<script>
    function toggleDropdown(el) {
        el.classList.toggle('open');
        const menu = el.nextElementSibling;
        menu.classList.toggle('show');
    }
    function openSidebar() {
        document.getElementById('adminSidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
    // Close sidebar when a nav link is clicked on mobile
    document.querySelectorAll('.sidebar .nav-item').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });
</script>
</body>
</html>