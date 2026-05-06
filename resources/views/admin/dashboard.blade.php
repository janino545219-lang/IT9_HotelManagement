@extends('admin.layout')
@section('title', 'Admin Dashboard')

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="icon"><i class="fas fa-door-open"></i></div>
        <div class="stat-label">Total Rooms</div>
        <div class="stat-value">{{ $totalRooms ?? 0 }}</div>
        <div class="stat-sub">Available: {{ $availableRooms ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-label">Reservations</div>
        <div class="stat-value">{{ $totalReservations ?? 0 }}</div>
        <div class="stat-sub">Active today</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <div class="stat-label">Total Guests</div>
        <div class="stat-value">{{ $totalGuests ?? 0 }}</div>
        <div class="stat-sub">Registered accounts</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-credit-card"></i></div>
        <div class="stat-label">Revenue</div>
        <div class="stat-value">₱{{ number_format($totalRevenue ?? 0, 0) }}</div>
        <div class="stat-sub">Total payments received</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-file-invoice"></i></div>
        <div class="stat-label">Pending Invoices</div>
        <div class="stat-value">{{ $pendingInvoices ?? 0 }}</div>
        <div class="stat-sub">Awaiting payment</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-person-walking"></i></div>
        <div class="stat-label">Walk-Ins</div>
        <div class="stat-value">{{ $totalWalkins ?? 0 }}</div>
        <div class="stat-sub">All time</div>
    </div>
</div>

<!-- Recent Reservations -->
<div class="section-card">
    <div class="section-header">
        <div class="section-title">Recent Reservations</div>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentReservations ?? [] as $r)
            <tr>
                <td>{{ $r->guest->first_name ?? '' }} {{ $r->guest->last_name ?? '' }}</td>
                <td>Room {{ $r->room->room_number ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }}</td>
                <td>₱{{ number_format($r->total_amount, 2) }}</td>
                <td>
                    @if($r->status === 'confirmed')
                        <span class="badge badge-green">Confirmed</span>
                    @elseif($r->status === 'pending')
                        <span class="badge badge-yellow">Pending</span>
                    @elseif($r->status === 'cancelled')
                        <span class="badge badge-red">Cancelled</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($r->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No reservations yet</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">
        {{ $recentReservations->links() }}
    </div>
</div>

<!-- Room Status Overview -->
<div class="section-card">
    <div class="section-header">
        <div class="section-title">Room Status Overview</div>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline btn-sm">Manage Rooms</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Room No.</th>
                <th>Type</th>
                <th>Floor</th>
                <th>Price / Night</th>
                <th>Capacity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms ?? [] as $room)
            <tr>
                <td><strong>{{ $room->room_number }}</strong></td>
                <td>{{ $room->room_type }}</td>
                <td>Floor {{ $room->floor_number }}</td>
                <td>₱{{ number_format($room->price_per_night, 2) }}</td>
                <td>{{ $room->capacity }} pax</td>
                <td>
                    @if($room->status === 'available')
                        <span class="badge badge-green">Available</span>
                    @elseif($room->status === 'occupied')
                        <span class="badge badge-red">Occupied</span>
                    @elseif($room->status === 'maintenance')
                        <span class="badge badge-yellow">Maintenance</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($room->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-door-open"></i><p>No rooms found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">
        {{ $rooms->links() }}
    </div>
</div>

@endsection