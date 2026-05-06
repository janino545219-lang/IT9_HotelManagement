@extends('staff.layouts.app')
@section('title', 'Walk-Ins')
@section('page-title', 'Walk-Ins')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
     style="background:#f0fff4; border:1px solid #c8e6c9; color:#2e7d32;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="mb-4 flex items-center justify-between">
    <span class="text-sm text-gray-400">{{ $walkins->total() }} walk-ins total</span>
    <a href="{{ route('staff.walkins.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium text-white transition"
       style="background:#b8972e;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">Record Walk-In</span>
        <span class="sm:hidden">Add</span>
    </a>
</div>

{{-- DESKTOP TABLE --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100" style="background:#f5f0e8">
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Guest Name</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Phone</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Guests</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Employee</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Check-In</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Check-Out</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Status</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($walkins as $walkin)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $walkin->guest_name }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $walkin->phone ?? '—' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $walkin->num_guests }}</td>
                <td class="px-6 py-4 text-gray-600">
                    @if($walkin->employee)
                        {{ $walkin->employee->first_name }} {{ $walkin->employee->last_name }}
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $walkin->check_in_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $walkin->check_out_date->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    @if($walkin->status === 'checked_in')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Checked In</span>
                    @elseif($walkin->status === 'checked_out')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Checked Out</span>
                    @elseif($walkin->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ ucfirst($walkin->status) }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2 flex-wrap">
                        @if($walkin->status === 'checked_in')
                        <a href="{{ route('staff.walkins.checkout', $walkin->walkin_id) }}"
                           class="px-3 py-1 rounded text-xs font-medium text-white transition bg-red-600 hover:bg-red-700">Check-out & Bill</a>
                        @endif
                        <a href="{{ route('staff.walkins.edit', $walkin->walkin_id) }}"
                           class="px-3 py-1 rounded text-xs font-medium text-white transition"
                           style="background:#1a1a1a;"
                           onmouseover="this.style.background='#b8972e'"
                           onmouseout="this.style.background='#1a1a1a'">Edit</a>
                        <form method="POST" action="{{ route('staff.walkins.destroy', $walkin->walkin_id) }}" onsubmit="return confirm('Delete this walk-in record?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 rounded text-xs font-medium text-white transition"
                                style="background:#c62828;"
                                onmouseover="this.style.background='#b71c1c'"
                                onmouseout="this.style.background='#c62828'">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-400">No walk-in records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MOBILE CARD LIST --}}
<div class="md:hidden space-y-3">
    @forelse($walkins as $walkin)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 pt-4 pb-3 flex justify-between items-start">
            <div>
                <p class="font-semibold text-gray-800">{{ $walkin->guest_name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $walkin->phone ?? 'No phone' }}</p>
            </div>
            @if($walkin->status === 'checked_in')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Checked In</span>
            @elseif($walkin->status === 'checked_out')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Checked Out</span>
            @elseif($walkin->status === 'pending')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ ucfirst($walkin->status) }}</span>
            @endif
        </div>

        <div class="px-4 pb-3 grid grid-cols-2 gap-x-4 gap-y-2 text-xs border-t border-gray-100 pt-3">
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Guests</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ $walkin->num_guests }}</p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Employee</span>
                <p class="font-medium text-gray-700 mt-0.5">
                    {{ $walkin->employee ? $walkin->employee->first_name . ' ' . $walkin->employee->last_name : '—' }}
                </p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Check-In</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ $walkin->check_in_date->format('M d, Y') }}</p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Check-Out</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ $walkin->check_out_date->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 flex-wrap">
            @if($walkin->status === 'checked_in')
            <a href="{{ route('staff.walkins.checkout', $walkin->walkin_id) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-red-600 hover:bg-red-700 transition">Check-out & Bill</a>
            @endif
            <a href="{{ route('staff.walkins.edit', $walkin->walkin_id) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-gray-800 hover:bg-[#b8972e] transition">Edit</a>
            <form method="POST" action="{{ route('staff.walkins.destroy', $walkin->walkin_id) }}" onsubmit="return confirm('Delete this walk-in record?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-red-800 hover:bg-red-900 transition">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400">
        No walk-in records found.
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $walkins->links() }}</div>
@endsection