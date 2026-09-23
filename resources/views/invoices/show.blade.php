@extends('layouts.app')
@section('page-title', 'Invoice Details')
@section('content')
@php $user = auth()->user(); @endphp
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">{{ $invoice->invoice_number }}</h5>
                        <div class="text-secondary small">Issued {{ $invoice->issue_date->format('d M Y') }} @if($invoice->due_date) · Due {{ $invoice->due_date->format('d M Y') }} @endif</div>
                    </div>
                    <span class="badge badge-soft-{{ $invoice->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$invoice->status)) }}</span>
                </div>
                <p class="small mb-3"><strong>Patient:</strong> <a href="{{ route('patients.show', $invoice->patient) }}">{{ $invoice->patient->name }}</a></p>

                <table class="table table-sm">
                    <thead><tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Amount</th></tr></thead>
                    <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>&#2547;{{ number_format($item->unit_price, 2) }}</td>
                            <td>&#2547;{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3" class="text-end">Total</th><th>&#2547;{{ number_format($invoice->total_amount, 2) }}</th></tr>
                        <tr><th colspan="3" class="text-end">Paid</th><th>&#2547;{{ number_format($invoice->amount_paid, 2) }}</th></tr>
                        <tr><th colspan="3" class="text-end">Balance Due</th><th>&#2547;{{ number_format($invoice->balanceDue(), 2) }}</th></tr>
                    </tfoot>
                </table>

                @if(($user->isAdmin() || $user->isReceptionist()) && !in_array($invoice->status, ['paid','cancelled']))
                    <form method="POST" action="{{ route('invoices.cancel', $invoice) }}" onsubmit="return confirm('Cancel this invoice?');" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-danger">Cancel Invoice</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold">Payment History</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th></th></tr></thead>
                    <tbody>
                    @forelse($invoice->payments as $p)
                        <tr>
                            <td>{{ $p->payment_date->format('d M Y') }}</td>
                            <td>&#2547;{{ number_format($p->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$p->payment_method)) }}</td>
                            <td>
                                @if($user->isAdmin())
                                    <form method="POST" action="{{ route('payments.destroy', [$invoice, $p]) }}" onsubmit="return confirm('Remove this payment?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-link text-danger p-0">Remove</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-3">No payments recorded.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(($user->isAdmin() || $user->isReceptionist()) && $invoice->balanceDue() > 0 && $invoice->status !== 'cancelled')
            <div class="card">
                <div class="card-header bg-white fw-semibold">Record Payment</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store', $invoice) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Amount (balance: &#2547;{{ number_format($invoice->balanceDue(), 2) }})</label>
                            <input type="number" step="0.01" max="{{ $invoice->balanceDue() }}" name="amount" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Method</label>
                            <select name="payment_method" class="form-select form-select-sm" required>
                                @foreach(['cash','card','mobile_banking','insurance'] as $m)
                                    <option value="{{ $m }}">{{ ucfirst(str_replace('_',' ',$m)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Payment date</label>
                            <input type="date" name="payment_date" value="{{ now()->toDateString() }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Transaction reference (optional)</label>
                            <input type="text" name="transaction_reference" class="form-control form-control-sm">
                        </div>
                        <button class="btn btn-sm btn-brand w-100">Record Payment</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
