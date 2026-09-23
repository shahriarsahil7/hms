@extends('layouts.app')
@section('page-title', 'New Invoice')
@section('content')
<div class="card" style="max-width:800px">
    <div class="card-body">
        <form method="POST" action="{{ route('invoices.store') }}" id="invoice-form">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">Select patient</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id', $appointment->patient_id ?? null)==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($appointment)
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @endif
                <div class="col-md-3 mb-3">
                    <label class="form-label small fw-semibold">Issue date</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', now()->toDateString()) }}" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label small fw-semibold">Due date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control">
                </div>
            </div>

            <label class="form-label small fw-semibold">Line items</label>
            <div id="items">
                <div class="row g-2 item-row mb-2">
                    <div class="col-md-6"><input type="text" name="items[0][description]" class="form-control form-control-sm" placeholder="Description (e.g. Consultation fee)" required></div>
                    <div class="col-md-2"><input type="number" name="items[0][quantity]" value="1" min="1" class="form-control form-control-sm qty" placeholder="Qty" required></div>
                    <div class="col-md-3"><input type="number" step="0.01" name="items[0][unit_price]" class="form-control form-control-sm price" placeholder="Unit price" required></div>
                    <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></div>
                </div>
            </div>
            <button type="button" id="add-item" class="btn btn-sm btn-outline-secondary mb-3">+ Add line item</button>

            <div class="text-end mb-3">
                <strong>Total: &#2547;<span id="grand-total">0.00</span></strong>
            </div>

            <button type="submit" class="btn btn-brand">Create Invoice</button>
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 1;
const itemsContainer = document.getElementById('items');
const grandTotal = document.getElementById('grand-total');

function recalcTotal(){
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        total += qty * price;
    });
    grandTotal.textContent = total.toFixed(2);
}

document.getElementById('add-item').addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'row g-2 item-row mb-2';
    row.innerHTML = `
        <div class="col-md-6"><input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm" placeholder="Description" required></div>
        <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="form-control form-control-sm qty" placeholder="Qty" required></div>
        <div class="col-md-3"><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm price" placeholder="Unit price" required></div>
        <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></div>
    `;
    itemsContainer.appendChild(row);
    itemIndex++;
    attachListeners();
});

function attachListeners(){
    document.querySelectorAll('.qty, .price').forEach(el => el.oninput = recalcTotal);
    document.querySelectorAll('.remove-row').forEach(btn => btn.onclick = (e) => {
        if (document.querySelectorAll('.item-row').length > 1) {
            e.target.closest('.item-row').remove();
            recalcTotal();
        }
    });
}
attachListeners();
</script>
@endpush
@endsection
