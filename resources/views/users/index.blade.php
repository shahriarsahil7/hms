@extends('layouts.app')
@section('page-title', 'User Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All roles</option>
            @foreach(['admin','doctor','receptionist','patient'] as $r)
                <option value="{{ $r }}" @selected(request('role')===$r)>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name/email">
        <button class="btn btn-sm btn-outline-brand">Filter</button>
    </form>
    <a href="{{ route('users.create') }}" class="btn btn-brand btn-sm">+ New User</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge badge-soft-info">{{ ucfirst($u->role) }}</span></td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td>
                        @if($u->is_active)
                            <span class="badge badge-soft-success">Active</span>
                        @else
                            <span class="badge badge-soft-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                        <form action="{{ route('users.toggle-status', $u) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">No users found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
