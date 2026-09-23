@extends('layouts.app')
@section('page-title', 'Patients')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name, phone, email">
        <button class="btn btn-sm btn-outline-brand">Search</button>
    </form>
    <a href="{{ route('patients.create') }}" class="btn btn-brand btn-sm">+ Register Patient</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Name</th><th>Gender</th><th>Phone</th><th>Blood Group</th><th>Registered</th><th></th></tr></thead>
            <tbody>
            @forelse($patients as $p)
                <tr>
                    <td><a href="{{ route('patients.show', $p) }}">{{ $p->name }}</a></td>
                    <td>{{ $p->gender ? ucfirst($p->gender) : '—' }}</td>
                    <td>{{ $p->phone ?? '—' }}</td>
                    <td>{{ $p->blood_group ?? '—' }}</td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('patients.show', $p) }}" class="btn btn-sm btn-outline-brand">View</a>
                        <a href="{{ route('patients.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">No patients registered yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $patients->links() }}</div>
@endsection
