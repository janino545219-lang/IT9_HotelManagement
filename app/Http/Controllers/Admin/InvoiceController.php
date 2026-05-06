<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('guest')->latest()->paginate(5);
        return view('admin.invoices', compact('invoices'));
    }

    public function create()
    {
        $reservations = Reservation::with(['guest', 'room'])->get();
        $guests       = Guest::orderBy('first_name')->get();
        return view('admin.invoices-form', compact('reservations', 'guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'guest_id'       => 'required|exists:guests,guest_id',
            'subtotal'       => 'required|numeric|min:0',
            'tax_amount'     => 'nullable|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'total_amount'   => 'required|numeric|min:0',
            'status'         => 'required',
        ]);

        Invoice::create([
            'invoice_id'     => Str::uuid(),
            'reservation_id' => $request->reservation_id,
            'guest_id'       => $request->guest_id,
            'subtotal'       => $request->subtotal,
            'tax_amount'     => $request->tax_amount ?? 0,
            'discount'       => $request->discount ?? 0,
            'total_amount'   => $request->total_amount,
            'status'         => $request->status,
            'issued_at'      => $request->issued_at ?? now(),
            'due_date'       => $request->due_date,
        ]);

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(string $id)
    {
        $invoice = Invoice::with(['guest', 'payments'])->findOrFail($id);
        return view('admin.invoices-show', compact('invoice'));
    }

    public function edit(string $id)
    {
        $invoice      = Invoice::findOrFail($id);
        $reservations = Reservation::with(['guest', 'room'])->get();
        $guests       = Guest::orderBy('first_name')->get();
        return view('admin.invoices-form', compact('invoice', 'reservations', 'guests'));
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'subtotal'     => 'required|numeric|min:0',
            'tax_amount'   => 'nullable|numeric|min:0',
            'discount'     => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status'       => 'required',
        ]);

        $invoice->update($request->only([
            'reservation_id', 'guest_id', 'subtotal',
            'tax_amount', 'discount', 'total_amount',
            'status', 'issued_at', 'due_date'
        ]));

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(string $id)
    {
        Invoice::findOrFail($id)->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice deleted.');
    }
}