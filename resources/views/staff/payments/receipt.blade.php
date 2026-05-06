<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ strtoupper(substr($payment->payment_id, 0, 8)) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-border { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-6 sm:py-10 px-3 sm:px-0 flex items-start sm:items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-5 sm:p-8 print-border">
        
        <!-- Header -->
        <div class="text-center mb-6 border-b border-gray-200 pb-6">
            <h1 class="text-2xl font-bold tracking-tight text-[#b8972e] font-serif">Grand Hotel</h1>
            <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Official Receipt</p>
            <p class="text-xs text-gray-400 mt-2">123 Hotel Ave, City, Country</p>
            <p class="text-xs text-gray-400">Tax ID: 000-111-222</p>
        </div>

        <!-- Receipt Info -->
        <div class="mb-6 grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Receipt No.</p>
                <p class="font-medium text-gray-800 font-mono">{{ strtoupper(substr($payment->payment_id, 0, 8)) }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                <p class="font-medium text-gray-800">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y h:i A') : now()->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Guest</p>
                <p class="font-medium text-gray-800">{{ $payment->invoice && $payment->invoice->guest ? $payment->invoice->guest->first_name . ' ' . $payment->invoice->guest->last_name : ($payment->invoice && $payment->invoice->walkin ? $payment->invoice->walkin->guest_name . ' (Walk-In)' : 'Unknown Guest') }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                <p class="font-medium text-green-600 uppercase">{{ $payment->status }}</p>
            </div>
        </div>

        <!-- Room / Invoice Details -->
        <div class="mb-6">
            <p class="text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100 pb-2 mb-2">Description</p>
            @if($payment->invoice && $payment->invoice->reservation && $payment->invoice->reservation->room)
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-medium text-gray-800">{{ $payment->invoice->reservation->room->room_type }} Room</p>
                        <p class="text-xs text-gray-500">Room {{ $payment->invoice->reservation->room->room_number }}</p>
                        <p class="text-[11px] text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 inline-block mt-1">
                            {{ \Carbon\Carbon::parse($payment->invoice->reservation->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($payment->invoice->reservation->check_out_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            @elseif($payment->invoice && $payment->invoice->walkin)
                <p class="font-medium text-gray-800">Walk-In Stay</p>
                <p class="text-xs text-gray-500">{{ $payment->invoice->walkin->num_guests }} Guests</p>
                <p class="text-[11px] text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 inline-block mt-1">
                    {{ \Carbon\Carbon::parse($payment->invoice->walkin->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($payment->invoice->walkin->check_out_date)->format('M d, Y') }}
                </p>
            @else
                <p class="text-sm font-medium text-gray-800">Payment for Invoice #{{ strtoupper(substr($payment->invoice_id, 0, 8)) }}</p>
            @endif
        </div>

        <!-- Totals -->
        <div class="border-t border-gray-200 pt-4 mb-8">
            <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-gray-600">Payment Method</p>
                <p class="text-sm font-medium text-gray-800 capitalize">{{ $payment->payment_method }}</p>
            </div>
            @if($payment->transaction_id)
            <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-gray-600">Ref / Txn ID</p>
                <p class="text-xs font-mono text-gray-800">{{ $payment->transaction_id }}</p>
            </div>
            @endif

            @if($payment->invoice && $payment->invoice->additional_charges > 0)
            <div class="flex justify-between items-start mt-3 bg-red-50 p-2 rounded">
                 <div>
                     <p class="text-sm text-red-700 font-medium">Extra Charges / Damages</p>
                     @if($payment->invoice->additional_charges_notes)
                        <p class="text-[10px] text-red-500 max-w-[200px]">{{ $payment->invoice->additional_charges_notes }}</p>
                     @endif
                 </div>
                 <p class="text-sm font-bold text-red-700">₱{{ number_format($payment->invoice->additional_charges, 2) }}</p>
            </div>
            @endif

            <div class="flex justify-between items-center mt-4 border-t border-gray-600 pt-2 border-dashed">
                <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">Total Paid</p>
                <p class="text-lg font-bold text-[#b8972e]">₱{{ number_format($payment->amount, 2) }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs text-gray-500 mb-1">Thank you for choosing Grand Hotel.</p>
            <p class="text-[10px] text-gray-400">For inquiries, please contact frontdesk@grandhotel.com</p>
        </div>

        <!-- Action Buttons (No Print) -->
        <div class="no-print mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="window.print()" class="w-full sm:w-auto bg-[#b8972e] hover:bg-[#9a7e25] text-white px-6 py-3 sm:py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Receipt
            </button>
            <a href="{{ route('staff.payments.index') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 sm:py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition">
                Back to Payments
            </a>
        </div>

    </div>

</body>
</html>
