@extends('layouts.app')
@section('page-title', 'Appointments')
@section('content')
<div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm" onchange="this.form.submit()">
    </form>
    <a href="{{ route('appointments.create') }}" class="btn btn-brand btn-sm">+ Book Appointment</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Date & Time</th><th>Patient</th><th>Doctor</th><th>Department</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($appointments as $appt)
                <tr>
                    <td>{{ $appt->appointment_date->format('d M Y') }} {{ \Illuminate\Support\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                    <td>{{ $appt->patient->name }}</td>
                    <td>Dr. {{ $appt->doctor->name() }}</td>
                    <td>{{ $appt->department?->name ?? '—' }}</td>
                    <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-brand">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">No appointments found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $appointments->links() }}</div>
@endsection
