@extends('admin.layout')
@section('title', isset($staffMember) ? 'Edit Staff' : 'Create Staff')

@section('content')

<div class="form-container">
    <div class="form-header">
        <div>
            <h2>{{ isset($staffMember) ? 'Edit Staff Member' : 'Create New Staff Member' }}</h2>
            <p>Manage staff information and work schedule</p>
        </div>
    </div>

    <div class="section-card">
        <form method="POST" action="{{ isset($staffMember) ? route('admin.staff.update', $staffMember->staff_id) : route('admin.staff.store') }}">
            @csrf
            @if(isset($staffMember)) @method('PUT') @endif

            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="{{ $staffMember->first_name ?? old('first_name') }}" required>
                @error('first_name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="{{ $staffMember->last_name ?? old('last_name') }}" required>
                @error('last_name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Role <span class="required">*</span></label>
                    <input type="text" name="role" value="{{ $staffMember->role ?? old('role') }}" required placeholder="e.g., Receptionist, Housekeeper">
                    @error('role') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Shift <span class="required">*</span></label>
                    <select name="shift" required>
                        <option value="">Select Shift</option>
                        <option value="morning" {{ (isset($staffMember) && $staffMember->shift === 'morning') || old('shift') === 'morning' ? 'selected' : '' }}>Morning (6 AM - 2 PM)</option>
                        <option value="afternoon" {{ (isset($staffMember) && $staffMember->shift === 'afternoon') || old('shift') === 'afternoon' ? 'selected' : '' }}>Afternoon (2 PM - 10 PM)</option>
                        <option value="night" {{ (isset($staffMember) && $staffMember->shift === 'night') || old('shift') === 'night' ? 'selected' : '' }}>Night (10 PM - 6 AM)</option>
                    </select>
                    @error('shift') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ $staffMember->user->email ?? old('email') }}" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="{{ $staffMember->phone ?? old('phone') }}" placeholder="+1 (555) 000-0000">
                    @error('phone') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            @if(!isset($staffMember))
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_available" value="1" {{ (isset($staffMember) && $staffMember->is_available) || old('is_available') ? 'checked' : '' }}>
                    Available for Shifts
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-gold">{{ isset($staffMember) ? 'Update Staff' : 'Create Staff' }}</button>
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
.form-group input[type="number"],
.form-group select { width: 100%; padding: 10px 12px; border: 1px solid #EAE6DF; border-radius: 6px; font-size: 13px; background: #FFF; }
.form-group input:focus,
.form-group select:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1); }
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
