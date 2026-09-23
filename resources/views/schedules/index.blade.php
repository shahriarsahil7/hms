@extends('layouts.app')
@section('page-title', 'Doctor Schedules')
@section('content')
@if($doctors->isNotEmpty())
    <form class="d-flex gap-2 mb-3" method="GET">
        <select name="doctor_id" class="form-select form-select-sm" style="max-width:280px" onchange="this.form.submit()">
            @foreach($doctors as $d)
                <option value="{{ $d->id }}" @selected(($doctor->id ?? null)==$d->id)>Dr. {{ $d->user->name }}</option>
            @endforeach
        </select>
    </form>
@endif

@if($doctor)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-semibold mb-0">Weekly availability for Dr. {{ $doctor->user->name ?? $doctor->name() }}</h6>
        <a href="{{ route('schedules.create') }}?doctor_id={{ $doctor->id }}" class="btn btn-brand btn-sm">+ Add Slot</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Slot length</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($schedules as $s)
                    <tr>
                        <td>{{ $s->dayName() }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($s->start_time)->format('h:i A') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($s->end_time)->format('h:i A') }}</td>
                        <td>{{ $s->slot_duration_minutes }} min</td>
                        <td>
                            @if($s->is_active)
                                <span class="badge badge-soft-success">Active</span>
                            @else
                                <span class="badge badge-soft-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('schedules.edit', $s) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                            <form action="{{ route('schedules.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this slot?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">No availability set yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-info">Select a doctor to manage their schedule.</div>
@endif
@endsection
