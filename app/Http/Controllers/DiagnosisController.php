<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\MedicalRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiagnosisController extends Controller
{
    public function store(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        $validated = $request->validate([
            'diagnosis_name' => ['required', 'string', 'max:255'],
            'icd_code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'severity' => ['required', Rule::in(Diagnosis::SEVERITIES)],
        ]);

        $validated['medical_record_id'] = $medicalRecord->id;

        Diagnosis::create($validated);

        return redirect()->route('medical-records.show', $medicalRecord)->with('success', 'Diagnosis added.');
    }

    public function destroy(Diagnosis $diagnosis): RedirectResponse
    {
        $recordId = $diagnosis->medical_record_id;
        $diagnosis->delete();

        return redirect()->route('medical-records.show', $recordId)->with('success', 'Diagnosis removed.');
    }
}
