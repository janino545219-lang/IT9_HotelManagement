<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['guest', 'room'])->latest()->paginate(5);
        return view('admin.reservations', compact('reservations'));
    }

    public function create()
    {
        $guests    = Guest::orderBy('first_name')->get();
        $rooms     = Room::where('status', 'available')->orderBy('room_number')->get();
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('admin.reservations-form', compact('guests', 'rooms', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id'       => 'required|exists:guests,guest_id',
            'room_id'        => 'required|exists:rooms,room_id',
            'employee_id'    => 'required|exists:employees,employee_id',
            'check_in_date'  => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests'     => 'required|integer|min:1',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required',
        ]);

        Reservation::create([
            'reservation_id' => Str::uuid(),
            'guest_id'       => $request->guest_id,
            'room_id'        => $request->room_id,
            'employee_id'    => $request->employee_id,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'num_guests'     => $request->num_guests,
            'total_amount'   => $request->total_amount,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    public function show(string $id)
    {
        $reservation = Reservation::with(['guest', 'room', 'employee'])->findOrFail($id);
        return view('admin.reservations-show', compact('reservation'));
    }

    public function edit(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $guests      = Guest::orderBy('first_name')->get();
        $rooms       = Room::orderBy('room_number')->get();
        $employees   = Employee::where('is_active', true)->orderBy('first_name')->get();
        return view('admin.reservations-form', compact('reservation', 'guests', 'rooms', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'guest_id'       => 'required|exists:guests,guest_id',
            'room_id'        => 'required|exists:rooms,room_id',
            'employee_id'    => 'required|exists:employees,employee_id',
            'check_in_date'  => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'num_guests'     => 'required|integer|min:1',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required',
        ]);

        $reservation->update($request->only([
            'guest_id', 'room_id', 'employee_id',
            'check_in_date', 'check_out_date',
            'num_guests', 'total_amount', 'status'
        ]));

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    public function destroy(string $id)
    {
        Reservation::findOrFail($id)->delete();
        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation deleted.');
    }
}