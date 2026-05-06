@extends('admin.layout')
@section('title', isset($payment) ? 'Edit Payment' : 'Record Payment')

@section('content')
<div class="section-card max-w-3xl">
    <div class="section-header">
        <div class="section-title">{{ isset($payment) ? 'Edit Payment' : 'Record New Payment' }}</div>
    </div>
    <div style="padding: 24px;">
        <form method="POST" action="{{ isset($payment) ? route('admin.payments.update', $payment->payment_id) : route('admin.payments.store') }}">
            @csrf
            @if(isset($payment)) @method('PUT') @endif

            <div class="form-grid">
                
                <div class="form-group full">
                    <label>Invoice / Guest</label>
                    <select name="invoice_id" required>
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->invoice_id }}" 
                                {{ old('invoice_id', $payment->invoice_id ?? '') == $invoice->invoice_id ? 'selected' : '' }}>
                                {{ strtoupper(substr($invoice->invoice_id, 0, 8)) }} - {{ $invoice->guest ? $invoice->guest->first_name . ' ' . $invoice->guest->last_name : 'Unknown Guest' }} (₱{{ $invoice->total_amount }})
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Amount Paid (₱)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $payment->amount ?? '') }}" required placeholder="0.00">
                    @error('amount')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="walk in" {{ old('payment_method', $payment->payment_method ?? '') == 'walk in' ? 'selected' : '' }}>Walk-In (Cash)</option>
                        <option value="online" {{ old('payment_method', $payment->payment_method ?? '') == 'online' ? 'selected' : '' }}>Online Payment</option>
                        <option value="card" {{ old('payment_method', $payment->payment_method ?? '') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                    </select>
                    @error('payment_method')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Transaction ID (Optional)</label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id ?? '') }}" placeholder="e.g. TXN123456">
                    @error('transaction_id')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="completed" {{ old('status', $payment->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status', $payment->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ old('status', $payment->status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ old('status', $payment->status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    @error('status')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Paid At</label>
                    <input type="datetime-local" name="paid_at" value="{{ old('paid_at', isset($payment) && $payment->paid_at ? $payment->paid_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                    @error('paid_at')<div style="color:red; font-size:11px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full form-actions mt-4">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-save"></i> {{ isset($payment) ? 'Update Payment' : 'Save Payment' }}
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
