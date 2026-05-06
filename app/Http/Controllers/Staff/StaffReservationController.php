<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffReservationController extends Controller
{
    public function index()
    {
        $reservations = DB::table('reservations')
            ->join('guests', 'reservations.guest_id', '=', 'guests.guest_id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
            // ✅ NEW: Include invoice/payment info
            ->leftJoin('invoices', 'reservations.reservation_id', '=', 'invoices.reservation_id')
            ->select(
                'reservations.*',
                'guests.first_name',
                'guests.last_name',
                'guests.phone',
                'rooms.room_number',
                'rooms.room_type',
                'invoices.invoice_id',
                'invoices.total_amount as invoice_total',
                'invoices.status as payment_status'
            )
            ->orderBy('reservations.created_at', 'desc')
            ->paginate(5);

        return view('staff.reservations.index', compact('reservations'));
    }

    // ✅ NEW: Staff confirms a reservation
    public function confirm($id)
    {
        DB::table('reservations')
            ->where('reservation_id', $id)
            ->update(['status' => 'confirmed', 'updated_at' => now()]);

        return back()->with('success', 'Reservation confirmed.');
    }

    // ✅ NEW: Staff checks in a reservation
    public function checkIn($id)
    {
        DB::table('reservations')
            ->where('reservation_id', $id)
            ->update(['status' => 'checked_in', 'updated_at' => now()]);

        return back()->with('success', 'Guest checked in successfully.');
    }

    // ✅ NEW: Staff marks invoice as paid
    public function markPaid($invoiceId)
    {
        DB::table('invoices')
            ->where('invoice_id', $invoiceId)
            ->update(['status' => 'paid', 'updated_at' => now()]);

        // Also create a payment record
        DB::table('payments')->insert([
            'payment_id'     => (string) Str::uuid(),
            'invoice_id'     => $invoiceId,
            'amount'         => DB::table('invoices')->where('invoice_id', $invoiceId)->value('total_amount'),
            'payment_method' => 'cash',
            'status'         => 'paid',
            'paid_at'        => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }

    // ✅ NEW: Staff checkout form
    public function checkout($id)
    {
        $reservation = DB::table('reservations')
            ->join('guests', 'reservations.guest_id', '=', 'guests.guest_id')
            ->join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
            ->where('reservations.reservation_id', $id)
            ->select('reservations.*', 'guests.first_name', 'guests.last_name', 'rooms.room_number', 'rooms.room_type')
            ->first();

        if (!$reservation) {
            return redirect()->route('staff.reservations.index')->with('error', 'Reservation not found.');
        }

        $invoice = DB::table('invoices')->where('reservation_id', $id)->first();

        return view('staff.reservations.checkout', compact('reservation', 'invoice'));
    }

    // ✅ NEW: Process checkout and additional charges
    public function processCheckout(Request $request, $id)
    {
        $request->validate([
            'additional_charges' => 'nullable|numeric|min:0',
            'additional_charges_notes' => 'nullable|string'
        ]);

        $additionalCharges = $request->input('additional_charges', 0) ?: 0;
        $notes = $request->input('additional_charges_notes', '');

        // Update reservation status
        DB::table('reservations')
            ->where('reservation_id', $id)
            ->update(['status' => 'checked_out', 'updated_at' => now()]);

        // Update invoice
        $invoice = DB::table('invoices')->where('reservation_id', $id)->first();
        if ($invoice) {
            $newTotal = $invoice->total_amount + $additionalCharges;
            DB::table('invoices')
                ->where('reservation_id', $id)
                ->update([
                    'additional_charges' => $additionalCharges,
                    'additional_charges_notes' => $notes,
                    'total_amount' => $newTotal,
                    'updated_at' => now()
                ]);

            // Redirect to standard payment interface
            return redirect()->route('staff.payments.create')->withInput(['invoice_id' => $invoice->invoice_id, 'amount' => $newTotal])->with('success', 'Checkout processed. Please collect payment.');
        }

        return redirect()->route('staff.reservations.index')->with('success', 'Checked out successfully.');
    }
}