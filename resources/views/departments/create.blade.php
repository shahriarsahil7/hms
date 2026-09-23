@extends('layouts.app')
@section('page-title', 'New Department')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" class="form-control" placeholder="e.g. Building A, 2nd Floor">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-brand">Create Department</button>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
