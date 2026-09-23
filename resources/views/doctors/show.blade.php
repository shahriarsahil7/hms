@extends('layouts.app')
@section('page-title', 'Doctor Profile')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-1"> {{ $doctor->user->name }}</h5>
                <div class="text-secondary mb-3">{{ $doctor->specialization }}</div>
                <dl class="row small mb-0">
                    <dt class="col-5">Department</dt><dd class="col-7">{{ $doctor->department?->name ?? '—' }}</dd>
                    <dt class="col-5">Qualification</dt><dd class="col-7">{{ $doctor->qualification ?? '—' }}</dd>
                    <dt class="col-5">Experience</dt><dd class="col-7">{{ $doctor->experience_years }} years</dd>
                    <dt class="col-5">Fee</dt><dd class="col-7">&#2547;{{ number_format($doctor->consultation_fee, 2) }}</dd>
                    <dt class="col-5">Email</dt><dd class="col-7">{{ $doctor->user->email }}</dd>
                    <dt class="col-5">Phone</dt><dd class="col-7">{{ $doctor->user->phone ?? '—' }}</dd>
                </dl>
                @if($doctor->bio)
                    <hr><p class="small text-secondary mb-0">{{ $doctor->bio }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Weekly Availability</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead><tr><th>Day</th><th>Hours</th><th>Slot length</th></tr></thead>
                    <tbody>
                    @forelse($doctor->schedules as $s)
                        <tr>
                            <td>{{ $s->dayName() }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($s->start_time)->format('h:i A') }} – {{ \Illuminate\Support\Carbon::parse($s->end_time)->format('h:i A') }}</td>
                            <td>{{ $s->slot_duration_minutes }} min</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">No schedule published yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('appointments.create') }}?doctor_id={{ $doctor->id }}" class="btn btn-brand">Book Appointment</a>
        </div>
    </div>
</div>
@endsection
