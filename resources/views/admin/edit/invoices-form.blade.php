@extends('admin.layout')
@section('title', isset($invoice) ? 'Edit Invoice' : 'New Invoice')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Invoices
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($invoice) ? 'Edit Invoice' : 'Create Invoice' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($invoice) ? route('admin.invoices.update', $invoice->invoice_id) : route('admin.invoices.store') }}">
            @csrf
            @if(isset($invoice)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Reservation</label>
                    <select name="reservation_id" required>
                        <option value="">Select reservation</option>
                        @foreach($reservations as $r)
                            <option value="{{ $r->reservation_id }}" {{ old('reservation_id', $invoice->reservation_id ?? '') === $r->reservation_id ? 'selected' : '' }}>
                                {{ $r->guest->first_name ?? '' }} {{ $r->guest->last_name ?? '' }} — Room {{ $r->room->room_number ?? '' }} ({{ \Carbon\Carbon::parse($r->check_in_date)->format('M d') }} – {{ \Carbon\Carbon::parse($r->check_out_date)->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Guest</label>
                    <select name="guest_id" required>
                        <option value="">Select guest</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->guest_id }}" {{ old('guest_id', $invoice->guest_id ?? '') === $guest->guest_id ? 'selected' : '' }}>
                                {{ $guest->first_name }} {{ $guest->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Subtotal (₱)</label>
                    <input type="number" name="subtotal" value="{{ old('subtotal', $invoice->subtotal ?? '') }}" required step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Tax Amount (₱)</label>
                    <input type="number" name="tax_amount" value="{{ old('tax_amount', $invoice->tax_amount ?? 0) }}" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Discount (₱)</label>
                    <input type="number" name="discount" value="{{ old('discount', $invoice->discount ?? 0) }}" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Total Amount (₱)</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount', $invoice->total_amount ?? '') }}" required step="0.01" min="0" id="totalAmount">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['unpaid','paid','overdue','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $invoice->status ?? 'unpaid') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', isset($invoice) ? $invoice->due_date : '') }}">
                </div>
                <div class="form-group">
                    <label>Issued At</label>
                    <input type="datetime-local" name="issued_at" value="{{ old('issued_at', isset($invoice) && $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($invoice) ? 'Update Invoice' : 'Save Invoice' }}
                </button>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection