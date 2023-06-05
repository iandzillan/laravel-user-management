@extends('layouts.app')

@section('content')
    {{-- data tables --}}
    <div class="card" id="table-item">
        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col-11">
                    <h6 class="fw-semibold mb-3">Item List</h6>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="btn btn-primary">
                        Add
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-items" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Safety Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
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
            table = $('#data-items').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-items").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('items.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'stock', name: 'stock'},
                    {data: 'safety_stock', name: 'safety_stock'},
                    {data: 'uom', name: 'uom'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: "13%"},
                ]
            });
        });
    </script>
@endsection