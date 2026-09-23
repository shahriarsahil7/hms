<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    /** List schedules. Admin/receptionist see all doctors; a doctor sees only their own. */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isDoctor()) {
            $doctor = $user->doctor;
            $schedules = $doctor ? $doctor->schedules()->orderBy('day_of_week')->get() : collect();

            return view('schedules.index', ['schedules' => $schedules, 'doctor' => $doctor, 'doctors' => collect()]);
        }

        $doctors = Doctor::with('user')->get();
        $selectedDoctorId = $request->integer('doctor_id') ?: optional($doctors->first())->id;
        $schedules = $selectedDoctorId
            ? DoctorSchedule::where('doctor_id', $selectedDoctorId)->orderBy('day_of_week')->get()
            : collect();

        return view('schedules.index', [
            'schedules' => $schedules,
            'doctors' => $doctors,
            'doctor' => $doctors->firstWhere('id', $selectedDoctorId),
        ]);
    }

    public function create(Request $request)
    {
        $doctor = $this->resolveDoctorForRequest($request);

        return view('schedules.create', compact('doctor'));
    }

    public function store(Request $request): RedirectResponse
    {
        $doctor = $this->resolveDoctorForRequest($request, requireExplicit: true);

        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration_minutes' => ['required', 'integer', 'min:5', 'max:180'],
        ]);

        $validated['doctor_id'] = $doctor->id;

        DoctorSchedule::create($validated);

        return redirect()->route('schedules.index', ['doctor_id' => $doctor->id])->with('success', 'Schedule slot added.');
    }

    public function edit(DoctorSchedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, DoctorSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration_minutes' => ['required', 'integer', 'min:5', 'max:180'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $schedule->update($validated);

        return redirect()->route('schedules.index', ['doctor_id' => $schedule->doctor_id])->with('success', 'Schedule updated.');
    }

    public function destroy(DoctorSchedule $schedule): RedirectResponse
    {
        $doctorId = $schedule->doctor_id;
        $schedule->delete();

        return redirect()->route('schedules.index', ['doctor_id' => $doctorId])->with('success', 'Schedule slot removed.');
    }

    private function resolveDoctorForRequest(Request $request, bool $requireExplicit = false): Doctor
    {
        $user = $request->user();

        if ($user->isDoctor() && $user->doctor) {
            return $user->doctor;
        }

        $doctorId = $request->input('doctor_id');
        abort_if(! $doctorId, 422, 'A doctor must be selected.');

        return Doctor::findOrFail($doctorId);
    }
}
