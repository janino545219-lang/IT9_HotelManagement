<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\WalkIn;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffWalkInController extends Controller
{
    public function index()
    {
        $walkins = WalkIn::with('employee')->latest()->paginate(5);
        return view('staff.walkins.index', compact('walkins'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $rooms = \App\Models\Room::where('status', 'available')->orderBy('room_number')->get();
        return view('staff.walkins.form', compact('employees', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'num_guests'     => 'required|integer|min:1',
            'employee_id'    => 'required|exists:employees,employee_id',
            'room_id'        => 'nullable|exists:rooms,room_id',
            'check_in_date'  => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status'         => 'required',
        ]);

        WalkIn::create([
            'walkin_id'      => Str::uuid(),
            'guest_name'     => $request->guest_name,
            'phone'          => $request->phone,
            'num_guests'     => $request->num_guests,
            'employee_id'    => $request->employee_id,
            'room_id'        => $request->room_id ?: null,
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status'         => $request->status,
        ]);

        return redirect()->route('staff.walkins.index')
            ->with('success', 'Walk-in recorded successfully.');
    }

    public function edit(string $id)
    {
        $walkin    = WalkIn::findOrFail($id);
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $rooms = \App\Models\Room::orderBy('room_number')->get();
        return view('staff.walkins.form', compact('walkin', 'employees', 'rooms'));
    }

    public function update(Request $request, string $id)
    {
        $walkin = WalkIn::findOrFail($id);

        $request->validate([
            'guest_name'     => 'required|string|max:255',
            'num_guests'     => 'required|integer|min:1',
            'employee_id'    => 'required|exists:employees,employee_id',
            'room_id'        => 'nullable|exists:rooms,room_id',
            'check_in_date'  => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status'         => 'required',
        ]);

        $walkin->update($request->only([
            'guest_name', 'phone', 'num_guests',
            'employee_id', 'room_id', 'check_in_date', 'check_out_date', 'status'
        ]));

        return redirect()->route('staff.walkins.index')
            ->with('success', 'Walk-in updated successfully.');
    }

    public function destroy(string $id)
    {
        WalkIn::findOrFail($id)->delete();
        return redirect()->route('staff.walkins.index')
            ->with('success', 'Walk-in deleted.');
    }

    public function checkout(string $id)
    {
        $walkin = WalkIn::with('room')->findOrFail($id);

        $invoice = \DB::table('invoices')->where('walkin_id', $id)->first();

        // Auto-calculate room subtotal based on room price and nights
        $autoSubtotal = 0;
        if ($walkin->room) {
            $nights = $walkin->check_in_date->diffInDays($walkin->check_out_date);
            $autoSubtotal = $nights * $walkin->room->price_per_night;
        }

        return view('staff.walkins.checkout', compact('walkin', 'invoice', 'autoSubtotal'));
    }

    public function processCheckout(Request $request, string $id)
    {
        $request->validate([
            'room_amount' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'additional_charges_notes' => 'nullable|string'
        ]);

        $walkin = WalkIn::findOrFail($id);
        
        $roomAmount = $request->input('room_amount', 0);
        $additionalCharges = $request->input('additional_charges', 0) ?: 0;
        $notes = $request->input('additional_charges_notes', '');
        $totalAmount = $roomAmount + $additionalCharges;

        $walkin->update([
            'status' => 'checked_out'
        ]);

        $invoice = \DB::table('invoices')->where('walkin_id', $id)->first();
        if ($invoice) {
            \DB::table('invoices')->where('walkin_id', $id)->update([
                'subtotal' => $roomAmount,
                'additional_charges' => $additionalCharges,
                'additional_charges_notes' => $notes,
                'total_amount' => $totalAmount,
                'updated_at' => now()
            ]);
            $invoiceId = $invoice->invoice_id;
        } else {
            $invoiceId = (string) \Illuminate\Support\Str::uuid();
            \DB::table('invoices')->insert([
                'invoice_id' => $invoiceId,
                'walkin_id' => $walkin->walkin_id,
                'subtotal' => $roomAmount,
                'tax_amount' => 0,
                'discount' => 0,
                'additional_charges' => $additionalCharges,
                'additional_charges_notes' => $notes,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'issued_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('staff.payments.create')->withInput(['invoice_id' => $invoiceId, 'amount' => $totalAmount])->with('success', 'Walk-in checked out. Please collect payment.');
    }
}