@extends('admin.layout')
@section('title', isset($report) ? 'Edit Report' : 'New Report')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($report) ? 'Edit Report' : 'Create New Report' }}</div>
    </div>
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($report) ? route('admin.reports.update', $report->report_id) : route('admin.reports.store') }}">
            @csrf
            @if(isset($report)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label>Report Type</label>
                    <select name="report_type" required>
                        <option value="">Select type</option>
                        @foreach(['occupancy','revenue','maintenance','incident','daily_summary'] as $type)
                            <option value="{{ $type }}" {{ old('report_type', $report->report_type ?? '') === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('report_type')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Staff Member</label>
                    <select name="staff_id" required>
                        <option value="">Select staff</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->staff_id }}" {{ old('staff_id', $report->staff_id ?? '') === $s->staff_id ? 'selected' : '' }}>
                                {{ $s->first_name }} {{ $s->last_name }} — {{ $s->role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Report Date</label>
                    <input type="date" name="report_date" value="{{ old('report_date', isset($report) ? $report->report_date : now()->toDateString()) }}" required>
                </div>
                <div class="form-group">
                    <label>Generated At</label>
                    <input type="datetime-local" name="generated_at" value="{{ old('generated_at', isset($report) && $report->generated_at ? \Carbon\Carbon::parse($report->generated_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group full">
                    <label>Report Content</label>
                    <textarea name="content" rows="8" required placeholder="Write the full report content here...">{{ old('content', $report->content ?? '') }}</textarea>
                    @error('content')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($report) ? 'Update Report' : 'Save Report' }}
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection