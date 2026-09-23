@extends('layouts.app')
@section('page-title', 'Departments')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('departments.create') }}" class="btn btn-brand btn-sm">+ New Department</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Name</th><th>Location</th><th>Doctors</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($departments as $d)
                <tr>
                    <td>{{ $d->name }}</td>
                    <td>{{ $d->location ?? '—' }}</td>
                    <td>{{ $d->doctors_count }}</td>
                    <td>
                        @if($d->is_active)
                            <span class="badge badge-soft-success">Active</span>
                        @else
                            <span class="badge badge-soft-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('departments.edit', $d) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                        <form action="{{ route('departments.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">No departments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $departments->links() }}</div>
@endsection
