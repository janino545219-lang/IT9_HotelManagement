@extends('admin.layout')
@section('title', 'Staff')

@section('content')

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search staff...">
    </div>
    <select>
        <option value="">All Shifts</option>
        <option>Morning</option>
        <option>Afternoon</option>
        <option>Night</option>
    </select>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add Staff
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Staff</div>
        <span style="font-size:12px;color:#AAA;">{{ $staff->total() ?? 0 }} staff members</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Shift</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $s)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;background:#F0EBE3;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;color:#C9A84C;font-weight:600;">
                            {{ strtoupper(substr($s->first_name,0,1)) }}
                        </div>
                        {{ $s->first_name }} {{ $s->last_name }}
                    </div>
                </td>
                <td>{{ $s->role }}</td>
                <td>
                    <span class="badge badge-blue">{{ ucfirst($s->shift) }}</span>
                </td>
                <td>
                    @if($s->is_available)
                        <span class="badge badge-green">Available</span>
                    @else
                        <span class="badge badge-grey">Unavailable</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.staff.edit', $s->staff_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.staff.destroy', $s->staff_id) }}" onsubmit="return confirm('Delete this staff?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5"><div class="empty-state"><i class="fas fa-user-tie"></i><p>No staff found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $staff->links() }}</div>
</div>

@endsection