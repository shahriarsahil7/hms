@extends('layouts.app')
@section('page-title', 'Prescription')
@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h5 class="fw-bold mb-0">{{ config('app.name') }}</h5>
                <div class="text-secondary small">Prescription #{{ $prescription->id }}</div>
            </div>
            <div class="text-end small">
                <div>Date: {{ $prescription->issue_date->format('d M Y') }}</div>
                <div>Dr. {{ $prescription->doctor->name() }}</div>
            </div>
        </div>

        <p class="small mb-1"><strong>Patient:</strong> {{ $prescription->patient->name }}</p>
        <hr>

        <table class="table table-sm">
            <thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instructions</th></tr></thead>
            <tbody>
            @foreach($prescription->items as $item)
                <tr>
                    <td>{{ $item->medicine_name }}</td>
                    <td>{{ $item->dosage ?? '—' }}</td>
                    <td>{{ $item->frequency ?? '—' }}</td>
                    <td>{{ $item->duration ?? '—' }}</td>
                    <td>{{ $item->instructions ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($prescription->notes)
            <p class="small"><strong>Notes:</strong> {{ $prescription->notes }}</p>
        @endif

        <div class="d-flex gap-2 mt-3 no-print">
            <button class="btn btn-sm btn-outline-brand" onclick="window.print()">Print</button>
            <a href="{{ route('medical-records.show', $prescription->medical_record_id) }}" class="btn btn-sm btn-outline-secondary">Back to Record</a>
        </div>
    </div>
</div>

@push('styles')
<style>@media print { .sidebar, .topbar, .no-print { display: none !important; } }</style>
@endpush
@endsection
