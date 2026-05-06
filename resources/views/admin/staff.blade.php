@extends('admin.layout')
@section('title', 'Staff')

@section('content')

<div class="filter-bar" style="align-items: center;">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search staff..." id="searchInput">
    </div>
    <div style="display: flex; gap: 12px; margin-left: auto; align-items: center;">
        <select id="shiftFilter" style="width: auto;">
            <option value="">All Shifts</option>
            <option value="morning">Morning</option>
            <option value="afternoon">Afternoon</option>
            <option value="night">Night</option>
        </select>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Add Staff
        </a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Staff Members</div>
        <span style="font-size:12px;color:#AAA;">{{ $staff->total() ?? 0 }} staff total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Shift</th>
                <th>Email</th>
                <th>Status</th>
                <th>Joined Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $member)
            <tr>
                <td><strong>{{ $member->first_name }} {{ $member->last_name }}</strong></td>
                <td>{{ $member->role ?? '—' }}</td>
                <td>
                    @if($member->shift === 'morning')
                        <span class="badge badge-yellow">Morning</span>
                    @elseif($member->shift === 'afternoon')
                        <span class="badge badge-blue">Afternoon</span>
                    @elseif($member->shift === 'night')
                        <span class="badge badge-purple">Night</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($member->shift) }}</span>
                    @endif
                </td>
                <td>{{ $member->user->email ?? '—' }}</td>
                <td>
                    @if($member->is_available)
                        <span class="badge badge-green">Available</span>
                    @else
                        <span class="badge badge-red">Unavailable</span>
                    @endif
                </td>
                <td>{{ $member->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.staff.edit', $member->staff_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.staff.destroy', $member->staff_id) }}" onsubmit="return confirm('Delete this staff member?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-people-group"></i><p>No staff members found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $staff->links() }}</div>
</div>

<style>


table { width: 100%; border-collapse: collapse; }
thead { background: #F9F6F0; }
th { padding: 14px 16px; text-align: left; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #999; font-weight: 600; border-bottom: 1px solid #EAE6DF; }
td { padding: 14px 16px; border-bottom: 1px solid #F5F2ED; font-size: 13px; }
tbody tr:hover { background: #FDFAF5; }
tbody tr:last-child td { border-bottom: 1px solid #EAE6DF; }

.badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
.badge-green { background: #E8F5E9; color: #2E7D32; }
.badge-red { background: #FFEBEE; color: #C62828; }
.badge-yellow { background: #FFF3E0; color: #E65100; }
.badge-blue { background: #E3F2FD; color: #1565C0; }
.badge-purple { background: #F3E5F5; color: #6A1B9A; }
.badge-grey { background: #F5F5F5; color: #666; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.2s; }
.btn-gold { background: #C9A84C; color: #FFF; }
.btn-gold:hover { background: #B89A3F; }
.btn-outline { background: #FFF; color: #2C2C2C; border: 1px solid #EAE6DF; }
.btn-outline:hover { background: #FAFAF8; }
.btn-danger { background: #F5E6E6; color: #C62828; }
.btn-danger:hover { background: #E8CCCC; }
.btn-sm { padding: 8px 12px; font-size: 12px; }

.empty-state { text-align: center; padding: 48px 24px; color: #999; }
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.3; }
.empty-state p { font-size: 14px; }

.pagination-wrap { padding: 16px 0; text-align: center; }
</style>

@endsection
