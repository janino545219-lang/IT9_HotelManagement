@extends('admin.layout')
@section('title', 'Payments')

@section('content')

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search payments...">
    </div>
    <select>
        <option value="">All Methods</option>
        <option>Cash</option>
        <option>Credit Card</option>
        <option>GCash</option>
        <option>Bank Transfer</option>
    </select>
    <select>
        <option value="">All Status</option>
        <option>Completed</option>
        <option>Pending</option>
        <option>Failed</option>
    </select>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Payments</div>
        <span style="font-size:12px;color:#AAA;">{{ $payments->total() ?? 0 }} transactions</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
            <tr>
                <td style="font-size:12px;color:#888;font-family:monospace;">{{ $p->transaction_id ?? '—' }}</td>
                <td style="font-size:11px;color:#888;font-family:monospace;">{{ substr($p->invoice_id,0,8) }}...</td>
                <td><strong>₱{{ number_format($p->amount, 2) }}</strong></td>
                <td>
                    <span class="badge badge-blue">{{ ucfirst($p->payment_method) }}</span>
                </td>
                <td>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('M d, Y h:i A') : '—' }}</td>
                <td>
                    @if($p->status==='completed') <span class="badge badge-green">Completed</span>
                    @elseif($p->status==='pending') <span class="badge badge-yellow">Pending</span>
                    @elseif($p->status==='failed') <span class="badge badge-red">Failed</span>
                    @else <span class="badge badge-grey">{{ ucfirst($p->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-credit-card"></i><p>No payments found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $payments->links() }}</div>
</div>

@endsection