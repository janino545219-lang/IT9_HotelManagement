@extends('admin.layout')
@section('title', 'Walk-Ins')

@section('content')

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search walk-ins...">
    </div>
    <select>
        <option value="">All Status</option>
        <option>Active</option>
        <option>Checked Out</option>
        <option>Cancelled</option>
    </select>
    <a href="{{ route('admin.walkins.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> New Walk-In
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Walk-Ins</div>
        <span style="font-size:12px;color:#AAA;">{{ $walkins->total() ?? 0 }} total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Phone</th>
                <th>Guests</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Handled By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($walkins as $w)
            <tr>
                <td><strong>{{ $w->guest_name }}</strong></td>
                <td>{{ $w->phone ?? '—' }}</td>
                <td>{{ $w->num_guests }}</td>
                <td>{{ \Carbon\Carbon::parse($w->check_in_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($w->check_out_date)->format('M d, Y') }}</td>
                <td>{{ $w->employee->first_name ?? '' }} {{ $w->employee->last_name ?? '' }}</td>
                <td>
                    @if($w->status==='active') <span class="badge badge-green">Active</span>
                    @elseif($w->status==='checked_out') <span class="badge badge-blue">Checked Out</span>
                    @elseif($w->status==='cancelled') <span class="badge badge-red">Cancelled</span>
                    @else <span class="badge badge-grey">{{ ucfirst($w->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.walkins.edit', $w->walkin_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.walkins.destroy', $w->walkin_id) }}" onsubmit="return confirm('Delete this walk-in?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-person-walking"></i><p>No walk-ins found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $walkins->links() }}</div>
</div>

@endsection