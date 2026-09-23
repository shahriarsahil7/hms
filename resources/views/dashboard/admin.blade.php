@extends('layouts.app')
@section('page-title', 'Admin Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">{{ $stats['total_patients'] }}</div><div class="stat-label">Patients</div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">{{ $stats['total_doctors'] }}</div><div class="stat-label">Doctors</div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">{{ $stats['today_appointments'] }}</div><div class="stat-label">Today's Appts</div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">{{ $stats['pending_appointments'] }}</div><div class="stat-label">Pending Appts</div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">{{ $stats['unpaid_invoices'] }}</div><div class="stat-label">Unpaid Invoices</div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card"><div class="stat-value">&#2547;{{ number_format($stats['revenue_this_month'], 2) }}</div><div class="stat-label">Revenue (mo.)</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white fw-semibold">Recent Appointments</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead><tr><th>Patient</th><th>Doctor</th><th>Department</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recentAppointments as $appt)
                <tr>
                    <td><a href="{{ route('appointments.show', $appt) }}">{{ $appt->patient->name }}</a></td>
                    <td>Dr. {{ $appt->doctor->name() }}</td>
                    <td>{{ $appt->department?->name ?? '—' }}</td>
                    <td>{{ $appt->appointment_date->format('d M Y') }} {{ \Illuminate\Support\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                    <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">No appointments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
