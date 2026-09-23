@extends('layouts.app')
@section('page-title', 'Front Desk Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="stat-card"><div class="stat-value">{{ $stats['total_patients'] }}</div><div class="stat-label">Patients</div></div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card"><div class="stat-value">{{ $stats['today_appointments'] }}</div><div class="stat-label">Today's Appts</div></div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card"><div class="stat-value">{{ $stats['unpaid_invoices'] }}</div><div class="stat-label">Unpaid Invoices</div></div>
    </div>
</div>
<div class="card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        Today's Appointments
        <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-brand">+ Book Appointment</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($todayAppointments as $appt)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                    <td>{{ $appt->patient->name }}</td>
                    <td>Dr. {{ $appt->doctor->name() }}</td>
                    <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                    <td><a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-brand">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">No appointments today.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
