@extends('layouts.app')
@section('page-title', 'Edit Medical Record')
@section('content')
<div class="card" style="max-width:750px">
    <div class="card-body">
        <form method="POST" action="{{ route('medical-records.update', $record) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label small fw-semibold">Chief complaint</label>
                <textarea name="chief_complaint" class="form-control" rows="2">{{ old('chief_complaint', $record->chief_complaint) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Blood pressure</label>
                    <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $record->blood_pressure) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Temperature (°C)</label>
                    <input type="number" step="0.1" name="temperature_celsius" value="{{ old('temperature_celsius', $record->temperature_celsius) }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Pulse (bpm)</label>
                    <input type="number" name="pulse_bpm" value="{{ old('pulse_bpm', $record->pulse_bpm) }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $record->weight_kg) }}" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-semibold">Height (cm)</label>
                    <input type="number" step="0.1" name="height_cm" value="{{ old('height_cm', $record->height_cm) }}" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $record->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('medical-records.show', $record) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
