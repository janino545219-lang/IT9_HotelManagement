<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $totalRooms       = DB::table('rooms')->count();
        $availableRooms   = DB::table('rooms')->where('status', 'available')->count();
        $totalGuests      = DB::table('guests')->count();
        $totalReservations = DB::table('reservations')->count();

        return view('staff.dashboard', compact(
            'totalRooms', 'availableRooms', 'totalGuests', 'totalReservations'
        ));
    }
}