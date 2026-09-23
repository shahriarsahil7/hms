<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        abort_if($invoice->status === 'paid', 422, 'This invoice is already fully paid.');
        abort_if($invoice->status === 'cancelled', 422, 'This invoice is cancelled.');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:'.$invoice->balanceDue()],
            'payment_method' => ['required', Rule::in(Payment::METHODS)],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['invoice_id'] = $invoice->id;
        $validated['received_by'] = $request->user()->id;

        Payment::create($validated);

        $invoice->refreshTotals();

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice, Payment $payment): RedirectResponse
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);

        $payment->delete();
        $invoice->refreshTotals();

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment removed and invoice recalculated.');
    }
}
