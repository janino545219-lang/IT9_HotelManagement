@extends('admin.layout')
@section('title', isset($staffMember) ? 'Edit Staff' : 'Add Staff')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Staff
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($staffMember) ? 'Edit Staff Member' : 'Add New Staff' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($staffMember) ? route('admin.staff.update', $staffMember->staff_id) : route('admin.staff.store') }}">
            @csrf
            @if(isset($staffMember)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $staffMember->first_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $staffMember->last_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" name="role" value="{{ old('role', $staffMember->role ?? '') }}" required placeholder="e.g. Housekeeper, Concierge">
                </div>
                <div class="form-group">
                    <label>Shift</label>
                    <select name="shift" required>
                        @foreach(['morning','afternoon','night'] as $shift)
                            <option value="{{ $shift }}" {{ old('shift', $staffMember->shift ?? '') === $shift ? 'selected' : '' }}>{{ ucfirst($shift) }}</option>
                        @endforeach
                    </select>
                </div>
                @if(!isset($staffMember))
                <div class="form-group">
                    <label>Email (Login)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                @endif
                <div class="form-group">
                    <label>Availability</label>
                    <select name="is_available">
                        <option value="1" {{ old('is_available', $staffMember->is_available ?? 1) == 1 ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ old('is_available', $staffMember->is_available ?? 1) == 0 ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($staffMember) ? 'Update Staff' : 'Save Staff' }}
                </button>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection