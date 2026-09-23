@extends('layouts.app')
@section('page-title', 'Patient Profile')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $patient->name }}</h5>
                <div class="text-secondary small mb-3">Patient ID #{{ $patient->id }}</div>
                <dl class="row small mb-0">
                    <dt class="col-5">Age</dt><dd class="col-7">{{ $patient->age() ?? '—' }}</dd>
                    <dt class="col-5">Gender</dt><dd class="col-7">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</dd>
                    <dt class="col-5">Blood group</dt><dd class="col-7">{{ $patient->blood_group ?? '—' }}</dd>
                    <dt class="col-5">Phone</dt><dd class="col-7">{{ $patient->phone ?? '—' }}</dd>
                    <dt class="col-5">Email</dt><dd class="col-7">{{ $patient->email ?? '—' }}</dd>
                    <dt class="col-5">Address</dt><dd class="col-7">{{ $patient->address ?? '—' }}</dd>
                    <dt class="col-5">Emergency</dt><dd class="col-7">{{ $patient->emergency_contact_name ?? '—' }} {{ $patient->emergency_contact_phone ? '('.$patient->emergency_contact_phone.')' : '' }}</dd>
                    <dt class="col-5">Allergies</dt><dd class="col-7">{{ $patient->allergies ?? 'None recorded' }}</dd>
                </dl>
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-brand mt-3">Edit Patient</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold">Appointments</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Date</th><th>Doctor</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($patient->appointments->sortByDesc('appointment_date') as $appt)
                        <tr>
                            <td><a href="{{ route('appointments.show', $appt) }}">{{ $appt->appointment_date->format('d M Y') }}</a></td>
                            <td>Dr. {{ $appt->doctor->name() }}</td>
                            <td><span class="badge badge-soft-{{ $appt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appt->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-3">No appointments yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold">Medical Records</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Visit Date</th><th>Doctor</th><th>Complaint</th><th></th></tr></thead>
                    <tbody>
                    @forelse($patient->medicalRecords->sortByDesc('visit_date') as $rec)
                        <tr>
                            <td>{{ $rec->visit_date->format('d M Y') }}</td>
                            <td>Dr. {{ $rec->doctor->name() }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($rec->chief_complaint, 40) }}</td>
                            <td><a href="{{ route('medical-records.show', $rec) }}" class="btn btn-sm btn-outline-brand">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-3">No medical records yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white fw-semibold">Invoices</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Invoice #</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($patient->invoices->sortByDesc('issue_date') as $inv)
                        <tr>
                            <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                            <td>&#2547;{{ number_format($inv->total_amount, 2) }}</td>
                            <td><span class="badge badge-soft-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-3">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
