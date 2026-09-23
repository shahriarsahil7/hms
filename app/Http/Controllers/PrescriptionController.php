<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function store(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->isDoctor() && $user->doctor, 403);

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_name' => ['required', 'string', 'max:255'],
            'items.*.dosage' => ['nullable', 'string', 'max:100'],
            'items.*.frequency' => ['nullable', 'string', 'max:100'],
            'items.*.duration' => ['nullable', 'string', 'max:100'],
            'items.*.instructions' => ['nullable', 'string', 'max:500'],
        ]);

        $prescription = Prescription::create([
            'medical_record_id' => $medicalRecord->id,
            'doctor_id' => $user->doctor->id,
            'patient_id' => $medicalRecord->patient_id,
            'issue_date' => now()->toDateString(),
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $prescription->items()->create($item);
        }

        return redirect()->route('medical-records.show', $medicalRecord)->with('success', 'Prescription added.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['items', 'doctor.user', 'patient', 'medicalRecord']);

        return view('prescriptions.show', compact('prescription'));
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        $recordId = $prescription->medical_record_id;
        $prescription->delete();

        return redirect()->route('medical-records.show', $recordId)->with('success', 'Prescription removed.');
    }
}
