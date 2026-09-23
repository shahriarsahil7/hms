@extends('layouts.app')
@section('page-title', 'Medical Record')
@section('content')
@php $user = auth()->user(); @endphp
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                Visit Summary
                <a href="{{ route('medical-records.edit', $record) }}" class="btn btn-sm btn-outline-brand">Edit</a>
            </div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-5">Patient</dt><dd class="col-7"><a href="{{ route('patients.show', $record->patient) }}">{{ $record->patient->name }}</a></dd>
                    <dt class="col-5">Doctor</dt><dd class="col-7">Dr. {{ $record->doctor->name() }}</dd>
                    <dt class="col-5">Visit date</dt><dd class="col-7">{{ $record->visit_date->format('d M Y') }}</dd>
                    <dt class="col-5">Blood pressure</dt><dd class="col-7">{{ $record->blood_pressure ?? '—' }}</dd>
                    <dt class="col-5">Temperature</dt><dd class="col-7">{{ $record->temperature_celsius ? $record->temperature_celsius.' °C' : '—' }}</dd>
                    <dt class="col-5">Pulse</dt><dd class="col-7">{{ $record->pulse_bpm ? $record->pulse_bpm.' bpm' : '—' }}</dd>
                    <dt class="col-5">Weight</dt><dd class="col-7">{{ $record->weight_kg ? $record->weight_kg.' kg' : '—' }}</dd>
                    <dt class="col-5">Height</dt><dd class="col-7">{{ $record->height_cm ? $record->height_cm.' cm' : '—' }}</dd>
                </dl>
                <hr>
                <p class="small mb-1"><strong>Chief complaint</strong></p>
                <p class="small text-secondary">{{ $record->chief_complaint ?? '—' }}</p>
                <p class="small mb-1"><strong>Notes</strong></p>
                <p class="small text-secondary mb-0">{{ $record->notes ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header bg-white fw-semibold">Diagnoses</div>
            <div class="card-body">
                @forelse($record->diagnoses as $dx)
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                        <div>
                            <span class="badge badge-soft-{{ $dx->severityBadgeColor() }} me-2">{{ ucfirst($dx->severity) }}</span>
                            <strong>{{ $dx->diagnosis_name }}</strong> @if($dx->icd_code)<span class="text-secondary small">({{ $dx->icd_code }})</span>@endif
                            <div class="small text-secondary">{{ $dx->description }}</div>
                        </div>
                        @if($user->isDoctor())
                            <form method="POST" action="{{ route('diagnoses.destroy', $dx) }}" onsubmit="return confirm('Remove this diagnosis?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger p-0">Remove</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-secondary small mb-0">No diagnoses recorded.</p>
                @endforelse

                @if($user->isDoctor())
                    <form method="POST" action="{{ route('diagnoses.store', $record) }}" class="mt-3 border-top pt-3">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-5">
                                <input type="text" name="diagnosis_name" class="form-control form-control-sm" placeholder="Diagnosis name" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="icd_code" class="form-control form-control-sm" placeholder="ICD code (optional)">
                            </div>
                            <div class="col-md-2">
                                <select name="severity" class="form-select form-select-sm" required>
                                    @foreach(['mild','moderate','severe','critical'] as $sev)
                                        <option value="{{ $sev }}">{{ ucfirst($sev) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-brand w-100">Add</button>
                            </div>
                            <div class="col-12">
                                <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Description (optional)"></textarea>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white fw-semibold">Prescriptions</div>
            <div class="card-body">
                @forelse($record->prescriptions as $rx)
                    <div class="border rounded p-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <strong class="small">Prescription #{{ $rx->id }} — {{ $rx->issue_date->format('d M Y') }}</strong>
                            <a href="{{ route('prescriptions.show', $rx) }}" class="small">Print / View</a>
                        </div>
                        <ul class="small mb-0 mt-1">
                            @foreach($rx->items as $item)
                                <li>{{ $item->medicine_name }} — {{ $item->dosage }}, {{ $item->frequency }}, {{ $item->duration }}</li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-secondary small mb-0">No prescriptions yet.</p>
                @endforelse

                @if($user->isDoctor())
                    <form method="POST" action="{{ route('prescriptions.store', $record) }}" class="mt-3 border-top pt-3" id="rx-form">
                        @csrf
                        <div id="rx-items">
                            <div class="row g-2 rx-item mb-2">
                                <div class="col-md-3"><input type="text" name="items[0][medicine_name]" class="form-control form-control-sm" placeholder="Medicine" required></div>
                                <div class="col-md-2"><input type="text" name="items[0][dosage]" class="form-control form-control-sm" placeholder="Dosage"></div>
                                <div class="col-md-2"><input type="text" name="items[0][frequency]" class="form-control form-control-sm" placeholder="Frequency"></div>
                                <div class="col-md-2"><input type="text" name="items[0][duration]" class="form-control form-control-sm" placeholder="Duration"></div>
                                <div class="col-md-3"><input type="text" name="items[0][instructions]" class="form-control form-control-sm" placeholder="Instructions"></div>
                            </div>
                        </div>
                        <button type="button" id="add-rx-item" class="btn btn-sm btn-outline-secondary mb-2">+ Add another medicine</button>
                        <div class="mb-2">
                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="General notes (optional)"></textarea>
                        </div>
                        <button class="btn btn-sm btn-brand">Save Prescription</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rxIndex = 1;
document.getElementById('add-rx-item')?.addEventListener('click', () => {
    const container = document.getElementById('rx-items');
    const row = document.createElement('div');
    row.className = 'row g-2 rx-item mb-2';
    row.innerHTML = `
        <div class="col-md-3"><input type="text" name="items[${rxIndex}][medicine_name]" class="form-control form-control-sm" placeholder="Medicine" required></div>
        <div class="col-md-2"><input type="text" name="items[${rxIndex}][dosage]" class="form-control form-control-sm" placeholder="Dosage"></div>
        <div class="col-md-2"><input type="text" name="items[${rxIndex}][frequency]" class="form-control form-control-sm" placeholder="Frequency"></div>
        <div class="col-md-2"><input type="text" name="items[${rxIndex}][duration]" class="form-control form-control-sm" placeholder="Duration"></div>
        <div class="col-md-3"><input type="text" name="items[${rxIndex}][instructions]" class="form-control form-control-sm" placeholder="Instructions"></div>
    `;
    container.appendChild(row);
    rxIndex++;
});
</script>
@endpush
@endsection
