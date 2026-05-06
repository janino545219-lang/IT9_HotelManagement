@extends('admin.layout')
@section('title', 'Invoice Detail')

@section('content')

<div style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Invoices
    </a>
    <a href="{{ route('admin.invoices.edit', $invoice->invoice_id) }}" class="btn btn-gold btn-sm">
        <i class="fas fa-pen"></i> Edit Invoice
    </a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

    <div class="section-card">
        <div class="section-header">
            <div>
                <div class="section-title">Invoice</div>
                <div style="font-size:11px;color:#AAA;margin-top:2px;font-family:monospace;">{{ $invoice->invoice_id }}</div>
            </div>
            @if($invoice->status==='paid') <span class="badge badge-green">Paid</span>
            @elseif($invoice->status==='unpaid') <span class="badge badge-yellow">Unpaid</span>
            @elseif($invoice->status==='overdue') <span class="badge badge-red">Overdue</span>
            @else <span class="badge badge-grey">{{ ucfirst($invoice->status) }}</span>
            @endif
        </div>

        <div>
            <div class="detail-row">
                <div class="detail-label">Guest</div>
                <div class="detail-value">{{ $invoice->guest->first_name ?? '' }} {{ $invoice->guest->last_name ?? '' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Reservation</div>
                <div class="detail-value" style="font-family:monospace;font-size:12px;">{{ $invoice->reservation_id }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Issued At</div>
                <div class="detail-value">{{ $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('F d, Y h:i A') : '—' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Due Date</div>
                <div class="detail-value">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F d, Y') : '—' }}</div>
            </div>
        </div>

        <div style="padding:20px 24px;border-top:1px solid #F0EBE3;">
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13.5px;color:#555;">
                <span>Subtotal</span><span>₱{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13.5px;color:#555;">
                <span>Tax</span><span>₱{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:13.5px;color:#C9A84C;">
                <span>Discount</span><span>−₱{{ number_format($invoice->discount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0 0;font-size:16px;font-weight:600;color:#1A1A1A;border-top:1px solid #F0EBE3;margin-top:8px;">
                <span>Total</span><span>₱{{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">Payments</div>
        </div>
        @forelse($invoice->payments ?? [] as $p)
        <div style="padding:14px 24px;border-bottom:1px solid #F8F5F0;">
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <strong style="font-size:13.5px;">₱{{ number_format($p->amount, 2) }}</strong>
                @if($p->status==='completed') <span class="badge badge-green">Completed</span>
                @elseif($p->status==='pending') <span class="badge badge-yellow">Pending</span>
                @else <span class="badge badge-grey">{{ ucfirst($p->status) }}</span>
                @endif
            </div>
            <div style="font-size:12px;color:#888;">{{ ucfirst($p->payment_method) }} · {{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('M d, Y') : '—' }}</div>
        </div>
        @empty
        <div class="empty-state"><i class="fas fa-credit-card"></i><p>No payments yet</p></div>
        @endforelse
    </div>
</div>

@endsection