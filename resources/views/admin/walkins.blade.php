@extends('admin.layout')
@section('title', 'Walk-Ins')

@section('content')

<div class="section-card">
    <div class="section-header">
        <div class="section-title">Walk-in Records</div>
        <span style="font-size:12px;color:#AAA;">{{ $walkins->total() ?? 0 }} walk-ins total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Phone</th>
                <th>Guests</th>
                <th>Employee</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($walkins as $walkin)
            <tr>
                <td><strong>{{ $walkin->guest_name }}</strong></td>
                <td>{{ $walkin->phone ?? '—' }}</td>
                <td>{{ $walkin->num_guests }} {{ $walkin->num_guests === 1 ? 'guest' : 'guests' }}</td>
                <td>
                    @if($walkin->employee)
                        {{ $walkin->employee->first_name }} {{ $walkin->employee->last_name }}
                    @else
                        <span style="color:#999;">—</span>
                    @endif
                </td>
                <td>{{ $walkin->check_in_date->format('M d, Y') }}</td>
                <td>{{ $walkin->check_out_date->format('M d, Y') }}</td>
                <td>
                    @if($walkin->status === 'checked_in')
                        <span class="badge badge-green">Checked In</span>
                    @elseif($walkin->status === 'checked_out')
                        <span class="badge badge-grey">Checked Out</span>
                    @elseif($walkin->status === 'pending')
                        <span class="badge badge-yellow">Pending</span>
                    @else
                        <span class="badge badge-blue">{{ ucfirst($walkin->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-person-walking"></i><p>No walk-in records found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $walkins->links() }}</div>
</div>

@endsection
