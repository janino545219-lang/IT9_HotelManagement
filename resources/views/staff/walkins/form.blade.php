@extends('staff.layouts.app')
@section('title', isset($walkin) ? 'Edit Walk-In' : 'Record Walk-In')
@section('page-title', isset($walkin) ? 'Edit Walk-In' : 'Record Walk-In')

@section('content')

<div class="mb-6">
    <a href="{{ route('staff.walkins.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Walk-Ins
    </a>
</div>

@if($errors->any())
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
     style="background:#fff0f0; border:1px solid #f5c6c6; color:#c62828;">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100" style="background:#f5f0e8">
        <h2 class="text-lg font-semibold text-gray-800">{{ isset($walkin) ? 'Edit Walk-In' : 'Record New Walk-In' }}</h2>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ isset($walkin) ? route('staff.walkins.update', $walkin->walkin_id) : route('staff.walkins.store') }}">
            @csrf
            @if(isset($walkin)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-data="walkinForm()">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Guest Name</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', $walkin->guest_name ?? '') }}" required
                           placeholder="e.g. John Doe"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $walkin->phone ?? '') }}"
                           placeholder="e.g. 09123456789"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Number of Guests</label>
                    <input type="number" name="num_guests" value="{{ old('num_guests', $walkin->num_guests ?? '1') }}" required min="1"
                           placeholder="e.g. 2"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Handled By (Employee)</label>
                    <select name="employee_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_id }}"
                                {{ old('employee_id', $walkin->employee_id ?? '') === $employee->employee_id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Room Assignment --}}
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Assign Room</label>
                    <select name="room_id" x-on:change="selectRoom($event)"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                        <option value="">-- No Room Assigned --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->room_id }}"
                                data-price="{{ $room->price_per_night }}"
                                data-type="{{ $room->room_type }}"
                                data-capacity="{{ $room->capacity }}"
                                {{ old('room_id', $walkin->room_id ?? '') === $room->room_id ? 'selected' : '' }}>
                                Room {{ $room->room_number }} — {{ ucfirst($room->room_type) }} — ₱{{ number_format($room->price_per_night, 2) }}/night (Cap: {{ $room->capacity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Room Info Preview --}}
                <div class="md:col-span-2" x-show="selectedRoom.price > 0" x-transition>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex flex-wrap gap-6 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Room Type</p>
                            <p class="font-semibold text-gray-800" x-text="selectedRoom.type"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Rate / Night</p>
                            <p class="font-semibold text-[#b8972e]" x-text="'₱' + selectedRoom.price.toLocaleString('en-US', {minimumFractionDigits:2})"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Capacity</p>
                            <p class="font-semibold text-gray-800" x-text="selectedRoom.capacity + ' guests'"></p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Check-In Date</label>
                    <input type="date" name="check_in_date" x-model="checkIn" value="{{ old('check_in_date', isset($walkin) ? $walkin->check_in_date->format('Y-m-d') : '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Check-Out Date</label>
                    <input type="date" name="check_out_date" x-model="checkOut" value="{{ old('check_out_date', isset($walkin) ? $walkin->check_out_date->format('Y-m-d') : '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                </div>

                {{-- Estimated Total --}}
                <div class="md:col-span-2" x-show="estimatedTotal > 0" x-transition>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-5 py-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Estimated Stay Cost</p>
                            <p class="text-[11px] text-gray-400 mt-0.5" x-text="nights + ' night(s) × ₱' + selectedRoom.price.toLocaleString('en-US',{minimumFractionDigits:2}) + '/night'"></p>
                        </div>
                        <p class="text-2xl font-bold text-[#b8972e]" x-text="'₱' + estimatedTotal.toLocaleString('en-US',{minimumFractionDigits:2})"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 font-semibold mb-2">Status</label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600">
                        @foreach(['pending', 'checked_in', 'checked_out'] as $s)
                            <option value="{{ $s }}" {{ old('status', $walkin->status ?? 'pending') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

<script>
function walkinForm() {
    return {
        selectedRoom: { price: 0, type: '', capacity: '' },
        checkIn: '{{ old('check_in_date', isset($walkin) ? $walkin->check_in_date->format('Y-m-d') : '') }}',
        checkOut: '{{ old('check_out_date', isset($walkin) ? $walkin->check_out_date->format('Y-m-d') : '') }}',

        selectRoom(event) {
            const opt = event.target.selectedOptions[0];
            this.selectedRoom = {
                price: parseFloat(opt.dataset.price) || 0,
                type: opt.dataset.type || '',
                capacity: opt.dataset.capacity || ''
            };
        },

        get nights() {
            if (!this.checkIn || !this.checkOut) return 0;
            const diff = (new Date(this.checkOut) - new Date(this.checkIn)) / (1000 * 60 * 60 * 24);
            return diff > 0 ? diff : 0;
        },

        get estimatedTotal() {
            return this.nights * this.selectedRoom.price;
        }
    }
}
</script>

            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white transition"
                    style="background:#b8972e;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($walkin) ? 'Update Walk-In' : 'Record Walk-In' }}
                </button>
                <a href="{{ route('staff.walkins.index') }}"
                   class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
