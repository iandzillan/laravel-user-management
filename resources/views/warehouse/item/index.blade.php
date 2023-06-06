@extends('layouts.app')

@section('content')
    {{-- data tables --}}
    <div class="card" id="table-item">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Item List</h4>
            </div>
            <a href="javascript:void(0)" class="btn btn-primary" id="btn-create"><i class="ti ti-file-plus"></i> Add Item</a>
        </div>
        <div class="card-body">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modal-item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                </div>
                <form action="{{ route('items.store') }}" id="form-item" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row d-flex justify-content-start">
                            <input type="hidden" name="id" id="id">
                            <div class="col-12 mb-2">
                                <label for="category-id" class="form-label">Category</label>
                                <select name="category_id" id="category-id" class="form-select"></select>
                                <div class="invalid-feedback d-none" role="alert" id="alert-category_id"></div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="code" class="form-label">Item Code</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="category-code">...</span>
                                    <input type="text" name="code" id="code" class="form-control">
                                </div>
                                <div class="invalid-feedback d-none" role="alert" id="alert-code"></div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                                <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" id="location" class="form-control">
                                <div class="invalid-feedback d-none" role="alert" id="alert-location"></div>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="uom-id" class="form-label">UOM</label>
                                <select name="uom_id" id="uom-id" class="form-select"></select>
                                <div class="invalid-feedback d-none" role="alert" id="alert-uom_id"></div>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="stock" class="form-label">stock</label>
                                <input type="number" name="stock" id="stock" class="form-control">
                                <div class="invalid-feedback d-none" role="alert" id="alert-stock"></div>
                            </div>
                            <div class="col-4 mb-2">
                                <label for="safety-stock" class="form-label">Safety Stock</label>
                                <input type="number" name="safety_stock" id="safety-stock" class="form-control">
                                <div class="invalid-feedback d-none" role="alert" id="alert-safety_stock"></div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="desc" class="form-label">Description</label>
                                <textarea name="desc" id="desc" class="form-control"></textarea>
                                <div class="invalid-feedback d-none" role="alert" id="alert-desc"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" id="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="store" value="store">Save</button>
                    </div>
                </form>
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
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // get category
            function categories(){
                $.get("{{ route('items.categories') }}", function(response){
                    $('#category-id').empty().append('<option selected disabled> -- Choose -- </option>');
                    $.each(response, function(i, category){
                        $('#category-id').append('<option value="'+category.id+'">'+category.name+'</option>');
                    });
                });
            }

            // category changed
            $('#category-id').change(function(){
                let url, id; 
                id  = $('#category-id').val();
                url = "{{ route('items.category', ":id") }}";
                url = url.replace(':id', id);
                $.get(url, function(response){
                    $('#category-code').text(response.code);
                });
            });

            // get uoms
            function uoms(){
                $.get("{{ route('items.uoms') }}", function(response){
                    $('#uom-id').empty().append('<option selected disabled> -- Choose -- </option>');
                    $.each(response, function(i, uom){
                        $('#uom-id').append('<option value="'+uom.id+'">'+uom.unit+'</option>');
                    });
                });
            }

            // show create modal
            $('#btn-create').on('click', function(){
                categories();
                uoms();
                let storeURL = "{{ route('items.store') }}";
                $('#form-item').attr('action', storeURL).attr('method', 'POST').trigger('reset');
                $('#category-code').text('...');
                $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                $('input').removeClass('is-invalid');
                $('select').removeClass('is-invalid');
                $('textarea').removeClass('is-invalid');
                $('#store').val('store');
                $('#modal-item').modal('show');
            });

            // store item
            $('body').on('submit', '#form-item', function(e){
                e.preventDefault();
                let textToast, typeJson, message, formData, url, btnValue;
                btnValue = $('#store').val();
                if (btnValue === 'store') {
                    textToast = 'Please wait, storing the data...';
                    typeJson  = 'post';
                    message   = ' has been stored';
                } else {
                    textToast = 'Please wait, updating the data...';
                    typeJson  = 'patch';
                    message   = ' has been updated';
                }

                swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    text: textToast,
                    showConfirmButton: false,
                    timerProgressBar: true
                });

                formData  = $(this).serializeArray();
                url       = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: typeJson,
                    data: formData, 
                    dataType: 'json',
                    cache: false,
                    success: function(response){
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            text: 'Item ' +response.data.name + message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('select').removeClass('is-invalid');
                        $('textarea').removeClass('is-invalid');
                        $('#modal-item').modal('hide');
                        table.draw();
                    }, error: function(error){
                        console.log(error.responseJSON.message);
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            text: 'Something wrong, please kindly to check again',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('select').removeClass('is-invalid');
                        $('textarea').removeClass('is-invalid');
                        $.each(error.responseJSON, function(i, error){
                            console.log(i);
                            $('#alert-'+i).addClass('d-block').removeClass('d-none').html(error[0]);
                            $('input[name="'+i+'"]').addClass('is-invalid');
                            $('select[name="'+i+'"]').addClass('is-invalid');
                            $('textarea[name="'+i+'"]').addClass('is-invalid');
                        });
                    }
                });
            });

            // btn edit
            $('body').on('click', '#btn-edit', function(){
                categories();
                uoms();
                let id, editURL, updateURL;
                id      = $(this).data('id');
                editURL = "{{ route('items.show', ":id") }}";
                editURL = editURL.replace(':id', id);
                $.ajax({
                    url: editURL,
                    type: 'get',
                    cache: false,
                    success: function(response){
                        let code          = response.data.code;
                        let category_code = response.category.code;
                        code = code.replace(category_code, '');
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            text: "You're editing " + response.data.name + " item",
                            showConfirmButton: false
                        });
                        updateURL = "{{ route('items.update', ":id") }}";
                        updateURL = updateURL.replace(':id', id);
                        $('#form-item').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#category-id').val(response.data.category_id).prop('selected', true).change();
                        $('#code').val(code);
                        $('#name').val(response.data.name);
                        $('#location').val(response.data.location);
                        $('#uom-id').val(response.data.uom_id).prop('selected', true).change();
                        $('#stock').val(response.data.stock);
                        $('#safety-stock').val(response.data.safety_stock);
                        $('#desc').val(response.data.desc);
                        $('#store').val('edit');
                        $('#modal-item').modal('show');
                    }
                });
            });

            // cancel 
            $('body').on('click', '#cancel', function(){
                let btnValue;
                btnValue = $('#store').val();
                if (btnValue === 'store') {
                    swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: 'Cancel storing',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000
                });
                } else {
                    swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: 'Cancel editing',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000
                });
                }
            });

            // delete
            $('body').on('click', '#btn-delete', function(){
                let id, url;
                id  = $(this).data('id');
                url = "{{ route('items.destroy', ':id') }}";
                url = url.replace(':id', id);

                swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'All related data will be deleted as well',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            text: 'Please wait, deleting the data...',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000
                        });

                        $.ajax({
                            url: url,
                            type: 'delete',
                            cache: false,
                            success: function(response){
                                swal.fire({
                                    toast: true, 
                                    position: 'top-end',
                                    icon: 'success',
                                    text: 'Item ' + response.data.name + ' has been deleted',
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 2000
                                });
                                table.draw();
                            },
                            error: function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection