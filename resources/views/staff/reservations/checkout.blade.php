@extends('staff.layouts.app')
@section('title', 'Check Out Guest')
@section('page-title', 'Check Out Guest')

@section('content')
<div class="mb-4 flex space-x-4 items-center max-w-3xl mx-auto">
    <a href="{{ route('staff.reservations.index') }}" class="text-gray-400 hover:text-[#b8972e] transition flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <p class="text-sm text-gray-500">Record check-out and additional charges</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm max-w-3xl mx-auto">
    <div class="border-b border-gray-100 px-6 py-5 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">Check-Out Form</h3>
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Checked In</span>
    </div>
    
    <div class="p-4 sm:p-6">
        <div class="bg-gray-50 rounded-lg p-5 mb-6 border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 border-b border-gray-200 pb-2">Reservation Summary</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Guest Name</span>
                    <span class="font-medium text-gray-800">{{ $reservation->first_name }} {{ $reservation->last_name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Room</span>
                    <span class="font-medium text-gray-800">{{ $reservation->room_number }} ({{ ucfirst($reservation->room_type) }})</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Check-in</span>
                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Check-out</span>
                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</span>
                </div>
            </div>
            @if($invoice)
            <div class="mt-4 pt-4 border-t border-gray-200">
                 <div class="flex justify-between items-center bg-white p-3 rounded border border-gray-100">
                     <span class="text-gray-600 font-medium">Invoice Subtotal</span>
                     <span class="text-md font-bold text-[#b8972e]">₱{{ number_format($invoice->total_amount, 2) }}</span>
                 </div>
            </div>
            @endif
        </div>

        <form method="POST" action="{{ route('staff.reservations.processCheckout', $reservation->reservation_id) }}" x-data="checkoutForm()">
            @csrf
            
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" x-model="addCharges" class="w-4 h-4 text-[#b8972e] border-gray-300 rounded focus:ring-[#b8972e]">
                    <span class="ml-2 text-sm text-gray-700 font-medium">Add charges for damages or extra services</span>
                </label>
            </div>

            <div x-show="addCharges" x-transition class="bg-yellow-50/50 p-5 rounded-lg border border-yellow-100 mb-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Amount (₱)</label>
                    <input type="number" step="0.01" name="additional_charges" x-model="additionalCharges" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Description / Notes</label>
                    <textarea name="additional_charges_notes" x-model="notes" rows="2" placeholder="e.g. Minibar usage, broken glass" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm"></textarea>
                </div>
                <p class="text-xs text-yellow-700 mt-2 italic"><i class="fas fa-info-circle mr-1"></i> These charges will be added to the final invoice.</p>
            </div>

            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg flex justify-between items-center shadow-sm">
                <span class="text-gray-600 font-bold uppercase tracking-wide text-sm">Total Bill</span>
                <span class="text-2xl font-bold text-[#b8972e]" x-text="'₱' + totalBill"></span>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="w-full sm:w-auto bg-[#b8972e] hover:bg-[#9a7e25] text-white px-6 py-3 sm:py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Process Check-out & Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkoutForm() {
        return {
            subtotal: {{ $invoice ? $invoice->subtotal : '0' }},
            addCharges: false,
            additionalCharges: 0,
            notes: '',
            
            get totalBill() {
                const room = parseFloat(this.subtotal) || 0;
                const extra = this.addCharges ? (parseFloat(this.additionalCharges) || 0) : 0;
                return (room + extra).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }
</script>
@endsection
