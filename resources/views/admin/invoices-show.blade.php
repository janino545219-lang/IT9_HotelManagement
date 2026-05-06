@extends('admin.layout')
@section('title', 'View Invoice')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Invoices
    </a>
    <a href="{{ route('admin.invoices.edit', $invoice->invoice_id) }}" class="btn btn-outline btn-sm" style="margin-left: 10px;">
        <i class="fas fa-edit"></i> Edit Invoice
    </a>
</div>

<div class="section-card" style="max-width: 800px; margin: 0 auto;">
    <!-- Paper Invoice Look -->
    <div style="padding: 40px; background: #FFF;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #EAE6DF; padding-bottom: 24px; margin-bottom: 24px;">
            <div>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 28px; color: #1A1A1A; margin-bottom: 5px;">INVOICE</h1>
                <p style="font-size: 13px; color: #666; font-family: monospace;">#{{ strtoupper(substr($invoice->invoice_id, 0, 12)) }}...</p>
            </div>
            <div style="text-align: right;">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 20px; color: #C9A84C;">Grand Hotel</h2>
                <p style="font-size: 13px; color: #666;">123 Luxury Avenue<br>Manila, Philippines<br>billing@grandhotel.com</p>
            </div>
        </div>

        <!-- Meta -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 32px;">
            <div>
                <p style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #999; letter-spacing: 1px; margin-bottom: 4px;">Bill To:</p>
                <p style="font-size: 15px; font-weight: 600; color: #2C2C2C;">
                    {{ $invoice->guest->first_name ?? 'Walk-In' }} {{ $invoice->guest->last_name ?? 'Guest' }}
                </p>
                <p style="font-size: 13px; color: #666;">
                    Reservation: {{ strtoupper(substr($invoice->reservation_id, 0, 8)) }}
                </p>
            </div>
            <div style="text-align: right;">
                <table style="font-size: 13px; color: #2C2C2C;">
                    <tr>
                        <td style="padding-right: 16px; color: #999;">Issued Date:</td>
                        <td>{{ $invoice->issued_at ? Carbon\Carbon::parse($invoice->issued_at)->format('M d, Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 16px; color: #999;">Due Date:</td>
                        <td>{{ $invoice->due_date ? Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 16px; color: #999;">Status:</td>
                        <td>
                            @if($invoice->status === 'paid')
                                <span style="color: #27ae60; font-weight: 600;">PAID</span>
                            @elseif($invoice->status === 'overdue')
                                <span style="color: #C0392B; font-weight: 600;">OVERDUE</span>
                            @else
                                <span style="font-weight: 600; text-transform: uppercase;">{{ $invoice->status }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Breakdown -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 32px;">
            <thead>
                <tr style="border-bottom: 1px solid #EAE6DF;">
                    <th style="text-align: left; padding: 12px 0; font-size: 12px; font-weight: 600; color: #999; text-transform: uppercase;">Description</th>
                    <th style="text-align: right; padding: 12px 0; font-size: 12px; font-weight: 600; color: #999; text-transform: uppercase;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 16px 0; font-size: 14px; color: #2C2C2C; border-bottom: 1px dashed #EAE6DF;">Room Charges & Services (Subtotal)</td>
                    <td style="text-align: right; padding: 16px 0; font-size: 14px; color: #2C2C2C; border-bottom: 1px dashed #EAE6DF;">₱{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 16px 0; font-size: 14px; color: #2C2C2C; border-bottom: 1px dashed #EAE6DF;">Taxes / Fees</td>
                    <td style="text-align: right; padding: 16px 0; font-size: 14px; color: #2C2C2C; border-bottom: 1px dashed #EAE6DF;">+ ₱{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @if($invoice->discount > 0)
                <tr>
                    <td style="padding: 16px 0; font-size: 14px; color: #2C2C2C; border-bottom: 1px dashed #EAE6DF;">Discount Applied</td>
                    <td style="text-align: right; padding: 16px 0; font-size: 14px; color: #C0392B; border-bottom: 1px dashed #EAE6DF;">- ₱{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Total -->
        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 300px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #FAF8F3; border-radius: 6px;">
                    <span style="font-size: 14px; font-weight: 600; color: #2C2C2C;">Total Amount</span>
                    <span style="font-size: 20px; font-weight: 600; color: #C9A84C; font-family: 'Playfair Display', serif;">₱{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if($invoice->payments && $invoice->payments->count() > 0)
        <!-- Payments Section -->
        <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #EAE6DF;">
            <h3 style="font-size: 14px; font-weight: 600; color: #2C2C2C; margin-bottom: 16px;">Payment History</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 8px 0; font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase;">Date</th>
                        <th style="text-align: left; padding: 8px 0; font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase;">Method</th>
                        <th style="text-align: left; padding: 8px 0; font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase;">Transaction ID</th>
                        <th style="text-align: right; padding: 8px 0; font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase;">Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td style="padding: 12px 0; font-size: 13px; color: #2C2C2C; border-bottom: 1px solid #FAF8F3;">{{ $payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') : '—' }}</td>
                        <td style="padding: 12px 0; font-size: 13px; color: #2C2C2C; border-bottom: 1px solid #FAF8F3;">{{ ucfirst($payment->payment_method) }}</td>
                        <td style="padding: 12px 0; font-size: 13px; color: #666; font-family: monospace; border-bottom: 1px solid #FAF8F3;">{{ substr($payment->transaction_id, 0, 12) }}...</td>
                        <td style="text-align: right; padding: 12px 0; font-size: 13px; color: #27ae60; font-weight: 500; border-bottom: 1px solid #FAF8F3;">₱{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

@endsection
