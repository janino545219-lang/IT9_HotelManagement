@extends('admin.layout')
@section('title', 'Employees')

@section('content')

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search employees...">
    </div>
    <select>
        <option value="">All Departments</option>
        <option>Front Desk</option>
        <option>Housekeeping</option>
        <option>Food & Beverage</option>
        <option>Management</option>
        <option>Maintenance</option>
    </select>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add Employee
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Employees</div>
        <span style="font-size:12px;color:#AAA;">{{ $employees->total() ?? 0 }} employees</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Hire Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;background:#F0EBE3;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;color:#C9A84C;font-weight:600;">
                            {{ strtoupper(substr($emp->first_name,0,1)) }}
                        </div>
                        {{ $emp->first_name }} {{ $emp->last_name }}
                    </div>
                </td>
                <td>{{ $emp->position }}</td>
                <td>{{ $emp->department }}</td>
                <td>₱{{ number_format($emp->salary, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->hire_date)->format('M d, Y') }}</td>
                <td>
                    @if($emp->is_active)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-grey">Inactive</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.employees.edit', $emp->employee_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.employees.destroy', $emp->employee_id) }}" onsubmit="return confirm('Delete this employee?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-id-badge"></i><p>No employees found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $employees->links() }}</div>
</div>

@endsection