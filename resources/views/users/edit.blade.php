@extends('layouts.app')
@section('page-title', 'Edit User')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Full name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Role</label>
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">New password <span class="text-secondary fw-normal">(leave blank to keep)</span></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            @if($user->role === 'doctor')
                <div class="border-top pt-3 mt-2">
                    <h6 class="fw-semibold mb-3">Doctor Profile</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Department</label>
                            <select name="department_id" class="form-select">
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" @selected(old('department_id', $user->doctor?->department_id)==$d->id)>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $user->doctor?->specialization) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold">Qualification</label>
                            <input type="text" name="qualification" value="{{ old('qualification', $user->doctor?->qualification) }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-semibold">Experience (yrs)</label>
                            <input type="number" name="experience_years" value="{{ old('experience_years', $user->doctor?->experience_years) }}" class="form-control" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-semibold">Consultation fee</label>
                            <input type="number" step="0.01" name="consultation_fee" value="{{ old('consultation_fee', $user->doctor?->consultation_fee) }}" class="form-control" min="0">
                        </div>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
