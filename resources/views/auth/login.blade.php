@extends('layouts.guest')
@section('title', 'Login')
@section('content')
    <h1 class="h4 fw-bold mb-1">Welcome back</h1>
    <p class="text-secondary small mb-4">Sign in to access your dashboard.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        <button type="submit" class="btn btn-brand w-100">Sign in</button>
    </form>

    <p class="text-center text-secondary small mt-4 mb-0">
        New patient? <a href="{{ route('register') }}">Create an account</a>
    </p>

    <div class="mt-4 pt-3 border-top">
        <!-- <p class="small text-secondary mb-1 fw-semibold">Demo accounts (after seeding):</p>
        <ul class="small text-secondary mb-0 ps-3">
            <li>Admin — admin@hms.test / password</li>
            <li>Doctor — doctor@hms.test / password</li>
            <li>Receptionist — reception@hms.test / password</li>
            <li>Patient — patient@hms.test / password</li>
        </ul> -->
    </div>
@endsection
