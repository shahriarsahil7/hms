@extends('layouts.app')
@section('page-title', 'My Dashboard')
@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                Upcoming Appointments
                <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-brand">+ Book Appointment</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Date</th><th>Doctor</th><th>Department</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($upcomingAppointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d M Y') }} {{ \Illuminate\Support\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                            <td>Dr. {{ $appt->doctor->name() }}</td>
                            <td>{{ $appt->department?->name ?? '—' }}</td>
                            <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No upcoming appointments.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Recent Invoices</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Invoice #</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($invoices as $inv)
                        <tr>
                            <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                            <td>&#2547;{{ number_format($inv->total_amount, 2) }}</td>
                            <td><span class="badge badge-soft-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
