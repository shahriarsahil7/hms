@extends('layouts.app')
@section('page-title', 'Edit Department')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('departments.update', $department) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label small fw-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Location</label>
                <input type="text" name="location" value="{{ old('location', $department->location) }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($department->is_active)>
                <label class="form-check-label small" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
