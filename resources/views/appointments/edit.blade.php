@extends('layouts.app')
@section('page-title', 'Update Appointment')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <p class="text-secondary small mb-3">
            {{ $appointment->patient->name }} with Dr. {{ $appointment->doctor->name() }} on
            {{ $appointment->appointment_date->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
        </p>
        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                        <option value="{{ $s }}" @selected($appointment->status===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
