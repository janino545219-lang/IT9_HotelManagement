<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // ── Summary Cards ───────────────────────────────────────────────
        $totalRevenue      = DB::table('payments')->where('status', 'completed')->sum('amount');
        $todayRevenue      = DB::table('payments')->where('status', 'completed')
                               ->whereDate('paid_at', today())->sum('amount');
        $monthRevenue      = DB::table('payments')->where('status', 'completed')
                               ->whereMonth('paid_at', now()->month)
                               ->whereYear('paid_at', now()->year)->sum('amount');
        $totalReservations = DB::table('reservations')->count();
        $totalWalkins      = DB::table('walk_ins')->count();
        $checkedOutToday   = DB::table('reservations')->where('status', 'checked_out')
                               ->whereDate('updated_at', today())->count()
                            + DB::table('walk_ins')->where('status', 'checked_out')
                               ->whereDate('updated_at', today())->count();

        // ── Daily Revenue – last 14 days ─────────────────────────────────
        $dailyLabels  = [];
        $dailyData    = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyLabels[] = $date->format('M d');
            $dailyData[]   = (float) DB::table('payments')
                               ->where('status', 'completed')
                               ->whereDate('paid_at', $date->toDateString())
                               ->sum('amount');
        }

        // ── Monthly Revenue – last 12 months ────────────────────────────
        $monthlyLabels = [];
        $monthlyData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData[]   = (float) DB::table('payments')
                                ->where('status', 'completed')
                                ->whereMonth('paid_at', $month->month)
                                ->whereYear('paid_at', $month->year)
                                ->sum('amount');
        }

        // ── Yearly Revenue – last 5 years ────────────────────────────────
        $yearlyLabels = [];
        $yearlyData   = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::today()->year - $i;
            $yearlyLabels[] = (string) $year;
            $yearlyData[]   = (float) DB::table('payments')
                               ->where('status', 'completed')
                               ->whereYear('paid_at', $year)
                               ->sum('amount');
        }

        // ── Top Room Types (Reservations) ────────────────────────────────
        $roomTypeStats = DB::table('reservations')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
            ->select('rooms.room_type', DB::raw('count(*) as total'))
            ->groupBy('rooms.room_type')
            ->orderByDesc('total')
            ->get();

        // ── Reservations vs Walk-Ins per month (last 6 months) ───────────
        $compLabels     = [];
        $compReserv     = [];
        $compWalkins    = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonths($i);
            $compLabels[]  = $month->format('M Y');
            $compReserv[]  = DB::table('reservations')
                              ->whereMonth('created_at', $month->month)
                              ->whereYear('created_at', $month->year)
                              ->count();
            $compWalkins[] = DB::table('walk_ins')
                              ->whereMonth('created_at', $month->month)
                              ->whereYear('created_at', $month->year)
                              ->count();
        }

        return view('admin.reports', compact(
            'totalRevenue', 'todayRevenue', 'monthRevenue',
            'totalReservations', 'totalWalkins', 'checkedOutToday',
            'dailyLabels', 'dailyData',
            'monthlyLabels', 'monthlyData',
            'yearlyLabels', 'yearlyData',
            'roomTypeStats',
            'compLabels', 'compReserv', 'compWalkins'
        ));
    }
}