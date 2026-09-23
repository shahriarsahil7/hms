<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

/**
 * Browsing doctor profiles. Doctor accounts are created/edited via
 * UserController (Admin > User Management) since a doctor is a User + profile.
 */
class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with(['user', 'department'])
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->string('search').'%';
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', $term))
                    ->orWhere('specialization', 'like', $term);
            })
            ->paginate(12)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('doctors.index', compact('doctors', 'departments'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'schedules' => fn ($q) => $q->orderBy('day_of_week')]);

        return view('doctors.show', compact('doctor'));
    }
}
