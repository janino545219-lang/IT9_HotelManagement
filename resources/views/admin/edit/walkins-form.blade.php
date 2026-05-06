@extends('admin.layout')
@section('title', isset($walkin) ? 'Edit Walk-In' : 'New Walk-In')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.walkins.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Walk-Ins
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($walkin) ? 'Edit Walk-In' : 'New Walk-In Entry' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($walkin) ? route('admin.walkins.update', $walkin->walkin_id) : route('admin.walkins.store') }}">
            @csrf
            @if(isset($walkin)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Guest Name</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', $walkin->guest_name ?? '') }}" required placeholder="Full name">
                    @error('guest_name')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $walkin->phone ?? '') }}" placeholder="09XXXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Number of Guests</label>
                    <input type="number" name="num_guests" value="{{ old('num_guests', $walkin->num_guests ?? 1) }}" required min="1">
                </div>
                <div class="form-group">
                    <label>Handled By (Employee)</label>
                    <select name="employee_id" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}" {{ old('employee_id', $walkin->employee_id ?? '') === $emp->employee_id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->position }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Check-In Date</label>
                    <input type="date" name="check_in_date" value="{{ old('check_in_date', isset($walkin) ? $walkin->check_in_date : '') }}" required>
                </div>
                <div class="form-group">
                    <label>Check-Out Date</label>
                    <input type="date" name="check_out_date" value="{{ old('check_out_date', isset($walkin) ? $walkin->check_out_date : '') }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['active','checked_out','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $walkin->status ?? 'active') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($walkin) ? 'Update Walk-In' : 'Save Walk-In' }}
                </button>
                <a href="{{ route('admin.walkins.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection