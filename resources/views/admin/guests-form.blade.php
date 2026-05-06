@extends('admin.layout')
@section('title', isset($guest) ? 'Edit Guest' : 'Add Guest')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.guests.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Guests
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($guest) ? 'Edit Guest' : 'Add New Guest' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($guest) ? route('admin.guests.update', $guest->guest_id) : route('admin.guests.store') }}">
            @csrf
            @if(isset($guest)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $guest->first_name ?? '') }}" required placeholder="First name">
                    @error('first_name')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $guest->last_name ?? '') }}" required placeholder="Last name">
                    @error('last_name')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $guest->email ?? '') }}" placeholder="email@example.com">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $guest->phone ?? '') }}" placeholder="09XXXXXXXXX">
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($guest) ? 'Update Guest' : 'Save Guest' }}
                </button>
                <a href="{{ route('admin.guests.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection