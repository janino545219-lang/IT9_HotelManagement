<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestReservationController extends Controller
{
    public function create()
    {
        $rooms = DB::table('rooms')
            ->where('status', 'available')
            ->where('is_active', 1)
            ->get();

        return view('guest.reservations.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,room_id',
            'check_in_date'  => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests'     => 'required|integer|min:1',
        ]);

        $user  = Auth::user();
        $guest = DB::table('guests')->where('user_id', $user->user_id)->first();

        if (!$guest) {
            return back()->withErrors(['error' => 'Guest profile not found.']);
        }

        $room = DB::table('rooms')->where('room_id', $request->room_id)->first();

        if (!$room || $room->status !== 'available') {
            return back()->withErrors(['room_id' => 'This room is no longer available.']);
        }

        if ($request->num_guests > $room->capacity) {
            return back()->withErrors([
                'num_guests' => "This room fits max {$room->capacity} guest(s)."
            ])->withInput();
        }

        $checkIn  = \Carbon\Carbon::parse($request->check_in_date);
        $checkOut = \Carbon\Carbon::parse($request->check_out_date);
        $nights   = $checkIn->diffInDays($checkOut);
        $subtotal = $nights * $room->price_per_night;
        $tax      = round($subtotal * 0.12, 2);
        $total    = $subtotal + $tax;

        $reservationId = (string) Str::uuid();

        // Staff will be assigned during check-in
        DB::table('reservations')->insert([
            'reservation_id' => $reservationId,
            'guest_id'       => $guest->guest_id,
            'room_id'        => $request->room_id,
            'employee_id'    => null,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'num_guests'     => $request->num_guests,
            'status'         => 'pending',
            'total_amount'   => $total,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // ✅ NEW: Auto-create invoice after booking
        DB::table('invoices')->insert([
            'invoice_id'     => (string) Str::uuid(),
            'reservation_id' => $reservationId,
            'guest_id'       => $guest->guest_id,
            'subtotal'       => $subtotal,
            'tax_amount'     => $tax,
            'discount'       => 0,
            'total_amount'   => $total,
            'status'         => 'unpaid',
            'issued_at'      => now(),
            'due_date'       => $request->check_in_date,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('guest.reservations.index')
            ->with('success', 'Room booked successfully! Your reservation is pending confirmation.');
    }

    public function index()
    {
        $user  = Auth::user();
        $guest = DB::table('guests')->where('user_id', $user->user_id)->first();

        $reservations = [];

        if ($guest) {
            $reservations = DB::table('reservations')
                ->join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
                // ✅ NEW: Join invoices so guest can see payment status
                ->leftJoin('invoices', 'reservations.reservation_id', '=', 'invoices.reservation_id')
                ->where('reservations.guest_id', $guest->guest_id)
                ->select(
                    'reservations.*',
                    'rooms.room_number',
                    'rooms.room_type',
                    'rooms.price_per_night',
                    'rooms.image',
                    'invoices.status as payment_status',
                    'invoices.total_amount as invoice_total'
                )
                ->orderBy('reservations.created_at', 'desc')
                ->get();
        }

        return view('guest.reservations.index', compact('reservations'));
    }
}