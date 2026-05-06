@extends('admin.layout')
@section('title', isset($employee) ? 'Edit Employee' : 'Create Employee')

@section('content')

<div class="form-container">
    <div class="form-header">
        <div>
            <h2>{{ isset($employee) ? 'Edit Employee' : 'Create New Employee' }}</h2>
            <p>Manage employee information and account details</p>
        </div>
    </div>

    <div class="section-card">
        <form method="POST" action="{{ isset($employee) ? route('admin.employees.update', $employee->employee_id) : route('admin.employees.store') }}">
            @csrf
            @if(isset($employee)) @method('PUT') @endif

            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="{{ $employee->first_name ?? old('first_name') }}" required>
                @error('first_name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="{{ $employee->last_name ?? old('last_name') }}" required>
                @error('last_name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Position <span class="required">*</span></label>
                    <input type="text" name="position" value="{{ $employee->position ?? old('position') }}" required placeholder="e.g., Manager, Chef">
                    @error('position') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Department <span class="required">*</span></label>
                    <input type="text" name="department" value="{{ $employee->department ?? old('department') }}" required placeholder="e.g., Front Desk, Kitchen">
                    @error('department') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ $employee->user->email ?? old('email') }}" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="{{ $employee->phone ?? old('phone') }}" placeholder="+1 (555) 000-0000">
                    @error('phone') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Hire Date <span class="required">*</span></label>
                    <input type="date" name="hire_date" value="{{ (isset($employee) && $employee->hire_date) ? $employee->hire_date->format('Y-m-d') : old('hire_date') }}" required>
                    @error('hire_date') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input type="number" name="salary" value="{{ $employee->salary ?? old('salary') }}" placeholder="Monthly salary" min="0" step="0.01">
                    @error('salary') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            @if(!isset($employee))
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ (isset($employee) && $employee->is_active) || old('is_active') ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-gold">{{ isset($employee) ? 'Update Employee' : 'Create Employee' }}</button>
            </div>
        </form>
    </div>
</div>

<style>
.form-container { max-width: 800px; margin: 0 auto; }
.form-header { margin-bottom: 24px; }
.form-header h2 { font-size: 24px; font-weight: 700; margin: 0 0 4px 0; }
.form-header p { font-size: 13px; color: #999; margin: 0; }

.section-card { background: #FFF; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #2C2C2C; }
.form-group input[type="text"], 
.form-group input[type="email"], 
.form-group input[type="tel"], 
.form-group input[type="password"],
.form-group input[type="date"],
.form-group input[type="number"] { width: 100%; padding: 10px 12px; border: 1px solid #EAE6DF; border-radius: 6px; font-size: 13px; }
.form-group input:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1); }
.form-group input[type="checkbox"] { margin-right: 8px; }
.form-group label { display: flex; align-items: center; }

.required { color: #C62828; }
.error { display: block; font-size: 12px; color: #C62828; margin-top: 4px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.form-actions { display: flex; gap: 12px; margin-top: 32px; justify-content: flex-end; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.btn-gold { background: #C9A84C; color: #FFF; }
.btn-gold:hover { background: #B89A3F; }
.btn-secondary { background: #EAE6DF; color: #2C2C2C; }
.btn-secondary:hover { background: #D9CCBE; }

@media (max-width: 600px) {
    .form-row { grid-template-columns: 1fr; }
}
</style>

@endsection
