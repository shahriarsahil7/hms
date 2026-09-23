@extends('layouts.app')
@section('page-title', 'Medical Records')
@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Visit Date</th><th>Patient</th><th>Doctor</th><th></th></tr></thead>
            <tbody>
            @forelse($records as $rec)
                <tr>
                    <td>{{ $rec->visit_date->format('d M Y') }}</td>
                    <td><a href="{{ route('patients.show', $rec->patient) }}">{{ $rec->patient->name }}</a></td>
                    <td>Dr. {{ $rec->doctor->name() }}</td>
                    <td class="text-end"><a href="{{ route('medical-records.show', $rec) }}" class="btn btn-sm btn-outline-brand">View</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-secondary py-4">No medical records yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $records->links() }}</div>
@endsection
