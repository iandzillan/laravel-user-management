@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Employees</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Employee</h6>
            <form action="{{ route('employees.store') }}" method="post" id="form-employee">
                <div class="row d-flex justify-content-start">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" id="department" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-department"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
            </form>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="card" id="table-employee">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Employee List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-employees" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // define variable
            let table; 

            // data-employees
            table = $('#data-employees').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-employees").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('employees.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'dept', name: 'dept'},
                    {data: 'actions', name: 'actions'},
                ]
            });
        });
    </script>
@endsection