@extends('layouts.app')
@section('page-title', 'New User')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Role</label>
                    <select name="role" id="role" class="form-select" required onchange="toggleDoctorFields()">
                        <option value="">Select role</option>
                        <option value="admin" @selected(old('role')==='admin')>Admin</option>
                        <option value="doctor" @selected(old('role')==='doctor')>Doctor</option>
                        <option value="receptionist" @selected(old('role')==='receptionist')>Receptionist</option>
                        <option value="patient" @selected(old('role')==='patient')>Patient</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div id="doctor-fields" class="border-top pt-3 mt-2" style="display:none">
                <h6 class="fw-semibold mb-3">Doctor Profile</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id')==$d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}" class="form-control" placeholder="e.g. MBBS, MD">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-semibold">Experience (yrs)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years') }}" class="form-control" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-semibold">Consultation fee</label>
                        <input type="number" step="0.01" name="consultation_fee" value="{{ old('consultation_fee') }}" class="form-control" min="0">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-brand">Create User</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<script>
function toggleDoctorFields(){
    document.getElementById('doctor-fields').style.display = document.getElementById('role').value === 'doctor' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleDoctorFields);
</script>
@endsection
