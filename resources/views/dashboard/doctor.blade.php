@extends('layouts.app')
@section('page-title', 'Doctor Dashboard')
@section('content')
@unless($doctor)
    <div class="alert alert-warning">Your doctor profile isn't set up yet. Please contact the administrator.</div>
@else
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Today's Appointments</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Time</th><th>Patient</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse($todayAppointments as $appt)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                            <td>{{ $appt->patient->name }}</td>
                            <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                            <td><a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-brand">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No appointments today.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Upcoming Appointments</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Date</th><th>Patient</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($upcomingAppointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d M Y') }}</td>
                            <td>{{ $appt->patient->name }}</td>
                            <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">Nothing upcoming.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endunless
@endsection
