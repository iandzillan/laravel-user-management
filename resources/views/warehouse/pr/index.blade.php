@extends('layouts.app')

@section('content')
    {{-- list pr --}}
    <div class="card" id="table-item">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Purchase Requisition List</h4>
            </div>
            <a href="{{ route('prs.create') }}" class="btn btn-primary" id="btn-create">
                <i class="ti ti-file-text"></i> Create
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="data-prs" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Number</th>
                            <th>Requester</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal form pr --}}
    <div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Purchase Requisition</h5>
                </div>
                <form action="#" method="post">
                    <div class="modal-body">
                        <div class="row d-flex justify-content-between">
                            <input type="hidden" name="id" id="id">
                            <div class="col-lg-6 col-md-12 mb-2">
                                <label for="pr_number" class="form-label">Purchase Requisition Number</label>
                                <input type="text" name="pr_number" id="pr_number" class="form-control" readonly>
                                <div class="invalid-feedback d-none" role="alert" id="alert-pr_number"></div>
                            </div>
                            <div class="col-lg-6 col-md-12 mb-2">
                                <label for="employee_id" class="form-label">Requester</label>
                                <select name="employee_id" id="employee_id" class="form-select"></select>
                                <div class="invalid-feedback d-none" role="alert" id="alert-employee_id"></div>
                            </div>
                            <hr class="my-2">
                        </div>

                        <h5 class="mb-2">Detail PR</h5>
                        <div class="row d-flex justify-content-end">
                            <div class="col-lg-6 col-md-12">
                                <div class="row mb-2">
                                    <label for="created_at" class="col-3 col-form-label">PR Date</label>
                                    <div class="col-9">
                                        <input type="date" name="created_at" id="created_at" class="form-control">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-created_at"></div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="due_date" class="col-3 col-form-label">Due Date</label>
                                    <div class="col-9">
                                        <input type="date" name="due_date" id="due_date" class="form-control">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-due_date"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table" id="pr-item">
                            <thead>
                                <tr>
                                    <th>Items</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="item_id" id="item_id" class="form-select"></select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty" id="qty" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" id="delete-pr-item" class="btn btn-danger btn-sm">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row d-flex justify-content-between">
                            <div class="col-9">
                                <button type="button" id="add-item" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> Add Item 
                                </button>
                            </div>
                            <div class="col-3 align-items-end">
                                Sub Qty : ...
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" id="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // data table
            let table = $('#data-prs').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-prs").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('prs.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'pr_number', name: 'pr_number'},
                    {data: 'requester', name: 'requester'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // btn-create
            // $('body').on('click', '#btn-create', function(){
            //     $('#modal-form').modal('show');
            // });

            // add-item
            $('body').on('click', '#add-item', function(){
                $('#pr-item > tbody:last-child').append(`
                    <tr>
                        <td>
                            <select name="item_id" id="item_id" class="form-select"></select>
                        </td>
                        <td>
                            <input type="number" name="qty" id="qty" class="form-control">
                        </td>
                        <td>
                            <button type="button" id="delete-pr-item" class="btn btn-danger btn-sm">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        });
    </script>
@endsection