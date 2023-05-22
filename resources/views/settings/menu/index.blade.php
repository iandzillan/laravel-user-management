@extends('layouts.app')

@section('content')
    {{-- form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Menus</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Menu</h6>
            <form action="{{ route('menus.store') }}" method="post" id="form-menu">
                <div class="row d-flex justify-content-center">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-4 col-md-12">
                        <label for="code" class="form-label">Code Menu</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Example: T001">
                        <div class="invalid-feedback d-none" role="alert" id="alert-code"></div>
                    </div>
                    <div class="mb-3 col-lg-4 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                    <div class="mb-3 col-lg-4 col-md-12">
                        <label for="icon" class="form-label">Icon</label>
                        <div class="input-group">
                            <input type="text" name="icon" id="icon" class="form-control">
                            <a href="https://tabler-icons.io/" target="_blank" class="btn btn-warning">
                                <i class="ti ti-brand-codesandbox"></i>
                            </a>
                        </div>
                        <span class="text-small text-warning">*Click the box to see icon references</span>
                        <div class="invalid-feedback d-none" role="alert" id="alert-icon"></div>
                    </div>
                    <div class="mb-3 col-lg-12 col-md-12">
                        <label for="description" class="form-label">Menu Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        <div class="invalid-feedback d-none" role="alert" id="alert-description"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel" value="cancel">Cancel</button>
            </form>
        </div>
    </div>

    <div class="card" id="table-menu">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Menus List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-menu" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Menu</th>
                            <th>icon</th>
                            <th>Description</th>
                            <th>Sub Menu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let table;
            table = $('#data-menu').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-menu").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('menus.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'icon', name: 'icon'},
                    {data: 'description', name: 'description'},
                    {data: 'sub_menus_count', name: 'sub_menus_count'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // store menu
            $('#form-menu').on('submit', function(e){
                e.preventDefault();
                let textToast, typeJson, message, formData, url;
                let btnValue = $('#store').val();
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
                    timerProgressBar: true,
                    timer: 2000
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
                            text: response.data.name + message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        let storeURL = "{{ route('menus.store') }}";
                        $('#form-menu').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-menu").offset().top},'fast');
                        table.draw();
                    }, error: function(error){
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            text: 'Something wrong, please kindly check again',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $.each(error.responseJSON, function(i, error){
                            $('#alert-'+i).addClass('d-block').removeClass('d-none').html(error[0]);
                            $('input[name="'+i+'"]').addClass('is-invalid');
                        });
                    }
                });
            });

            // edit menu 
            $('body').on('click', '#btn-edit', function(){
                let id, editURL;
                id      = $(this).data('id');
                editURL = "{{ route('menus.edit', ":id") }}";
                editURL = editURL.replace(':id', id);
                $.ajax({
                    url: editURL,
                    type: 'get',
                    cache: false,
                    success: function(response){
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            text: "You're editing " + response.data.name,
                            showConfirmButton: false
                        });

                        let updateURL = "{{ route('menus.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-menu').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#code').val(response.data.code);
                        $('#name').val(response.data.name);
                        $('#icon').val(response.data.icon);
                        $('#description').val(response.data.description);
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit user
            $('#cancel').on('click', function(){
                let storeURL = "{{ route('menus.store') }}";
                $('#form-menu').attr('action', storeURL).attr('method', 'post');
                $('#store').val('store');
                swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: 'Cancel editing',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000
                });
                $(this).addClass('d-none');
                $('.invalid-feedback').removeClass('d-block');
                $('input').removeClass('is-invalid');
            });

            // delete user
            $('body').on('click', '#btn-delete', function(){
                let id, url;
                id  = $(this).data('id');
                url = "{{ route('menus.destroy', ':id') }}";
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
                                    text: response.data.name + ' has been deleted',
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 2000
                                });
                                table.draw();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection