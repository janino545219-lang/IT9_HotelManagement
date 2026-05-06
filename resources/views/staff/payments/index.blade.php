@extends('staff.layouts.app')
@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="mb-5 flex justify-between items-center">
    <p class="text-sm text-gray-500">Manage online and walk-in payments</p>
    <a href="{{ route('staff.payments.create') }}" class="bg-[#b8972e] hover:bg-[#9a7e25] text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden sm:inline">New Payment</span>
        <span class="sm:hidden">New</span>
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg text-sm flex items-center gap-2 border border-green-200">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('success') }}
    </div>
@endif

{{-- DESKTOP TABLE --}}
<div class="hidden md:block bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                <th class="px-6 py-4 font-medium">Payment ID</th>
                <th class="px-6 py-4 font-medium">Invoice ID</th>
                <th class="px-6 py-4 font-medium">Guest</th>
                <th class="px-6 py-4 font-medium">Amount</th>
                <th class="px-6 py-4 font-medium">Method</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-100">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ strtoupper(substr($payment->payment_id, 0, 8)) }}</td>
                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ strtoupper(substr($payment->invoice_id, 0, 8)) }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">
                    {{ $payment->invoice && $payment->invoice->guest ? $payment->invoice->guest->first_name . ' ' . $payment->invoice->guest->last_name : ($payment->invoice && $payment->invoice->walkin ? $payment->invoice->walkin->guest_name : 'Unknown') }}
                </td>
                <td class="px-6 py-4 text-gray-700">₱{{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-4 text-gray-500 capitalize">{{ $payment->payment_method }}</td>
                <td class="px-6 py-4">
                    @if($payment->status === 'completed' || $payment->status === 'paid')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Completed</span>
                    @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 capitalize">{{ $payment->status }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('staff.payments.receipt', $payment->payment_id) }}" class="p-1.5 text-gray-400 hover:text-[#b8972e] rounded transition" title="Print Receipt">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </a>
                        <a href="{{ route('staff.payments.edit', $payment->payment_id) }}" class="p-1.5 text-gray-400 hover:text-[#b8972e] rounded transition" title="Edit Payment">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <p>No payments recorded yet.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $payments->links() }}
        </div>
    @endif
</div>

{{-- MOBILE CARD LIST --}}
<div class="md:hidden space-y-3">
    @forelse($payments as $payment)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <div class="flex justify-between items-start mb-3">
            <div>
                <p class="font-semibold text-gray-800 text-sm">
                    {{ $payment->invoice && $payment->invoice->guest ? $payment->invoice->guest->first_name . ' ' . $payment->invoice->guest->last_name : ($payment->invoice && $payment->invoice->walkin ? $payment->invoice->walkin->guest_name : 'Unknown') }}
                </p>
                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ strtoupper(substr($payment->payment_id, 0, 8)) }}</p>
            </div>
            @if($payment->status === 'completed' || $payment->status === 'paid')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
            @elseif($payment->status === 'pending')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize">{{ $payment->status }}</span>
            @endif
        </div>
        <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-3">
            <div>
                <span class="text-[#b8972e] font-bold text-base">₱{{ number_format($payment->amount, 2) }}</span>
                <span class="text-gray-400 text-xs ml-2 capitalize">{{ $payment->payment_method }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.payments.receipt', $payment->payment_id) }}" class="text-gray-400 hover:text-[#b8972e] transition" title="Receipt">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </a>
                <a href="{{ route('staff.payments.edit', $payment->payment_id) }}" class="text-gray-400 hover:text-[#b8972e] transition" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        No payments recorded yet.
    </div>
    @endforelse
    @if($payments->hasPages())
        <div class="pt-2">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
