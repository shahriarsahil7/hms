@extends('layouts.app')
@section('page-title', 'Doctors')
@section('content')
<form class="d-flex gap-2 mb-3" method="GET">
    <select name="department_id" class="form-select form-select-sm" style="max-width:220px" onchange="this.form.submit()">
        <option value="">All departments</option>
        @foreach($departments as $d)
            <option value="{{ $d->id }}" @selected(request('department_id')==$d->id)>{{ $d->name }}</option>
        @endforeach
    </select>
    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or specialization" style="max-width:280px">
    <button class="btn btn-sm btn-outline-brand">Search</button>
</form>

<div class="row g-3">
    @forelse($doctors as $doc)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-1">Dr. {{ $doc->user->name }}</h6>
                    <div class="text-secondary small mb-2">{{ $doc->specialization }}</div>
                    <div class="small mb-1"><i class="bi bi-building"></i> {{ $doc->department?->name ?? 'Unassigned' }}</div>
                    <div class="small mb-1"><i class="bi bi-mortarboard"></i> {{ $doc->qualification ?? '—' }}</div>
                    <div class="small mb-3"><i class="bi bi-briefcase"></i> {{ $doc->experience_years }} yrs experience</div>
                    <a href="{{ route('doctors.show', $doc) }}" class="btn btn-sm btn-outline-brand">View Profile</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-secondary py-5">No doctors found.</div>
    @endforelse
</div>
<div class="mt-3">{{ $doctors->links() }}</div>
@endsection
