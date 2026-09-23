@extends('layouts.app')
@section('page-title', 'Appointment Details')
@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="fw-bold mb-0">Appointment #{{ $appointment->id }}</h5>
                    <span class="badge badge-soft-{{ $appointment->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$appointment->status)) }}</span>
                </div>
                <dl class="row small mb-0">
                    <dt class="col-5">Patient</dt><dd class="col-7"><a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name }}</a></dd>
                    <dt class="col-5">Doctor</dt><dd class="col-7">Dr. {{ $appointment->doctor->name() }}</dd>
                    <dt class="col-5">Department</dt><dd class="col-7">{{ $appointment->department?->name ?? '—' }}</dd>
                    <dt class="col-5">Date</dt><dd class="col-7">{{ $appointment->appointment_date->format('d M Y') }}</dd>
                    <dt class="col-5">Time</dt><dd class="col-7">{{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</dd>
                    <dt class="col-5">Reason</dt><dd class="col-7">{{ $appointment->reason ?? '—' }}</dd>
                    <dt class="col-5">Notes</dt><dd class="col-7">{{ $appointment->notes ?? '—' }}</dd>
                </dl>

                @php $user = auth()->user(); @endphp
                @if($user->isAdmin() || $user->isReceptionist() || $user->isDoctor())
                    <hr>
                    <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="d-flex gap-2">
                        @csrf @method('PUT')
                        <select name="status" class="form-select form-select-sm">
                            @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                                <option value="{{ $s }}" @selected($appointment->status===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-brand">Update</button>
                    </form>
                @endif

                @if(!in_array($appointment->status, ['cancelled','completed']))
                    <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="mt-2" onsubmit="return confirm('Cancel this appointment?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Cancel Appointment</button>
                    </form>
                @endif

                @if($user->isDoctor() && $appointment->status !== 'cancelled' && !$appointment->medicalRecord)
                    <a href="{{ route('medical-records.create') }}?appointment_id={{ $appointment->id }}" class="btn btn-sm btn-brand mt-2">Start Visit / Add Medical Record</a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        @if($appointment->medicalRecord)
            <div class="card">
                <div class="card-header bg-white fw-semibold">Medical Record for this Visit</div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Chief complaint:</strong> {{ $appointment->medicalRecord->chief_complaint ?? '—' }}</p>
                    <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}" class="btn btn-sm btn-outline-brand">View Full Record</a>
                </div>
            </div>
        @else
            <div class="alert alert-light border">No medical record has been created for this visit yet.</div>
        @endif
    </div>
</div>
@endsection
