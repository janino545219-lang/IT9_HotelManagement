@extends('staff.layouts.app')
@section('title', isset($payment) ? 'Edit Payment' : 'New Payment')
@section('page-title', isset($payment) ? 'Edit Payment' : 'New Payment')

@section('content')
<div class="mb-4 flex space-x-4 items-center max-w-3xl mx-auto">
    <a href="{{ route('staff.payments.index') }}" class="text-gray-400 hover:text-[#b8972e] transition flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <p class="text-sm text-gray-500">Fill in the payment details</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm max-w-3xl mx-auto" x-data="paymentForm()">
    <div class="border-b border-gray-100 px-6 py-5">
        <h3 class="text-lg font-medium text-gray-800">Payment Information</h3>
    </div>
    
    <div class="p-4 sm:p-6">
        <form method="POST" action="{{ isset($payment) ? route('staff.payments.update', $payment->payment_id) : route('staff.payments.store') }}">
            @csrf
            @if(isset($payment)) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- INVOICE ID (hidden — pre-filled from checkout flow) -->
                @foreach($invoices as $invoice)
                    @if(old('invoice_id', $payment->invoice_id ?? '') == $invoice->invoice_id)
                        <input type="hidden" name="invoice_id" value="{{ $invoice->invoice_id }}">
                    @endif
                @endforeach
                {{-- If only one invoice available (checkout flow), always use it --}}
                @if(count($invoices) === 1)
                    <input type="hidden" name="invoice_id" value="{{ $invoices->first()->invoice_id }}">
                @endif

                <!-- AMOUNT TO PAY (READ-ONLY — auto-filled from invoice) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Amount to Pay (₱)</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="amount" x-model="amount" required readonly
                            placeholder="0.00"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-700 text-sm font-semibold cursor-not-allowed select-none focus:outline-none">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">Fixed</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Pre-filled from the invoice. Not editable.</p>
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- PAYMENT METHOD -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Payment Method</label>
                    <select name="payment_method" required x-model="method" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">
                        <option value="cash">Walk-In (Cash)</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="ewallet">GCash / Maya</option>
                    </select>
                    @error('payment_method')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- CONDITIONAL: CASH TAB -->
                <div x-show="method === 'cash'" class="col-span-1 md:col-span-2 p-5 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 block border-b border-gray-200 pb-2">Cash Payment Details</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Amount Received (₱)</label>
                            <input type="number" step="0.01" x-model.number="received" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Change (₱)</label>
                            <input type="number" readonly :value="changeFormatted" class="w-full px-4 py-2 rounded-lg border-transparent text-sm bg-gray-100 text-gray-700 font-semibold focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- CONDITIONAL: CARD TAB -->
                <div x-show="method === 'card'" class="col-span-1 md:col-span-2 p-5 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 block border-b border-gray-200 pb-2">Simulate Card Processing</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Card Number</label>
                            <input type="text" placeholder="XXXX XXXX XXXX XXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white" :required="method === 'card'">
                        </div>
                        <div class="col-span-2 md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Expiry Date</label>
                            <input type="text" placeholder="MM/YY" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white" :required="method === 'card'">
                        </div>
                        <div class="col-span-2 md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">CVV</label>
                            <input type="text" placeholder="123" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white" :required="method === 'card'">
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-3">* Card details are simulated and will validate locally on submission.</p>
                </div>

                <!-- CONDITIONAL: E-WALLET TAB -->
                <div x-show="method === 'ewallet'" class="col-span-1 md:col-span-2 p-5 bg-gray-50 rounded-lg border border-gray-200 flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-4 bg-white border border-gray-200 rounded-lg">
                        <div class="w-32 h-32 bg-gray-100 flex items-center justify-center mb-2 border border-[#b8972e]">
                           <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Scan QR to Pay</p>
                    </div>
                    <div class="w-full md:w-2/3">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 block border-b border-gray-200 pb-2">GCash / Maya Processing</h4>
                        <p class="text-xs text-gray-500 mb-4">Please ask the guest to scan the QR code and provide the reference number once the transfer is successful.</p>
                        
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Reference Number</label>
                        <input type="text" name="transaction_id" x-model="refNumber" placeholder="e.g. 1029304958" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white" :required="method === 'ewallet'">
                        @error('transaction_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                    <select name="status" x-model="status" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm font-medium" :class="{'text-green-700 bg-green-50': status === 'completed', 'text-yellow-700 bg-yellow-50': status === 'pending'}">
                        <option value="completed" {{ old('status', $payment->status ?? '') == 'completed' ? 'selected' : '' }}>Completed (Paid)</option>
                        <option value="pending" {{ old('status', $payment->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ old('status', $payment->status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ old('status', $payment->status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <!-- PAID AT -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Paid At</label>
                    <input type="datetime-local" name="paid_at" value="{{ old('paid_at', isset($payment) && $payment->paid_at ? $payment->paid_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-[#b8972e] focus:ring focus:ring-[#b8972e] focus:ring-opacity-20 transition text-sm">
                    @error('paid_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button type="button" @click="processPayment" class="w-full sm:w-auto bg-[#b8972e] hover:bg-[#9a7e25] text-white px-6 py-3 sm:py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ isset($payment) ? 'Update & Confirm Payment' : 'Confirm Payment & Receipt' }}
                    </button>
                    <a href="{{ route('staff.payments.index') }}" class="text-center text-gray-500 hover:text-gray-700 text-sm font-medium transition py-2">Cancel</a>
                </div>
                <div class="hidden sm:block text-xs text-gray-400 font-medium">Auto-updates Invoice Status</div>
            </div>
        </form>
    </div>
</div>

<script>
    function paymentForm() {
        // Auto-initialize amount from the invoice total (passed by PHP since dropdown is removed)
        @php
            $invoiceTotal = '';
            if (isset($payment)) {
                $invoiceTotal = $payment->amount ?? '';
            } elseif (count($invoices) === 1) {
                $invoiceTotal = $invoices->first()->total_amount ?? '';
            }
        @endphp

        return {
            amount: '{{ old('amount', $invoiceTotal) }}',
            method: '{{ old('payment_method', $payment->payment_method ?? 'cash') }}',
            status: '{{ old('status', $payment->status ?? 'completed') }}',
            received: 0,
            refNumber: '{{ old('transaction_id', $payment->transaction_id ?? '') }}',

            get change() {
                const amt = parseFloat(this.amount) || 0;
                const rec = parseFloat(this.received) || 0;
                return rec > amt ? rec - amt : 0;
            },

            get changeFormatted() {
                return this.change.toFixed(2);
            },

            processPayment(e) {
                if(this.method === 'card') {
                    if(!confirm('Simulate Card Terminal processing? Click OK for success, Cancel for failure.')) {
                        this.status = 'failed';
                        alert('Transaction Failed!');
                        e.preventDefault();
                        return false;
                    }
                    this.status = 'completed';
                } else if(this.method === 'ewallet' && !this.refNumber) {
                    this.status = 'pending';
                } else if (this.method === 'cash') {
                    this.status = 'completed';
                    if (this.received < parseFloat(this.amount)) {
                        if(!confirm('Amount received is less than amount to pay. Mark as partial payment?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }
                e.target.closest('form').submit();
            }
        }
    }
</script>
@endsection
