@extends('layouts.app')
@section('page-title', 'Add Schedule Slot')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('schedules.store') }}">
            @csrf
            @if(request('doctor_id'))
                <input type="hidden" name="doctor_id" value="{{ request('doctor_id') }}">
            @endif
            <div class="mb-3">
                <label class="form-label small fw-semibold">Day of week</label>
                <select name="day_of_week" class="form-select" required>
                    @foreach(\App\Models\DoctorSchedule::DAYS as $num => $name)
                        <option value="{{ $num }}" @selected(old('day_of_week')==$num)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label small fw-semibold">Start time</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label small fw-semibold">End time</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Slot duration (minutes)</label>
                <input type="number" name="slot_duration_minutes" value="{{ old('slot_duration_minutes', 30) }}" class="form-control" min="5" max="180" required>
            </div>
            <button type="submit" class="btn btn-brand">Add Slot</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
