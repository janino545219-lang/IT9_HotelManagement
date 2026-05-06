@extends('admin.layout')
@section('title', 'Guest Details')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.guests.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Guests
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;align-items:start;">

    <!-- Profile Card -->
    <div class="section-card">
        <div style="padding:32px 24px;text-align:center;border-bottom:1px solid #F0EBE3;">
            <div style="width:64px;height:64px;background:#F0EBE3;border:2px solid #C9A84C;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:24px;color:#C9A84C;margin:0 auto 14px;">
                {{ strtoupper(substr($guest->first_name,0,1)) }}
            </div>
            <div style="font-size:16px;font-weight:600;color:#1A1A1A;">{{ $guest->first_name }} {{ $guest->last_name }}</div>
            <div style="font-size:11px;color:#C9A84C;letter-spacing:2px;text-transform:uppercase;margin-top:4px;">Guest</div>
        </div>
        <div style="padding:16px 24px;">
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $guest->email ?? '—' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Phone</div>
                <div class="detail-value">{{ $guest->phone ?? '—' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Member Since</div>
                <div class="detail-value">{{ $guest->created_at->format('F d, Y') }}</div>
            </div>
        </div>
        <div style="padding:16px 24px;border-top:1px solid #F0EBE3;display:flex;gap:8px;">
            <a href="{{ route('admin.guests.edit', $guest->guest_id) }}" class="btn btn-gold btn-sm" style="flex:1;justify-content:center;">Edit</a>
            <form method="POST" action="{{ route('admin.guests.destroy', $guest->guest_id) }}" onsubmit="return confirm('Delete this guest?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </div>
    </div>

    <!-- Reservations -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">Reservation History</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Guests</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guest->reservations ?? [] as $r)
                <tr>
                    <td>Room {{ $r->room->room_number ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }}</td>
                    <td>{{ $r->num_guests }}</td>
                    <td>₱{{ number_format($r->total_amount, 2) }}</td>
                    <td>
                        @if($r->status==='confirmed') <span class="badge badge-green">Confirmed</span>
                        @elseif($r->status==='pending') <span class="badge badge-yellow">Pending</span>
                        @elseif($r->status==='cancelled') <span class="badge badge-red">Cancelled</span>
                        @else <span class="badge badge-grey">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No reservations</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection