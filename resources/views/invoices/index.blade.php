@extends('layouts.app')
@section('page-title', 'Invoices & Payments')
@section('content')
@php $user = auth()->user(); @endphp
<div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach(['unpaid','partially_paid','paid','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search invoice # or patient">
        <button class="btn btn-sm btn-outline-brand">Search</button>
    </form>
    @if($user->isAdmin() || $user->isReceptionist())
        <a href="{{ route('invoices.create') }}" class="btn btn-brand btn-sm">+ New Invoice</a>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Invoice #</th><th>Patient</th><th>Issue Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($invoices as $inv)
                <tr>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->patient->name }}</td>
                    <td>{{ $inv->issue_date->format('d M Y') }}</td>
                    <td>&#2547;{{ number_format($inv->total_amount, 2) }}</td>
                    <td>&#2547;{{ number_format($inv->amount_paid, 2) }}</td>
                    <td>&#2547;{{ number_format($inv->balanceDue(), 2) }}</td>
                    <td><span class="badge badge-soft-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                    <td class="text-end"><a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-brand">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-secondary py-4">No invoices found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $invoices->links() }}</div>
@endsection
