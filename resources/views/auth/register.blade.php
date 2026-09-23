@extends('layouts.guest')
@section('title', 'Register')
@section('content')
    <h1 class="h4 fw-bold mb-1">Create a patient account</h1>
    <p class="text-secondary small mb-4">Staff accounts are created by the administrator.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Full name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label small fw-semibold">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label small fw-semibold">Date of birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Prefer not to say</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Confirm password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-brand w-100">Create account</button>
    </form>

    <p class="text-center text-secondary small mt-4 mb-0">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </p>
@endsection
