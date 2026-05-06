@extends('staff.layouts.app')
@section('title', 'Rooms')
@section('page-title', 'Rooms')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="mb-6">
    <p class="text-gray-500 text-sm">All hotel rooms and availability</p>
</div>

{{-- Room Cards Grid --}}
@if($rooms->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-16 text-center">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#f5f0e8">
            <svg class="w-8 h-8" style="color:#b8972e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
            </svg>
        </div>
        <p class="text-gray-400 text-sm">No rooms found. Add rooms from the Create Room module.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            {{-- Room Image --}}
            <div class="h-48 w-full overflow-hidden" style="background:#f5f0e8">
                @if($room->image)
                    <img src="{{ asset($room->image) }}"
                         alt="Room {{ $room->room_number }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-300 text-xs mt-2">No image</p>
                    </div>
                @endif
            </div>

            {{-- Room Info --}}
            <div class="p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $room->room_number }}</h3>
                        <p class="text-sm text-gray-500">{{ ucfirst($room->room_type) }} · Floor {{ $room->floor_number }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : ($room->status === 'occupied' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($room->status) }}
                    </span>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400">Price/Night</p>
                        <p class="font-bold" style="color:#b8972e">₱{{ number_format($room->price_per_night, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Capacity</p>
                        <p class="font-semibold text-gray-700">{{ $room->capacity }} pax</p>
                    </div>
                </div>

                @if($room->amenities)
                <p class="text-xs text-gray-400 mt-3 truncate">🛎 {{ $room->amenities }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection