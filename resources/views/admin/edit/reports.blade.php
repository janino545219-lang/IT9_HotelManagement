@extends('admin.layout')
@section('title', 'Reports')

@section('content')

<!-- Summary Stats -->
<div class="stat-grid" style="margin-bottom:32px;">
    <div class="stat-card">
        <div class="icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-label">Total Reports</div>
        <div class="stat-value">{{ $totalReports ?? 0 }}</div>
        <div class="stat-sub">All time</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-label">This Month</div>
        <div class="stat-value">{{ $thisMonthReports ?? 0 }}</div>
        <div class="stat-sub">{{ now()->format('F Y') }}</div>
    </div>
    <div class="stat-card">
        <div class="icon"><i class="fas fa-user-tie"></i></div>
        <div class="stat-label">Staff Reports</div>
        <div class="stat-value">{{ $staffReportCount ?? 0 }}</div>
        <div class="stat-sub">Submitted by staff</div>
    </div>
</div>

<div class="filter-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search reports...">
    </div>
    <select>
        <option value="">All Types</option>
        <option>Occupancy</option>
        <option>Revenue</option>
        <option>Maintenance</option>
        <option>Incident</option>
        <option>Daily Summary</option>
    </select>
    <input type="date" style="width:auto;">
    <a href="{{ route('admin.reports.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> New Report
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Reports</div>
        <span style="font-size:12px;color:#AAA;">{{ $reports->total() ?? 0 }} reports</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Report Type</th>
                <th>Submitted By</th>
                <th>Report Date</th>
                <th>Generated At</th>
                <th>Preview</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                <td>
                    <span class="badge badge-blue">{{ ucfirst($report->report_type) }}</span>
                </td>
                <td>{{ $report->staff->first_name ?? '' }} {{ $report->staff->last_name ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</td>
                <td>{{ $report->generated_at ? \Carbon\Carbon::parse($report->generated_at)->format('M d, Y h:i A') : '—' }}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:#888;">
                    {{ Str::limit($report->content, 60) }}
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.reports.show', $report->report_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.reports.edit', $report->report_id) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('admin.reports.destroy', $report->report_id) }}" onsubmit="return confirm('Delete this report?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-chart-bar"></i><p>No reports found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $reports->links() }}</div>
</div>

@endsection