@extends('admin.layout')
@section('title', 'Invoices')

@section('content')

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search invoices...">
    </div>
    <select>
        <option value="">All Status</option>
        <option>Paid</option>
        <option>Unpaid</option>
        <option>Overdue</option>
        <option>Cancelled</option>
    </select>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Invoices</div>
        <span style="font-size:12px;color:#AAA;">{{ $invoices->total() ?? 0 }} invoices</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Guest</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
            <tr>
                <td style="font-size:11px;color:#888;font-family:monospace;">{{ substr($inv->invoice_id,0,8) }}...</td>
                <td>{{ $inv->guest->first_name ?? '' }} {{ $inv->guest->last_name ?? '' }}</td>
                <td>₱{{ number_format($inv->subtotal, 2) }}</td>
                <td>₱{{ number_format($inv->tax_amount, 2) }}</td>
                <td>₱{{ number_format($inv->discount, 2) }}</td>
                <td><strong>₱{{ number_format($inv->total_amount, 2) }}</strong></td>
                <td>{{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('M d, Y') : '—' }}</td>
                <td>
                    @if($inv->status==='paid') <span class="badge badge-green">Paid</span>
                    @elseif($inv->status==='unpaid') <span class="badge badge-yellow">Unpaid</span>
                    @elseif($inv->status==='overdue') <span class="badge badge-red">Overdue</span>
                    @elseif($inv->status==='cancelled') <span class="badge badge-grey">Cancelled</span>
                    @else <span class="badge badge-grey">{{ ucfirst($inv->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.invoices.show', $inv->invoice_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.invoices.edit', $inv->invoice_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state"><i class="fas fa-file-invoice"></i><p>No invoices found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $invoices->links() }}</div>
</div>

@endsection