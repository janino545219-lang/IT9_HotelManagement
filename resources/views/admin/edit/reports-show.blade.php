@extends('admin.layout')
@section('title', 'Report Details')

@section('content')

<div style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.reports.edit', $report->report_id) }}" class="btn btn-gold btn-sm">
            <i class="fas fa-pen"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.reports.destroy', $report->report_id) }}" onsubmit="return confirm('Delete this report?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div>
            <div class="section-title">{{ ucfirst($report->report_type) }} Report</div>
            <div style="font-size:11px;color:#AAA;margin-top:2px;">{{ \Carbon\Carbon::parse($report->report_date)->format('F d, Y') }}</div>
        </div>
        <span class="badge badge-blue">{{ ucfirst($report->report_type) }}</span>
    </div>

    <div>
        <div class="detail-row">
            <div class="detail-label">Report Type</div>
            <div class="detail-value">{{ ucfirst($report->report_type) }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Report Date</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($report->report_date)->format('F d, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Submitted By</div>
            <div class="detail-value">{{ $report->staff->first_name ?? '' }} {{ $report->staff->last_name ?? '' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Generated At</div>
            <div class="detail-value">{{ $report->generated_at ? \Carbon\Carbon::parse($report->generated_at)->format('F d, Y h:i A') : '—' }}</div>
        </div>
        <div class="detail-row" style="align-items:flex-start;">
            <div class="detail-label" style="padding-top:4px;">Content</div>
            <div class="detail-value" style="white-space:pre-wrap;line-height:1.7;">{{ $report->content }}</div>
        </div>
    </div>
</div>

@endsection