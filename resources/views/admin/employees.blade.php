@extends('admin.layout')
@section('title', 'Employees')

@section('content')

<div class="filter-bar" style="align-items: center;">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search employees..." id="searchInput">
    </div>
    <div style="display: flex; gap: 12px; margin-left: auto; align-items: center;">
        <select id="statusFilter" style="width: auto;">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Add Employee
        </a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Employees</div>
        <span style="font-size:12px;color:#AAA;">{{ $employees->total() ?? 0 }} employees total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Hire Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></td>
                <td>{{ $employee->position ?? '—' }}</td>
                <td>{{ $employee->department ?? '—' }}</td>
                <td>{{ $employee->email ?? '—' }}</td>
                <td>{{ $employee->phone ?? '—' }}</td>
                <td>
                    @if($employee->is_active)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-grey">Inactive</span>
                    @endif
                </td>
                <td>{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : '—' }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.employees.edit', $employee->employee_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.employees.destroy', $employee->employee_id) }}" onsubmit="return confirm('Delete this employee?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-briefcase"></i><p>No employees found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $employees->links() }}</div>
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
