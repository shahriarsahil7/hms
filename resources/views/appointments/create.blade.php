@extends('layouts.app')
@section('page-title', 'Book Appointment')
@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">
        <form method="POST" action="{{ route('appointments.store') }}" id="appt-form">
            @csrf

            @if($patient)
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="alert alert-light border small">Booking for: <strong>{{ $patient->name }}</strong></div>
            @else
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">Select patient</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id')==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label small fw-semibold">Department</label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">Any department</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" @selected(old('department_id')==$d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Doctor</label>
                <select name="doctor_id" id="doctor_id" class="form-select" required>
                    <option value="">Select doctor</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" data-department="{{ $d->department_id }}" @selected(old('doctor_id', request('doctor_id'))==$d->id)>
                            Dr. {{ $d->user->name }} — {{ $d->specialization }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Date</label>
                <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" min="{{ now()->toDateString() }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Available time slots</label>
                <div id="slots-container" class="d-flex flex-wrap gap-2">
                    <span class="text-secondary small">Select a doctor and date to see available slots.</span>
                </div>
                <input type="hidden" name="appointment_time" id="appointment_time" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Reason for visit</label>
                <textarea name="reason" class="form-control" rows="2">{{ old('reason') }}</textarea>
            </div>

            <button type="submit" class="btn btn-brand">Book Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
const departmentSelect = document.getElementById('department_id');
const doctorSelect = document.getElementById('doctor_id');
const dateInput = document.getElementById('appointment_date');
const slotsContainer = document.getElementById('slots-container');
const timeInput = document.getElementById('appointment_time');

departmentSelect.addEventListener('change', () => {
    const dept = departmentSelect.value;
    [...doctorSelect.options].forEach(opt => {
        if (!opt.value) return;
        opt.hidden = dept && opt.dataset.department !== dept;
    });
    doctorSelect.value = '';
    resetSlots();
});

function resetSlots(){
    slotsContainer.innerHTML = '<span class="text-secondary small">Select a doctor and date to see available slots.</span>';
    timeInput.value = '';
}

async function loadSlots(){
    const doctorId = doctorSelect.value;
    const date = dateInput.value;
    if(!doctorId || !date){ resetSlots(); return; }

    slotsContainer.innerHTML = '<span class="text-secondary small">Loading slots...</span>';
    timeInput.value = '';

    const url = `{{ route('appointments.available-slots') }}?doctor_id=${doctorId}&date=${date}`;
    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (!data.slots || data.slots.length === 0) {
            slotsContainer.innerHTML = '<span class="text-secondary small">No available slots for this date.</span>';
            return;
        }
        slotsContainer.innerHTML = '';
        data.slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-brand slot-btn';
            btn.textContent = slot;
            btn.addEventListener('click', () => {
                document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('btn-brand', 'text-white'));
                document.querySelectorAll('.slot-btn').forEach(b => b.classList.add('btn-outline-brand'));
                btn.classList.remove('btn-outline-brand');
                btn.classList.add('btn-brand', 'text-white');
                timeInput.value = slot;
            });
            slotsContainer.appendChild(btn);
        });
    } catch (e) {
        slotsContainer.innerHTML = '<span class="text-danger small">Could not load slots. Try again.</span>';
    }
}

doctorSelect.addEventListener('change', loadSlots);
dateInput.addEventListener('change', loadSlots);

document.getElementById('appt-form').addEventListener('submit', (e) => {
    if (!timeInput.value) {
        e.preventDefault();
        alert('Please select an available time slot.');
    }
});
</script>
@endpush
@endsection
