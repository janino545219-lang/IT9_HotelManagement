@extends('staff.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    {{-- Reservations --}}
    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Reservations</p>
                <p class="text-4xl font-bold text-gray-800">{{ $totalReservations }}</p>
                <p class="text-xs text-gray-400 mt-2">Total bookings</p>
            </div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#f5f0e8">
                <svg class="w-5 h-5" style="color:#b8972e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Guests --}}
    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Total Guests</p>
                <p class="text-4xl font-bold text-gray-800">{{ $totalGuests }}</p>
                <p class="text-xs text-gray-400 mt-2">Registered accounts</p>
            </div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#f5f0e8">
                <svg class="w-5 h-5" style="color:#b8972e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- My Role --}}
    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm">
        <div class="flex justify-between items-start">
            <div class="min-w-0 mr-3">
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">My Role</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800 break-words">{{ session('staff_role') }}</p>
                <p class="text-xs text-gray-400 mt-2 truncate">{{ session('staff_email') }}</p>
            </div>
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#f5f0e8">
                <svg class="w-5 h-5" style="color:#b8972e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="bg-white rounded-xl shadow-sm p-5 sm:p-6">
    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-widest mb-4">Quick Actions</h2>
    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
        <a href="{{ route('staff.reservations.index') }}"
            class="flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition w-full sm:w-auto">
            View Reservations
        </a>
        <a href="{{ route('staff.walkins.index') }}"
            class="flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition w-full sm:w-auto">
            View Walk-Ins
        </a>
    </div>
</div>
@endsection