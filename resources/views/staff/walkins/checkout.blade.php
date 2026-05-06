@extends('staff.layouts.app')
@section('title', 'Check Out Walk-In')
@section('page-title', 'Check Out Walk-In')

@section('content')
<div class="mb-6 flex space-x-4 items-center">
    <a href="{{ route('staff.walkins.index') }}" class="text-gray-400 hover:text-[#b8972e] transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <p class="text-sm text-gray-500">Record check-out and calculate final bill</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm max-w-3xl">
    <div class="border-b border-gray-100 px-6 py-5 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">Check-Out Form</h3>
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Walk-In</span>
    </div>
    
    <div class="p-6">
        {{-- Walk-In Summary --}}
        <div class="bg-gray-50 rounded-lg p-5 mb-6 border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 border-b border-gray-200 pb-2">Walk-In Summary</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Guest Name</span>
                    <span class="font-medium text-gray-800">{{ $walkin->guest_name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Number of Guests</span>
                    <span class="font-medium text-gray-800">{{ $walkin->num_guests }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Check-in</span>
                    <span class="text-gray-700">{{ $walkin->check_in_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Check-out</span>
                    <span class="text-gray-700">{{ $walkin->check_out_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Room Details (if assigned) --}}
        @if($walkin->room)
        @php
            $nights = $walkin->check_in_date->diffInDays($walkin->check_out_date);
        @endphp
        <div class="mb-6 rounded-lg border border-[#e8d9a0] bg-yellow-50/60 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#b8972e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Assigned Room
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Room No.</span>
                    <span class="font-bold text-gray-800">{{ $walkin->room->room_number }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Type</span>
                    <span class="font-medium text-gray-700">{{ ucfirst($walkin->room->room_type) }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Rate / Night</span>
                    <span class="font-semibold text-[#b8972e]">₱{{ number_format($walkin->room->price_per_night, 2) }}</span>
                </div>
                <div>
                    <span class="text-gray-500 text-xs uppercase tracking-wider block mb-1">Nights</span>
                    <span class="font-medium text-gray-700">{{ $nights }} night(s)</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-yellow-200 flex justify-between items-center">
                <span class="text-xs text-gray-500">{{ $nights }} night(s) × ₱{{ number_format($walkin->room->price_per_night, 2) }}/night</span>
                <span class="text-lg font-bold text-[#b8972e]">Room Total: ₱{{ number_format($autoSubtotal, 2) }}</span>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('staff.walkins.processCheckout', $walkin->walkin_id) }}" x-data="checkoutForm()">
            @csrf
            
            <div class="mb-6 bg-white p-5 rounded-lg border border-gray-200 shadow-sm space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Final Billing Details</h4>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Room / Baseline Rate (₱)</label>
                    <input type="number" step="0.01" name="room_amount" x-model="roomAmount" required 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">
                    @if($walkin->room)
                    <p class="text-[10px] text-green-600 mt-1">✓ Auto-filled based on room rate × {{ $walkin->check_in_date->diffInDays($walkin->check_out_date) }} night(s)</p>
                    @else
                    <p class="text-[10px] text-gray-400 mt-1">Please enter the agreed total rate for this walk-in's stay.</p>
                    @endif
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="addCharges" class="w-4 h-4 text-[#b8972e] border-gray-300 rounded focus:ring-[#b8972e]">
                        <span class="ml-2 text-sm text-gray-700 font-medium">Add charges for damages or extra services</span>
                    </label>
                </div>

                <div x-show="addCharges" x-transition class="bg-yellow-50/50 p-5 rounded-lg border border-yellow-100 mb-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Amount (₱)</label>
                        <input type="number" step="0.01" name="additional_charges" x-model="additionalCharges" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Description / Notes</label>
                        <textarea name="additional_charges_notes" rows="2" placeholder="e.g. Minibar usage, broken glass" 
                                  class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">{{ $invoice ? $invoice->additional_charges_notes : '' }}</textarea>
                    </div>
                    <p class="text-xs text-yellow-700 italic">These charges will be combined with the room rate.</p>
                </div>
            </div>

            {{-- Bill Breakdown --}}
            <div class="mb-6 rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Bill Breakdown</p>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="flex justify-between px-4 py-3 text-sm">
                        <span class="text-gray-600">Room / Baseline Rate</span>
                        <span class="font-medium text-gray-800" x-text="'₱' + (parseFloat(roomAmount)||0).toLocaleString('en-US',{minimumFractionDigits:2})"></span>
                    </div>
                    <div class="flex justify-between px-4 py-3 text-sm" x-show="addCharges && additionalCharges > 0">
                        <span class="text-gray-600">Additional Charges</span>
                        <span class="font-medium text-red-600" x-text="'+ ₱' + (parseFloat(additionalCharges)||0).toLocaleString('en-US',{minimumFractionDigits:2})"></span>
                    </div>
                    <div class="flex justify-between px-4 py-4 bg-white">
                        <span class="font-bold text-gray-700 uppercase tracking-wide text-sm">Total Bill</span>
                        <span class="text-2xl font-bold text-[#b8972e]" x-text="'₱' + totalBill"></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-[#b8972e] hover:bg-[#9a7e25] text-white px-6 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Process Check-out & Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkoutForm() {
        return {
            roomAmount: {{ $invoice ? $invoice->subtotal : $autoSubtotal }},
            addCharges: {{ $invoice && $invoice->additional_charges > 0 ? 'true' : 'false' }},
            additionalCharges: {{ $invoice ? $invoice->additional_charges : '0' }},
            
            get totalBill() {
                const room = parseFloat(this.roomAmount) || 0;
                const extra = this.addCharges ? (parseFloat(this.additionalCharges) || 0) : 0;
                return (room + extra).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }
</script>
@endsection
