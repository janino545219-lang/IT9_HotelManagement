@extends('admin.layout')
@section('title', isset($employee) ? 'Edit Employee' : 'Add Employee')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Employees
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($employee) ? 'Edit Employee' : 'Add New Employee' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($employee) ? route('admin.employees.update', $employee->employee_id) : route('admin.employees.store') }}">
            @csrf
            @if(isset($employee)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" value="{{ old('position', $employee->position ?? '') }}" required placeholder="e.g. Receptionist">
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" required>
                        <option value="">Select department</option>
                        @foreach(['Front Desk','Housekeeping','Food & Beverage','Management','Maintenance','Security'] as $dept)
                            <option value="{{ $dept }}" {{ old('department', $employee->department ?? '') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Salary (₱)</label>
                    <input type="number" name="salary" value="{{ old('salary', $employee->salary ?? '') }}" required step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Hire Date</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', isset($employee) ? $employee->hire_date : '') }}" required>
                </div>
                @if(!isset($employee))
                <div class="form-group">
                    <label>Email (Login)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="employee@hotel.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Min. 8 characters">
                </div>
                @endif
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active">
                        <option value="1" {{ old('is_active', $employee->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $employee->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($employee) ? 'Update Employee' : 'Save Employee' }}
                </button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection