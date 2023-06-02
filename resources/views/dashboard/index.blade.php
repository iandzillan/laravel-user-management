@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Dashboard</h5>
            <p class="mb-0">{{ Auth::user()->employee->name }}</p>
        </div>
    </div>
@endsection