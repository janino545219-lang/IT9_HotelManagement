@extends('admin.layout')
@section('title', 'Reservations')

@section('content')

<div class="filter-bar" style="align-items: center;">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search reservations...">
    </div>
    <div style="display: flex; gap: 12px; margin-left: auto; align-items: center;">
        <select style="width: auto;">
            <option value="">All Status</option>
            <option>Confirmed</option>
            <option>Pending</option>
            <option>Cancelled</option>
            <option>Checked-out</option>
        </select>
        <a href="{{ route('admin.reservations.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> New Reservation
        </a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Reservations</div>
        <span style="font-size:12px;color:#AAA;">{{ $reservations->total() ?? 0 }} total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Guests</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
            <tr>
                <td>{{ $r->guest->first_name ?? '' }} {{ $r->guest->last_name ?? '' }}</td>
                <td>Room {{ $r->room->room_number ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }}</td>
                <td>{{ $r->num_guests }}</td>
                <td>₱{{ number_format($r->total_amount, 2) }}</td>
                <td>
                    @if($r->status==='confirmed') <span class="badge badge-green">Confirmed</span>
                    @elseif($r->status==='pending') <span class="badge badge-yellow">Pending</span>
                    @elseif($r->status==='cancelled') <span class="badge badge-red">Cancelled</span>
                    @elseif($r->status==='checked_out') <span class="badge badge-blue">Checked Out</span>
                    @else <span class="badge badge-grey">{{ ucfirst($r->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.reservations.show', $r->reservation_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.reservations.edit', $r->reservation_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.reservations.destroy', $r->reservation_id) }}" onsubmit="return confirm('Delete this reservation?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No reservations found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $reservations->links() }}</div>
</div>

@endsection