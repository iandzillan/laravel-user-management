@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Sub Menus</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Sub Menu</h6>
            <form action="{{ route('submenus.store') }}" method="post" id="form-submenu">
                <div class="row d-flex justify-content-center">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="menu" class="form-label">Menu</label>
                        <select name="menu_id" id="menu-id" class="form-select"></select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-menu"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="code" class="form-label">Code</label>
                        <div class="input-group">
                            <span class="input-group-text" id="menu-code">...</span>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Example: T01">
                        </div>
                        <div class="invalid-feedback d-none" role="alert" id="alert-code"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
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
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
            </form>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="card" id="table-submenu">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Sub Menus List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-submenu" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Sub Menu</th>
                            <th>Menu</th>
                            <th>Icon</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        $(document).ready(function(){
            let table;
            // data table
            table = $('#data-submenu').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-submenu").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('submenus.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'menu', name: 'menu'},
                    {data: 'icon', name: 'icon'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // form-select menu
            $.get("{{ route('submenus.menus') }}", function(response){
                $('#menu-id').append('<option disabled selected>-- Choose --</option>');
                $.each(response, function(i, menu){
                    $('#menu-id').append('<option value="'+menu.id+'">'+menu.code+' - '+menu.name+'</option>');
                });
            });

            // menu code
            $('body').on('change', '#menu-id', function(){
                let id, url;
                id  = $('#menu-id').val();
                url = "{{ route('submenus.menu', ":id") }}";
                url = url.replace(':id', id);

                $.get(url, function(response){
                    if (id !== null) {
                        $('#menu-code').html(response.code+'-');
                    }
                    
                    if (id === null) {
                        $('#menu-code').html('...');
                    }
                });
            });

            // store-submenu
            $('#form-submenu').on('submit', function(e){
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
                        let storeURL = "{{ route('submenus.store') }}";
                        $('#form-submenu').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('#menu-id option:first').prop('selected', true).change();
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-submenu").offset().top},'fast');
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

            // edit submenu
            $('body').on('click', '#btn-edit', function(){
                let id        = $(this).data('id');
                let editURL   = "{{ route('submenus.edit', ":id") }}";
                editURL       = editURL.replace(':id', id);
                $.ajax({
                    url: editURL,
                    type: 'get',
                    cache: false,
                    success: function(response){
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            text: "You're editing " + response.data.name + " sub menu",
                            showConfirmButton: false
                        });

                        let updateURL = "{{ route('submenus.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-submenu').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#code').val(response.data.code);
                        $('#name').val(response.data.name);
                        $('#icon').val(response.data.icon);
                        $('#menu-id option[value="'+response.data.menu_id+'"]').attr('selected', 'selected').change();
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit submenu
            $('#cancel').on('click', function(){
                let storeURL = "{{ route('submenus.store') }}";
                $('#form-submenu').attr('action', storeURL).attr('method', 'post');
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
                $('#menu-id option:first').prop('selected', true).change();
            });

            // delete user
            $('body').on('click', '#btn-delete', function(){
                let id, url;
                id  = $(this).data('id');
                url = "{{ route('submenus.destroy', ':id') }}";
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
                            }, error: function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection