@extends('staff.layouts.app')
@section('title', 'Reservations')
@section('page-title', 'Reservations')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
     style="background:#f0fff4; border:1px solid #c8e6c9; color:#2e7d32;">
    ✓ {{ session('success') }}
</div>
@endif

{{-- DESKTOP TABLE --}}
<div class="hidden lg:block bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100" style="background:#f5f0e8">
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Guest</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Room</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Check-In</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Check-Out</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Guests</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Total</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Status</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Payment</th>
                <th class="text-left px-6 py-4 text-xs uppercase tracking-widest text-gray-500">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">
                    {{ $r->first_name }} {{ $r->last_name }}
                    @if($r->phone)<div class="text-xs text-gray-400">{{ $r->phone }}</div>@endif
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $r->room_number }}
                    <div class="text-xs text-gray-400">{{ ucfirst($r->room_type) }}</div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $r->num_guests }}</td>
                <td class="px-6 py-4 font-medium" style="color:#C9A84C;">₱{{ number_format($r->invoice_total ?? $r->total_amount, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $r->status === 'confirmed'   ? 'bg-green-100 text-green-700'  :
                          ($r->status === 'pending'     ? 'bg-yellow-100 text-yellow-700' :
                          ($r->status === 'checked_in'  ? 'bg-blue-100 text-blue-700'    :
                          ($r->status === 'checked_out' ? 'bg-purple-100 text-purple-700' :
                           'bg-gray-100 text-gray-600'))) }}">
                        {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($r->payment_status === 'paid')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Paid</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2 flex-wrap">
                        @if($r->status === 'pending')
                        <form method="POST" action="{{ route('staff.reservations.confirm', $r->reservation_id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded text-xs font-medium text-white transition"
                                style="background:#1a1a1a;"
                                onmouseover="this.style.background='#C9A84C'"
                                onmouseout="this.style.background='#1a1a1a'">Confirm</button>
                        </form>
                        @endif
                        @if($r->status === 'confirmed')
                        <form method="POST" action="{{ route('staff.reservations.checkin', $r->reservation_id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded text-xs font-medium text-white transition bg-blue-600 hover:bg-blue-700">Check-in</button>
                        </form>
                        @endif
                        @if($r->status === 'checked_in')
                        <a href="{{ route('staff.reservations.checkout', $r->reservation_id) }}"
                           class="px-3 py-1 rounded text-xs font-medium text-white transition bg-red-600 hover:bg-red-700">Check-out & Bill</a>
                        @endif
                        @if($r->invoice_id && $r->payment_status !== 'paid')
                        <form method="POST" action="{{ route('staff.reservations.markPaid', $r->invoice_id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded text-xs font-medium text-white transition"
                                style="background:#2e7d32;"
                                onmouseover="this.style.background='#1b5e20'"
                                onmouseout="this.style.background='#2e7d32'">Mark Paid</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-12 text-center text-gray-400">No reservations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MOBILE CARD LIST --}}
<div class="lg:hidden space-y-3">
    @forelse($reservations as $r)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-4 pt-4 pb-3 flex justify-between items-start">
            <div>
                <p class="font-semibold text-gray-800">{{ $r->first_name }} {{ $r->last_name }}</p>
                @if($r->phone)<p class="text-xs text-gray-400 mt-0.5">{{ $r->phone }}</p>@endif
            </div>
            <div class="flex flex-col items-end gap-1.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $r->status === 'confirmed'   ? 'bg-green-100 text-green-700'  :
                      ($r->status === 'pending'     ? 'bg-yellow-100 text-yellow-700' :
                      ($r->status === 'checked_in'  ? 'bg-blue-100 text-blue-700'    :
                      ($r->status === 'checked_out' ? 'bg-purple-100 text-purple-700' :
                       'bg-gray-100 text-gray-600'))) }}">
                    {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                </span>
                @if($r->payment_status === 'paid')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Paid</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
                @endif
            </div>
        </div>

        {{-- Card Details --}}
        <div class="px-4 pb-3 grid grid-cols-2 gap-x-4 gap-y-2 text-xs border-t border-gray-100 pt-3">
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Room</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ $r->room_number }} <span class="text-gray-400">({{ ucfirst($r->room_type) }})</span></p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Guests</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ $r->num_guests }}</p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Check-In</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($r->check_in_date)->format('M d, Y') }}</p>
            </div>
            <div>
                <span class="text-gray-400 uppercase tracking-wide">Check-Out</span>
                <p class="font-medium text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Card Footer --}}
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <span class="font-bold text-[#b8972e]">₱{{ number_format($r->invoice_total ?? $r->total_amount, 2) }}</span>
            <div class="flex gap-2 flex-wrap justify-end">
                @if($r->status === 'pending')
                <form method="POST" action="{{ route('staff.reservations.confirm', $r->reservation_id) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-gray-800 hover:bg-[#C9A84C] transition">Confirm</button>
                </form>
                @endif
                @if($r->status === 'confirmed')
                <form method="POST" action="{{ route('staff.reservations.checkin', $r->reservation_id) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Check-in</button>
                </form>
                @endif
                @if($r->status === 'checked_in')
                <a href="{{ route('staff.reservations.checkout', $r->reservation_id) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-red-600 hover:bg-red-700 transition">Check-out & Bill</a>
                @endif
                @if($r->invoice_id && $r->payment_status !== 'paid')
                <form method="POST" action="{{ route('staff.reservations.markPaid', $r->invoice_id) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-green-700 hover:bg-green-800 transition">Mark Paid</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400">
        No reservations found.
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $reservations->links() }}</div>
@endsection