<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice.guest', 'invoice.reservation.room')->latest()->paginate(5);
        return view('admin.payments', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::with('guest')->where('status', '!=', 'paid')->get();
        return view('admin.payments-form', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'required|in:completed,pending,failed,refunded',
            'paid_at' => 'nullable|date'
        ]);

        Payment::create($request->all());

        // Update invoice status if fully paid? We can do this automatically or leave it to staff/admin to manually do it.
        // For simplicity, let's just create the payment here.

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(string $id)
    {
        $payment = Payment::with('invoice.guest')->findOrFail($id);
        return view('admin.payments-show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $invoices = Invoice::with('guest')->get();
        return view('admin.payments-form', compact('payment', 'invoices'));
    }

    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'required|in:completed,pending,failed,refunded',
            'paid_at' => 'nullable|date'
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }
}