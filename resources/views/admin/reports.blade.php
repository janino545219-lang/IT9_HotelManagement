@extends('admin.layout')

@section('title', 'Financial Analytics')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

<style>
    .dash { font-family: 'DM Sans', sans-serif; }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: #fff;
        border: 1px solid #f0ede8;
        border-radius: 18px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .header-row::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #c9a84c;
    }
    .header-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px;
        font-weight: 600;
        letter-spacing: 0.01em;
        margin: 0;
        color: #1a1a1a;
    }
    .header-sub {
        font-size: 13px;
        color: #999;
        margin: 4px 0 0;
    }
    .header-actions { display: flex; gap: 10px; }

    .btn-lux {
        padding: 9px 18px;
        font-size: 12px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .btn-lux-dark {
        background: #1a1a1a;
        color: #fff;
        border: 1px solid #1a1a1a;
    }
    .btn-lux-dark:hover { background: #333; }
    .btn-lux-gold {
        background: #c9a84c;
        color: #fff;
        border: 1px solid #c9a84c;
    }
    .btn-lux-gold:hover { background: #b8972e; }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    @media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
        background: #fff;
        border: 1px solid #f0ede8;
        border-radius: 20px;
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        transition: border-color 0.3s, transform 0.3s;
    }
    .kpi-card:hover { border-color: #c9a84c; transform: translateY(-3px); }
    .kpi-card.dark-kpi {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .kpi-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #999;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .dark-kpi .kpi-label { color: #666; }

    .kpi-value {
        font-size: 26px;
        font-weight: 600;
        color: #1a1a1a;
        letter-spacing: -0.01em;
        line-height: 1.1;
    }
    .dark-kpi .kpi-value { color: #fff; }

    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        margin-top: 15px;
    }
    .badge-gold { background: #fdf8ef; color: #c9a84c; }
    .badge-green { background: #eaf3de; color: #3b6d11; }

    .progress-bar { margin-top: 20px; }
    .progress-track {
        width: 100%;
        height: 5px;
        background: #2a2a2a;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 7px;
    }
    .progress-fill { height: 100%; background: #c9a84c; border-radius: 3px; }
    .progress-info { display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; }
    .progress-info span:first-child { color: #555; text-transform: uppercase; letter-spacing: 0.08em; }
    .progress-info span:last-child { color: #c9a84c; }

    /* Visual Analytics Area */
    .analysis-row {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(0, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    @media (max-width: 1100px) { .analysis-row { grid-template-columns: 1fr; } }

    .card-main {
        background: #fff;
        border: 1px solid #f0ede8;
        border-radius: 24px;
        padding: 2rem;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 15px;
    }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: #1a1a1a; margin: 0; }
    .card-sub { font-size: 12px; color: #999; margin-top: 2px; }

    /* Tabs */
    .tab-nav {
        display: flex;
        background: #f9f8f6;
        border: 1px solid #ede9e3;
        border-radius: 12px;
        padding: 4px;
        gap: 4px;
    }
    .tab-link {
        padding: 7px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: none;
        border-radius: 9px;
        cursor: pointer;
        background: transparent;
        color: #999;
        transition: all 0.2s;
    }
    .tab-link.active {
        background: #fff;
        color: #1a1a1a;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        border: 1px solid #ede9e3;
    }

    /* Distribution / Market Mix */
    .dist-box {
        background: #fff;
        border: 1px solid #f0ede8;
        border-radius: 24px;
        padding: 2rem;
    }
    .dist-item { margin-bottom: 20px; }
    .dist-item:last-child { margin-bottom: 0; }
    .dist-label-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 8px; }
    .dist-label-top { font-size: 10px; font-weight: 700; color: #bbb; text-transform: uppercase; letter-spacing: 0.1em; }
    .dist-label-main { font-size: 14px; font-weight: 600; color: #1a1a1a; }
    .dist-val { font-size: 12px; font-weight: 700; color: #c9a84c; }
    .dist-bar-track { width: 100%; height: 4px; background: #f7f5f2; border-radius: 2px; overflow: hidden; }
    .dist-bar-fill { height: 100%; background: linear-gradient(90deg, #c9a84c, #e8d5a0); border-radius: 2px; transition: width 1s ease-out; }

    /* Trajectory Analysis */
    .traj-card {
        background: #1a1a1a;
        border-radius: 32px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .traj-title { font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 600; color: #fff; margin: 0; }
    .traj-sub { font-size: 12px; color: #666; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.1em; }
    .traj-legend { display: flex; gap: 24px; margin: 20px 0; }
    .legend-item { display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; }
    .dot-white { width: 12px; height: 3px; background: #fff; border-radius: 2px; }
    .dot-gold { width: 12px; height: 3px; background: #c9a84c; border-radius: 2px; }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dash { animation: slide-up 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="dash">

    {{-- Header --}}
    <div class="header-row">
        <div>
            <h1 class="header-title">Financial Intelligence Dashboard</h1>
            <p class="header-sub">Institutional revenue auditing & forecasting system &ndash; Last updated: {{ now()->format('M d, Y H:i') }}</p>
        </div>
        <div class="header-actions">
            <button class="btn-lux btn-lux-dark">
                <i class="fas fa-file-export"></i> Audited Export
            </button>
            <button class="btn-lux btn-lux-gold">
                <i class="fas fa-print"></i> Generate PDF
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-grid">
        {{-- Lifetime --}}
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#fdf8ef;">
                <i class="fas fa-vault text-[#c9a84c]"></i>
            </div>
            <p class="kpi-label">Lifetime Revenue</p>
            <p class="kpi-value">₱{{ number_format($totalRevenue, 2) }}</p>
            <span class="kpi-badge badge-green">
                <i class="fas fa-arrow-up mr-1"></i> 14.2% Institutional Growth
            </span>
        </div>

        {{-- Monthly --}}
        <div class="kpi-card dark-kpi">
            <div class="kpi-icon" style="background:#2a2a2a;">
                <i class="fas fa-chart-line text-[#c9a84c]"></i>
            </div>
            <p class="kpi-label">Current Month</p>
            <p class="kpi-value">₱{{ number_format($monthRevenue, 2) }}</p>
            <div class="progress-bar">
                <div class="progress-track">
                    <div class="progress-fill" style="width: 82%;"></div>
                </div>
                <div class="progress-info">
                    <span>Revenue Target</span>
                    <span>82%</span>
                </div>
            </div>
        </div>

        {{-- Today --}}
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#eaf3de;">
                <i class="fas fa-bolt text-[#3b6d11]"></i>
            </div>
            <p class="kpi-label">Daily Collection</p>
            <p class="kpi-value">₱{{ number_format($todayRevenue, 2) }}</p>
            <span class="kpi-badge badge-gold">Peak Volume: 10:30 AM</span>
        </div>

        {{-- Volume --}}
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#f5f2ed;">
                <i class="fas fa-bell-concierge text-[#1a1a1a]"></i>
            </div>
            <p class="kpi-label">Daily Activity</p>
            <p class="kpi-value">{{ $checkedOutToday }}</p>
            <div class="mt-4 flex -space-x-2">
                @for($i=0; $i<min(4, $checkedOutToday); $i++)
                <div class="w-7 h-7 rounded-full border-2 border-white bg-gray-200 shadow-sm overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ $i }}&background=random" alt="">
                </div>
                @endfor
                @if($checkedOutToday > 4)
                <div class="w-7 h-7 rounded-full border-2 border-white bg-[#c9a84c] flex items-center justify-center text-[9px] text-white font-bold">+{{ $checkedOutToday - 4 }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Analytics --}}
    <div class="analysis-row">
        {{-- Revenue Chart --}}
        <div class="card-main">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Institutional Revenue Stream</h3>
                    <p class="card-sub">Comparative analysis of verified transaction volume of this period</p>
                </div>
                <div class="tab-nav">
                    <button class="tab-link active" onclick="updateLuxChart('daily', this)">Daily</button>
                    <button class="tab-link" onclick="updateLuxChart('monthly', this)">Monthly</button>
                    <button class="tab-link" onclick="updateLuxChart('yearly', this)">Yearly</button>
                </div>
            </div>
            <div style="position: relative; height: 380px;">
                <canvas id="luxRevenueChart"></canvas>
            </div>
        </div>

        {{-- Market Mix --}}
        <div class="dist-box">
            <h3 class="card-title">Portfolio Mix</h3>
            <p class="card-sub mb-8">Revenue contribution by room categorization</p>
            
            <div class="dist-items">
                @foreach($roomTypeStats as $stat)
                <div class="dist-item">
                    <div class="dist-label-row">
                        <div>
                            <p class="dist-label-top">Room Class</p>
                            <p class="dist-label-main uppercase">{{ $stat->room_type }}</p>
                        </div>
                        <span class="dist-val">{{ $stat->total }} BOOKINGS</span>
                    </div>
                    <div class="dist-bar-track">
                        @php $percent = $totalReservations > 0 ? ($stat->total / $totalReservations) * 100 : 0; @endphp
                        <div class="dist-bar-fill" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 pt-8 border-t border-gray-50 grid grid-cols-2 gap-4">
                <div class="p-4 bg-[#1a1a1a] rounded-2xl text-center shadow-lg">
                    <p class="text-xl font-bold text-[#c9a84c]">{{ $totalReservations }}</p>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-1">Verified Online</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl text-center">
                    <p class="text-xl font-bold text-gray-900">{{ $totalWalkins }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Direct Walk-In</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Trajectory Analysis --}}
    <div class="traj-card shadow-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="traj-title">Operational Trajectory Analysis</h3>
                <p class="traj-sub">Historical comparison of reservation sources over 6 months</p>
            </div>
            <div class="traj-legend">
                <div class="legend-item">
                    <div class="dot-white shadow-[0_0_8px_white]"></div>
                    <span class="text-white opacity-60">Online Reservations</span>
                </div>
                <div class="legend-item">
                    <div class="dot-gold shadow-[0_0_8px_#c9a84c]"></div>
                    <span class="text-[#c9a84c] opacity-80">Walk-In Acquisitions</span>
                </div>
            </div>
        </div>
        <div style="position: relative; height: 320px; margin-top: 20px;">
            <canvas id="luxTrajChart"></canvas>
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    const luxChartData = {
        daily:   { labels: @json($dailyLabels),   data: @json($dailyData),   label: 'Institutional Revenue' },
        monthly: { labels: @json($monthlyLabels), data: @json($monthlyData), label: 'Institutional Revenue' },
        yearly:  { labels: @json($yearlyLabels),  data: @json($yearlyData),  label: 'Institutional Revenue' }
    };

    let luxMainChart;

    function initLuxCharts() {
        const ctx = document.getElementById('luxRevenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#c9a84c');
        gradient.addColorStop(1, '#e8d5a0');

        luxMainChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: luxChartData.daily.labels,
                datasets: [{
                    label: luxChartData.daily.label,
                    data: luxChartData.daily.data,
                    backgroundColor: gradient,
                    borderRadius: 12,
                    borderSkipped: false,
                    maxBarThickness: 55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleFont: { size: 12, weight: 'bold', family: 'DM Sans' },
                        bodyFont: { size: 15, weight: 'black', family: 'DM Sans' },
                        padding: 18,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: { label: c => ' ₱' + c.parsed.y.toLocaleString() }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [6, 6], color: '#f0f0f0', drawBorder: false },
                        ticks: { color: '#bbb', font: { size: 11, weight: '700' }, padding: 10, callback: v => '₱' + (v >= 1000 ? v/1000 + 'k' : v) }
                    },
                    x: { grid: { display: false }, ticks: { color: '#bbb', font: { size: 11, weight: '700' }, padding: 10 } }
                }
            }
        });
    }

    function updateLuxChart(type, btn) {
        document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
        btn.classList.add('active');
        luxMainChart.data.labels = luxChartData[type].labels;
        luxMainChart.data.datasets[0].data = luxChartData[type].data;
        luxMainChart.update();
    }

    function initTrajChart() {
        const ctx = document.getElementById('luxTrajChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($compLabels),
                datasets: [
                    {
                        label: 'RESERVATIONS',
                        data: @json($compReserv),
                        borderColor: '#ffffff',
                        borderWidth: 3.5,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(255, 255, 255, 0.05)'
                    },
                    {
                        label: 'WALK-INS',
                        data: @json($compWalkins),
                        borderColor: '#c9a84c',
                        borderWidth: 3.5,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(201, 168, 76, 0.08)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }, ticks: { stepSize: 1, color: '#555', font: { weight: '800' } } },
                    x: { grid: { display: false }, ticks: { color: '#555', font: { weight: '800' } } }
                }
            }
        });
    }

    window.addEventListener('load', () => {
        initLuxCharts();
        initTrajChart();
    });
</script>
@endsection
