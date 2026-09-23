<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Admin-only: manage staff & patient login accounts.
 * Creating a "doctor" account also creates the linked Doctor profile.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->string('role')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->string('search').'%';
                $q->where(fn ($q2) => $q2->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('users.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => ['required', 'confirmed', Password::defaults()],
            'department_id' => ['nullable', 'required_if:role,doctor', 'exists:departments,id'],
            'specialization' => ['nullable', 'required_if:role,doctor', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:70'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated['role'] === User::ROLE_DOCTOR) {
            Doctor::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'specialization' => $validated['specialization'],
                'qualification' => $validated['qualification'] ?? null,
                'experience_years' => $validated['experience_years'] ?? 0,
                'consultation_fee' => $validated['consultation_fee'] ?? 0,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $user->load('doctor');

        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'department_id' => ['nullable', 'exists:departments,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:70'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($user->role === User::ROLE_DOCTOR && $user->doctor) {
            $user->doctor->update([
                'department_id' => $validated['department_id'] ?? $user->doctor->department_id,
                'specialization' => $validated['specialization'] ?? $user->doctor->specialization,
                'qualification' => $validated['qualification'] ?? $user->doctor->qualification,
                'experience_years' => $validated['experience_years'] ?? $user->doctor->experience_years,
                'consultation_fee' => $validated['consultation_fee'] ?? $user->doctor->consultation_fee,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
