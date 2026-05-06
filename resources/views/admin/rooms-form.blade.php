@extends('admin.layout')
@section('title', isset($room) ? 'Edit Room' : 'Add Room')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Rooms
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($room) ? 'Edit Room' : 'Add New Room' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($room) ? route('admin.rooms.update', $room->room_id) : route('admin.rooms.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($room)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Room Number</label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number ?? '') }}" required placeholder="e.g. 101">
                    @error('room_number')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type" required>
                        <option value="">Select type</option>
                        @foreach(['Standard','Deluxe','Suite','Executive','Family'] as $type)
                            <option value="{{ $type }}" {{ old('room_type', $room->room_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Floor Number</label>
                    <input type="number" name="floor_number" value="{{ old('floor_number', $room->floor_number ?? '') }}" required min="1" placeholder="e.g. 1">
                </div>
                <div class="form-group">
                    <label>Price Per Night (₱)</label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night ?? '') }}" required step="0.01" min="0" placeholder="e.g. 2500.00">
                </div>
                <div class="form-group">
                    <label>Capacity (guests)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity ?? '') }}" required min="1" placeholder="e.g. 2">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['available','occupied','maintenance'] as $s)
                            <option value="{{ $s }}" {{ old('status', $room->status ?? 'available') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Room Image</label>
                    <input type="file" name="image" accept="image/*" placeholder="Upload room image">
                    @error('image')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                    @if(isset($room) && $room->image)
                        <p style="font-size:12px;color:#7F8C8D;margin-top:8px;">Current image: <a href="{{ asset($room->image) }}" target="_blank">View</a></p>
                    @endif
                </div>
                <div class="form-group full">
                    <label>Amenities</label>
                    <textarea name="amenities" placeholder="e.g. WiFi, Air Conditioning, Mini Bar, TV...">{{ old('amenities', $room->amenities ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($room) ? 'Update Room' : 'Save Room' }}
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection