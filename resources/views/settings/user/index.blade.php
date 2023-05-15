@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Users</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Create User</h6>
            <form action="{{ route('users.store') }}" method="post">
                @method('post')
                @csrf
                <div class="row d-flex justify-content-center">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-12 col-md-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="password" class="form-label">Password Confirmation</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="card">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Users List</h6>
            <table class="table table-bordered" id="data-users" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Script --}}
    <script>
        $(document).ready(function(){
            let table = $('#data-users').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                initComplete: function (settings, json) {  
                    $("#data-users").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'actions', name: 'actions'},
                ]
            });
        });
    </script>
@endsection