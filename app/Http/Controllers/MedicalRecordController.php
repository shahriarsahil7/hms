<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $records = MedicalRecord::with(['patient', 'doctor.user'])
            ->when($user->isDoctor(), fn ($q) => $q->where('doctor_id', optional($user->doctor)->id ?? 0))
            ->when($user->isPatient(), fn ($q) => $q->where('patient_id', optional($user->patient)->id ?? 0))
            ->when($request->filled('patient_id'), fn ($q) => $q->where('patient_id', $request->integer('patient_id')))
            ->latest('visit_date')
            ->paginate(15)
            ->withQueryString();

        return view('medical-records.index', compact('records'));
    }

    /** Typically created from an appointment ("Start visit"). */
    public function create(Request $request)
    {
        $user = $request->user();
        abort_unless($user->isDoctor() && $user->doctor, 403, 'Only doctors can create medical records.');

        $appointment = null;
        if ($request->filled('appointment_id')) {
            $appointment = Appointment::with('patient')->findOrFail($request->integer('appointment_id'));
        }

        $patients = Patient::orderBy('name')->get();

        return view('medical-records.create', compact('appointment', 'patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->isDoctor() && $user->doctor, 403);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'visit_date' => ['required', 'date'],
            'chief_complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'temperature_celsius' => ['nullable', 'numeric', 'between:25,45'],
            'pulse_bpm' => ['nullable', 'integer', 'between:20,250'],
            'weight_kg' => ['nullable', 'numeric', 'between:0,400'],
            'height_cm' => ['nullable', 'numeric', 'between:0,300'],
        ]);

        $validated['doctor_id'] = $user->doctor->id;

        $record = MedicalRecord::create($validated);

        if (! empty($validated['appointment_id'])) {
            Appointment::where('id', $validated['appointment_id'])->update(['status' => 'completed']);
        }

        return redirect()->route('medical-records.show', $record)->with('success', 'Medical record created. You can now add a diagnosis and prescription.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'doctor.user', 'diagnoses', 'prescriptions.items']);

        return view('medical-records.show', ['record' => $medicalRecord]);
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        return view('medical-records.edit', ['record' => $medicalRecord]);
    }

    public function update(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $validated = $request->validate([
            'chief_complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'temperature_celsius' => ['nullable', 'numeric', 'between:25,45'],
            'pulse_bpm' => ['nullable', 'integer', 'between:20,250'],
            'weight_kg' => ['nullable', 'numeric', 'between:0,400'],
            'height_cm' => ['nullable', 'numeric', 'between:0,300'],
        ]);

        $medicalRecord->update($validated);

        return redirect()->route('medical-records.show', $medicalRecord)->with('success', 'Medical record updated.');
    }
}
