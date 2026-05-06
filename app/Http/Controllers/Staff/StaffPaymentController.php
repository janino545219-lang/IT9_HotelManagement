<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class StaffPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice.guest', 'invoice.reservation.room', 'invoice.walkin')->latest()->paginate(10);
        return view('staff.payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::with(['guest', 'reservation.room', 'walkin'])->where('status', '!=', 'paid')->get();
        return view('staff.payments.form', compact('invoices'));
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

        $payment = Payment::create($request->all());

        $this->updateRelatedStatuses($payment);

        return redirect()->route('staff.payments.receipt', $payment->payment_id)->with('success', 'Payment processed and recorded successfully.');
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $invoices = Invoice::with(['guest', 'reservation.room', 'walkin'])->get();
        return view('staff.payments.form', compact('payment', 'invoices'));
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

        $this->updateRelatedStatuses($payment);

        return redirect()->route('staff.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('staff.payments.index')->with('success', 'Payment deleted successfully.');
    }

    private function updateRelatedStatuses(Payment $payment)
    {
        if ($payment->status === 'completed' || $payment->status === 'paid') {
            $invoice = Invoice::with('reservation')->find($payment->invoice_id);
            if ($invoice) {
                $totalPaid = Payment::where('invoice_id', $invoice->invoice_id)->whereIn('status', ['completed', 'paid'])->sum('amount');
                if ($totalPaid >= $invoice->total_amount) {
                    $invoice->update(['status' => 'paid']);
                    if ($invoice->reservation) {
                        $invoice->reservation->update(['status' => 'confirmed']);
                    }
                } else if ($totalPaid > 0) {
                     // Partial Payment Scenario
                     $invoice->update(['status' => 'partially_paid']);
                     if ($invoice->reservation && $invoice->reservation->status == 'pending') {
                         $invoice->reservation->update(['status' => 'partially_paid']);
                     }
                }
            }
        } elseif ($payment->status === 'refunded') {
             $invoice = Invoice::with('reservation')->find($payment->invoice_id);
             if ($invoice) {
                 $invoice->update(['status' => 'refunded']);
                 if ($invoice->reservation) {
                     $invoice->reservation->update(['status' => 'cancelled']); // or 'refunded'
                 }
             }
        }
    }

    // Generate receipt
    public function receipt(string $id)
    {
        $payment = Payment::with('invoice.guest', 'invoice.reservation.room', 'invoice.walkin')->findOrFail($id);
        return view('staff.payments.receipt', compact('payment'));
    }
}
