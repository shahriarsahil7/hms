@extends('layouts.app')
@section('page-title', 'New Medical Record')
@section('content')
<div class="card" style="max-width:750px">
    <div class="card-body">
        <form method="POST" action="{{ route('medical-records.store') }}">
            @csrf
            @if($appointment)
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                <div class="alert alert-light border small">Visit for: <strong>{{ $appointment->patient->name }}</strong> (Appointment #{{ $appointment->id }})</div>
            @else
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">Select patient</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label small fw-semibold">Visit date</label>
                <input type="date" name="visit_date" value="{{ old('visit_date', now()->toDateString()) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Chief complaint</label>
                <textarea name="chief_complaint" class="form-control" rows="2">{{ old('chief_complaint') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Blood pressure</label>
                    <input type="text" name="blood_pressure" value="{{ old('blood_pressure') }}" class="form-control" placeholder="120/80">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Temperature (°C)</label>
                    <input type="number" step="0.1" name="temperature_celsius" value="{{ old('temperature_celsius') }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Pulse (bpm)</label>
                    <input type="number" name="pulse_bpm" value="{{ old('pulse_bpm') }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg') }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Height (cm)</label>
                    <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm') }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-brand">Save Record</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
