@extends('admin.layout')
@section('title', 'Payments')

@section('content')

<div class="section-card">
    <div class="section-header">
        <div class="section-title">Payment Management</div>
        <a href="{{ route('admin.payments.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Record Payment
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Invoice</th>
                <th>Guest</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Paid At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td style="font-family: monospace; color: #666;">
                    {{ strtoupper(substr($payment->payment_id, 0, 8)) }}
                </td>
                <td style="font-family: monospace; color: #666;">
                    {{ strtoupper(substr($payment->invoice_id, 0, 8)) }}
                </td>
                <td>
                    @if($payment->invoice && $payment->invoice->guest)
                        <strong>{{ $payment->invoice->guest->first_name }} {{ $payment->invoice->guest->last_name }}</strong>
                    @else
                        <span style="color:#999;">—</span>
                    @endif
                </td>
                <td>
                    ₱{{ number_format($payment->amount, 2) }}
                </td>
                <td>
                     <span class="badge badge-blue">{{ ucfirst($payment->payment_method) }}</span>
                </td>
                <td>
                    @if($payment->status === 'completed' || $payment->status === 'paid')
                        <span class="badge badge-green">Completed</span>
                    @elseif($payment->status === 'pending')
                        <span class="badge badge-yellow">Pending</span>
                    @elseif($payment->status === 'failed' || $payment->status === 'refunded')
                        <span class="badge badge-red">{{ ucfirst($payment->status) }}</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($payment->status) }}</span>
                    @endif
                </td>
                <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y h:i A') : '—' }}</td>
                <td>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('admin.payments.edit', $payment->payment_id) }}" class="btn btn-outline btn-sm" style="color: #666;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.payments.destroy', $payment->payment_id) }}" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm" style="color: #C0392B;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fas fa-credit-card"></i>
                        <p>No payments recorded yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $payments->links() }}
    </div>
</div>

<style>
.btn-sm { padding: 6px 12px; font-size: 11px; }
.badge-red { background: rgba(231, 76, 60, 0.1); color: #E74C3C; border: 1px solid rgba(231, 76, 60, 0.2); }
</style>

@endsection
