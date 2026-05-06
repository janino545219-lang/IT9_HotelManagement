@extends('admin.layout')
@section('title', 'Invoices')

@section('content')

<div class="section-card">
    <div class="section-header">
        <div class="section-title">Invoice Management</div>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Record Invoice
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Guest</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Issued At</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td style="font-family: monospace; color: #666;">
                    {{ strtoupper(substr($invoice->invoice_id, 0, 8)) }}
                </td>
                <td>
                    @if($invoice->guest)
                        <strong>{{ $invoice->guest->first_name }} {{ $invoice->guest->last_name }}</strong>
                    @else
                        <span style="color:#999;">—</span>
                    @endif
                </td>
                <td>
                    ₱{{ number_format($invoice->total_amount, 2) }}
                </td>
                <td>
                    @if($invoice->status === 'paid')
                        <span class="badge badge-green">Paid</span>
                    @elseif($invoice->status === 'pending')
                        <span class="badge badge-yellow">Pending</span>
                    @elseif($invoice->status === 'overdue')
                        <span class="badge badge-red">Overdue</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($invoice->status) }}</span>
                    @endif
                </td>
                <td>{{ $invoice->issued_at ? Carbon\Carbon::parse($invoice->issued_at)->format('M d, Y') : '—' }}</td>
                <td>{{ $invoice->due_date ? Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : '—' }}</td>
                <td>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('admin.invoices.show', $invoice->invoice_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.invoices.edit', $invoice->invoice_id) }}" class="btn btn-outline btn-sm" style="color: #666;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.invoices.destroy', $invoice->invoice_id) }}" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
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
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <p>No invoices recorded yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        {{ $invoices->links() }}
    </div>
</div>

<style>
.btn-sm { padding: 6px 12px; font-size: 11px; }
.badge-red { background: rgba(231, 76, 60, 0.1); color: #E74C3C; border: 1px solid rgba(231, 76, 60, 0.2); }
</style>

@endsection
