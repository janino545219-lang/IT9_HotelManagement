@extends('admin.layout')
@section('title', isset($walkin) ? 'Edit Walk-In' : 'Record Walk-In')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.walkins.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Walk-Ins
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($walkin) ? 'Edit Walk-In' : 'Record New Walk-In' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($walkin) ? route('admin.walkins.update', $walkin->walkin_id) : route('admin.walkins.store') }}">
            @csrf
            @if(isset($walkin)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Guest Name</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', $walkin->guest_name ?? '') }}" required placeholder="e.g. John Doe">
                    @error('guest_name')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $walkin->phone ?? '') }}" placeholder="e.g. 09123456789">
                    @error('phone')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Number of Guests</label>
                    <input type="number" name="num_guests" value="{{ old('num_guests', $walkin->num_guests ?? '1') }}" required min="1" placeholder="e.g. 2">
                    @error('num_guests')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Handled By (Employee)</label>
                    <select name="employee_id" required>
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->employee_id }}" 
                                {{ old('employee_id', $walkin->employee_id ?? '') === $employee->employee_id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Check-In Date</label>
                    <input type="date" name="check_in_date" value="{{ old('check_in_date', isset($walkin) ? $walkin->check_in_date->format('Y-m-d') : '') }}" required>
                    @error('check_in_date')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Check-Out Date</label>
                    <input type="date" name="check_out_date" value="{{ old('check_out_date', isset($walkin) ? $walkin->check_out_date->format('Y-m-d') : '') }}" required>
                    @error('check_out_date')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['pending', 'checked_in', 'checked_out'] as $s)
                            <option value="{{ $s }}" {{ old('status', $walkin->status ?? 'pending') === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($walkin) ? 'Update Walk-In' : 'Record Walk-In' }}
                </button>
                <a href="{{ route('admin.walkins.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 20px; }
.form-grid .full { grid-column: 1 / -1; }

.form-group { display: flex; flex-direction: column; }
.form-group label { font-size: 12px; font-weight: 600; color: #2C2C2C; margin-bottom: 8px; letter-spacing: 0.5px; }
.form-group input,
.form-group select,
.form-group textarea { padding: 11px 14px; border: 1px solid #EAE6DF; border-radius: 6px; font-size: 13px; font-family: inherit; background: #FFF; }
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 2px rgba(201, 168, 76, 0.1); }

.form-actions { display: flex; gap: 12px; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 11px 24px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.2s; }
.btn-gold { background: #C9A84C; color: #FFF; }
.btn-gold:hover { background: #B89A3F; }
.btn-outline { background: #FFF; color: #2C2C2C; border: 1px solid #EAE6DF; }
.btn-outline:hover { background: #FAFAF8; }
</style>

@endsection
