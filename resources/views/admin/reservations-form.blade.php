@extends('admin.layout')
@section('title', isset($reservation) ? 'Edit Reservation' : 'New Reservation')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reservations
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($reservation) ? 'Edit Reservation' : 'New Reservation' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($reservation) ? route('admin.reservations.update', $reservation->reservation_id) : route('admin.reservations.store') }}">
            @csrf
            @if(isset($reservation)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Guest</label>
                    <select name="guest_id" required>
                        <option value="">Select guest</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->guest_id }}" {{ old('guest_id', $reservation->guest_id ?? '') === $guest->guest_id ? 'selected' : '' }}>
                                {{ $guest->first_name }} {{ $guest->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Room</label>
                    <select name="room_id" required>
                        <option value="">Select room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->room_id }}" {{ old('room_id', $reservation->room_id ?? '') === $room->room_id ? 'selected' : '' }}>
                                Room {{ $room->room_number }} — {{ $room->room_type }} (₱{{ number_format($room->price_per_night,2) }}/night)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Employee Assigned</label>
                    <select name="employee_id" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}" {{ old('employee_id', $reservation->employee_id ?? '') === $emp->employee_id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->position }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Number of Guests</label>
                    <input type="number" name="num_guests" value="{{ old('num_guests', $reservation->num_guests ?? 1) }}" required min="1">
                </div>
                <div class="form-group">
                    <label>Check-In Date</label>
                    <input type="date" name="check_in_date" value="{{ old('check_in_date', isset($reservation) ? $reservation->check_in_date : '') }}" required>
                </div>
                <div class="form-group">
                    <label>Check-Out Date</label>
                    <input type="date" name="check_out_date" value="{{ old('check_out_date', isset($reservation) ? $reservation->check_out_date : '') }}" required>
                </div>
                <div class="form-group">
                    <label>Total Amount (₱)</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount', $reservation->total_amount ?? '') }}" required step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['pending','confirmed','checked_out','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $reservation->status ?? 'pending') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($reservation) ? 'Update' : 'Save Reservation' }}
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection