@extends('layouts.app')
@section('page-title', 'Edit Schedule Slot')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('schedules.update', $schedule) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label small fw-semibold">Day of week</label>
                <select name="day_of_week" class="form-select" required>
                    @foreach(\App\Models\DoctorSchedule::DAYS as $num => $name)
                        <option value="{{ $num }}" @selected(old('day_of_week', $schedule->day_of_week)==$num)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label small fw-semibold">Start time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($schedule->start_time,0,5)) }}" class="form-control" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label small fw-semibold">End time</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($schedule->end_time,0,5)) }}" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Slot duration (minutes)</label>
                <input type="number" name="slot_duration_minutes" value="{{ old('slot_duration_minutes', $schedule->slot_duration_minutes) }}" class="form-control" min="5" max="180" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($schedule->is_active)>
                <label class="form-check-label small" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
