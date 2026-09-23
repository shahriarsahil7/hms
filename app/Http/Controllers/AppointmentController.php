<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Appointment::with(['patient', 'doctor.user', 'department'])
            ->when($user->isDoctor(), fn ($q) => $q->where('doctor_id', optional($user->doctor)->id ?? 0))
            ->when($user->isPatient(), fn ($q) => $q->where('patient_id', optional($user->patient)->id ?? 0))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('appointment_date', $request->date('date')));

        $appointments = $query->orderByDesc('appointment_date')->orderByDesc('appointment_time')->paginate(15)->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $doctors = Doctor::with('user')->get();

        $patients = collect();
        $patient = null;

        if ($user->isPatient()) {
            $patient = $user->patient;
        } else {
            $patients = Patient::orderBy('name')->get();
        }

        return view('appointments.create', compact('departments', 'doctors', 'patients', 'patient'));
    }

    /** AJAX: returns available time slots for a doctor on a given date. */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date = Carbon::parse($request->input('date'));
        $dayOfWeek = $date->dayOfWeek;

        $schedules = DoctorSchedule::where('doctor_id', $request->input('doctor_id'))
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        $allSlots = collect();
        foreach ($schedules as $schedule) {
            $allSlots = $allSlots->merge($schedule->generateSlots());
        }
        $allSlots = $allSlots->unique()->sort()->values();

        $bookedSlots = Appointment::where('doctor_id', $request->input('doctor_id'))
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_time')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'));

        $available = $allSlots->diff($bookedSlots)->values();

        if ($date->isToday()) {
            $now = Carbon::now()->format('H:i');
            $available = $available->filter(fn ($slot) => $slot > $now)->values();
        }

        return response()->json(['slots' => $available]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'patient_id' => [$user->isPatient() ? 'nullable' : 'required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $patientId = $user->isPatient() ? optional($user->patient)->id : $validated['patient_id'];
        abort_if(! $patientId, 422, 'No patient profile found for this account.');

        $exists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'That slot was just booked by someone else. Please pick another time.');
        }

        Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $validated['doctor_id'],
            'department_id' => $validated['department_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'created_by' => $user->id,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'department', 'medicalRecord.diagnoses', 'medicalRecord.prescriptions.items']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled.');
    }
}
