<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
@php
    $user = auth()->user();
    $isActive = fn (string $pattern) => request()->routeIs($pattern) ? 'active' : '';
@endphp
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            {{ config('app.name') }}
            <small>Hospital Management</small>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="{{ $isActive('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>

            @if($user->isAdmin())
                <div class="nav-section">Administration</div>
                <a href="{{ route('users.index') }}" class="{{ $isActive('users.*') }}"><i class="bi bi-people"></i> User Management</a>
                <a href="{{ route('departments.index') }}" class="{{ $isActive('departments.*') }}"><i class="bi bi-building"></i> Departments</a>
            @endif

            @if($user->isAdmin() || $user->isReceptionist())
                <div class="nav-section">Front Desk</div>
                <a href="{{ route('patients.index') }}" class="{{ $isActive('patients.*') }}"><i class="bi bi-person-vcard"></i> Patients</a>
            @endif

            <div class="nav-section">Clinical</div>
            <a href="{{ route('doctors.index') }}" class="{{ $isActive('doctors.*') }}"><i class="bi bi-heart-pulse"></i> Doctors</a>
            <a href="{{ route('appointments.index') }}" class="{{ $isActive('appointments.*') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
            @if($user->isAdmin() || $user->isDoctor())
                <a href="{{ route('schedules.index') }}" class="{{ $isActive('schedules.*') }}"><i class="bi bi-calendar-week"></i> Doctor Schedules</a>
            @endif
            <a href="{{ route('medical-records.index') }}" class="{{ $isActive('medical-records.*') }}"><i class="bi bi-file-earmark-medical"></i> Medical Records</a>

            <div class="nav-section">Billing</div>
            <a href="{{ route('invoices.index') }}" class="{{ $isActive('invoices.*') }}"><i class="bi bi-receipt"></i> Invoices & Payments</a>
        </nav>
    </aside>

    <div class="main">
        <div class="topbar">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h5 mb-0 d-none d-md-block">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="role-pill">{{ $user->role }}</span>
                <span class="d-none d-sm-inline text-secondary small">{{ $user->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-brand" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
