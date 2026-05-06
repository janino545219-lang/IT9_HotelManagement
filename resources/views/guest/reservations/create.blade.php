<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room — Grand Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --gold: #C9A84C; --gold-light: #E8D5A3; --gold-dim: rgba(201,168,76,0.12);
            --dark: #1a1a1a; --darker: #111111; --cream: #FAF8F3;
            --cream-dark: #F0EDE6; --text-muted: #888; --sidebar-w: 240px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; display: flex; }

        /* Sidebar — same as dashboard */
        .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--darker); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; }
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

        /* Rooms Grid */
        .page-header { margin-bottom: 1.5rem; }
        .page-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 500; color: var(--dark); }
        .page-header p { font-size: 13px; color: var(--text-muted); margin-top: 0.3rem; }

        .rooms-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }

        .room-card { background: #fff; border-radius: 10px; border: 1px solid var(--cream-dark); overflow: hidden; transition: box-shadow 0.2s, transform 0.2s; }
        .room-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }

        .room-img { width: 100%; height: 180px; object-fit: cover; display: block; background: var(--cream-dark); }
        .room-img-placeholder { width: 100%; height: 180px; background: var(--cream-dark); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 12px; }

        .room-body { padding: 1.2rem 1.4rem; }
        .room-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
        .room-name { font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; font-weight: 500; color: var(--dark); }
        .badge-available { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 10px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; }
        .room-type { font-size: 12px; color: var(--text-muted); margin-bottom: 0.8rem; }
        .room-meta { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 0.8rem; }
        .room-price { font-size: 1rem; font-weight: 500; color: var(--gold); }
        .room-amenities { font-size: 11px; color: var(--text-muted); margin-bottom: 1rem; padding: 0.5rem; background: var(--cream); border-radius: 4px; }

        .btn-book { width: 100%; padding: 0.7rem; background: var(--darker); color: #fff; border: none; border-radius: 6px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; letter-spacing: 0.03em; }
        .btn-book:hover { background: var(--gold); }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 12px; padding: 2rem; width: 90%; max-width: 460px; position: relative; }
        .modal-title { font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; font-weight: 500; color: var(--dark); margin-bottom: 0.3rem; }
        .modal-sub { font-size: 12px; color: var(--text-muted); margin-bottom: 1.5rem; }
        .modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted); }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 11px; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.65rem 0.9rem; border: 1px solid var(--cream-dark); border-radius: 6px; font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--dark); background: #fff; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: var(--gold); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .price-summary { background: var(--cream); border-radius: 6px; padding: 0.8rem 1rem; margin-bottom: 1rem; font-size: 12px; color: var(--text-muted); }
        .price-summary span { color: var(--gold); font-weight: 500; }
        .btn-submit { width: 100%; padding: 0.8rem; background: var(--darker); color: #fff; border: none; border-radius: 6px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--gold); }

        .alert-error { background: #fff0f0; border: 1px solid #ffcccc; color: #c0392b; padding: 0.8rem 1rem; border-radius: 6px; font-size: 12px; margin-bottom: 1rem; }
        .alert-success { background: #f0fff4; border: 1px solid #c8e6c9; color: #2e7d32; padding: 0.8rem 1rem; border-radius: 6px; font-size: 12px; margin-bottom: 1rem; }
        .no-rooms { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
        .no-rooms p { font-size: 13px; margin-top: 0.5rem; }
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
        <a href="{{ route('guest.reservations.index') }}" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            My Reservations
        </a>
        <a href="{{ route('guest.reservations.create') }}" class="nav-item active">
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
        <span class="topbar-title">Book a Room</span>
        <span class="topbar-date">{{ now()->format('F d, Y') }}</span>
    </header>

    <div class="content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        <div class="page-header">
            <h2>Available Rooms</h2>
            <p>Select a room to begin your booking</p>
        </div>

        @if($rooms->isEmpty())
            <div class="no-rooms">
                <svg width="40" height="40" fill="none" stroke="#ccc" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p>No rooms available at the moment. Please check back later.</p>
            </div>
        @else
            <div class="rooms-grid">
                @foreach($rooms as $room)
                <div class="room-card">
                    @if($room->image)
                        <img src="{{ asset($room->image) }}" alt="{{ $room->room_number }}" class="room-img">
                    @else
                        <div class="room-img-placeholder">No Image</div>
                    @endif
                    <div class="room-body">
                        <div class="room-header">
                            <div class="room-name">{{ $room->room_number }}</div>
                            <span class="badge-available">Available</span>
                        </div>
                        <div class="room-type">{{ ucfirst($room->room_type) }} · Floor {{ $room->floor_number }}</div>
                        <div class="room-meta">
                            <span class="room-price">₱{{ number_format($room->price_per_night, 2) }}/night</span>
                            <span>Capacity: {{ $room->capacity }} pax</span>
                        </div>
                        @if($room->amenities)
                            <div class="room-amenities">{{ $room->amenities }}</div>
                        @endif
                        <button class="btn-book" onclick="openModal(
                            '{{ $room->room_id }}',
                            '{{ $room->room_number }}',
                            {{ $room->price_per_night }},
                            {{ $room->capacity }}
                        )">Book This Room</button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Booking Modal -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal()">✕</button>
        <div class="modal-title" id="modalRoomName">Book Room</div>
        <div class="modal-sub">Fill in your stay details below</div>

        <form method="POST" action="{{ route('guest.reservations.store') }}">
            @csrf
            <input type="hidden" name="room_id" id="modalRoomId">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Check-in Date</label>
                    <input type="date" name="check_in_date" id="checkIn" class="form-control"
                           min="{{ date('Y-m-d') }}" required onchange="calcTotal()">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-out Date</label>
                    <input type="date" name="check_out_date" id="checkOut" class="form-control"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required onchange="calcTotal()">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Number of Guests</label>
                <input type="number" name="num_guests" id="numGuests" class="form-control"
                       min="1" value="1" required>
                <small style="font-size:11px;color:var(--text-muted)" id="capacityNote"></small>
            </div>

            <div class="price-summary" id="priceSummary">
                Select dates to see the total amount.
            </div>

            <button type="submit" class="btn-submit">Confirm Booking</button>
        </form>
    </div>
</div>

<script>
    let currentPrice = 0;

    function openModal(roomId, roomName, price, capacity) {
        currentPrice = price;
        document.getElementById('modalRoomId').value = roomId;
        document.getElementById('modalRoomName').textContent = 'Book — ' + roomName;
        document.getElementById('numGuests').max = capacity;
        document.getElementById('capacityNote').textContent = 'Max ' + capacity + ' guest(s)';
        document.getElementById('bookingModal').classList.add('open');
        calcTotal();
    }

    function closeModal() {
        document.getElementById('bookingModal').classList.remove('open');
        document.getElementById('priceSummary').textContent = 'Select dates to see the total amount.';
    }

    function calcTotal() {
        const ci = document.getElementById('checkIn').value;
        const co = document.getElementById('checkOut').value;
        if (ci && co) {
            const nights = Math.round((new Date(co) - new Date(ci)) / 86400000);
            if (nights > 0) {
                const total = nights * currentPrice;
                document.getElementById('priceSummary').innerHTML =
                    nights + ' night(s) × ₱' + currentPrice.toLocaleString() +
                    ' = <span>₱' + total.toLocaleString('en-PH', {minimumFractionDigits:2}) + '</span>';
                // Set check-out min to day after check-in
                document.getElementById('checkOut').min = new Date(new Date(ci).getTime() + 86400000).toISOString().split('T')[0];
            } else {
                document.getElementById('priceSummary').textContent = 'Check-out must be after check-in.';
            }
        }
    }

    // Close modal on overlay click
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
</body>
</html>