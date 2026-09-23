@extends('layouts.app')
@section('page-title', 'Edit Patient')
@section('content')
<div class="card" style="max-width:800px">
    <div class="card-body">
        <form method="POST" action="{{ route('patients.update', $patient) }}">
            @csrf @method('PUT')
            @include('patients._form')
            <button type="submit" class="btn btn-brand">Save Changes</button>
            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
