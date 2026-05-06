<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\WalkIn;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms       = Room::count();
        $availableRooms   = Room::where('status', 'available')->count();
        $totalReservations = Reservation::count();
        $totalGuests      = Guest::count();
        $totalRevenue     = Payment::where('status', 'completed')->sum('amount');
        $pendingInvoices  = Invoice::where('status', 'unpaid')->count();
        $totalWalkins     = WalkIn::count();

        $recentReservations = Reservation::with(['guest', 'room'])
            ->latest()
            ->paginate(5, ['*'], 'res_page');

        $rooms = Room::latest()->paginate(5, ['*'], 'rooms_page');

        return view('admin.dashboard', compact(
            'totalRooms', 'availableRooms', 'totalReservations',
            'totalGuests', 'totalRevenue', 'pendingInvoices',
            'totalWalkins', 'recentReservations', 'rooms'
        ));
    }
}