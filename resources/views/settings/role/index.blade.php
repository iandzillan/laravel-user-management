@extends('layouts.app')

@section('content')
    {{-- form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Roles</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Role</h6>
            <form action="{{ route('roles.store') }}" method="post" id="form-role">
                <div class="row d-flex justify-content-start">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
            </form>
        </div>
    </div>

    {{-- data table --}}
    <div class="card" id="table-role">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Roles List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-roles" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // data table
            let table;
            table = $('#data-roles').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $('#data-roles').wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('roles.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'actions', name: 'actions'},
                ]
            });

            // store role
            $('#form-role').on('submit', function(e){
                e.preventDefault();
                let textToast, typeJson, message, formData, url, btnValue, storeURL;
                btnValue = $('#store').val();
                if (btnValue === 'store') {
                    textToast = 'Please wait, storing the data';
                    typeJson  = 'post';
                    message   = ' has been stored';
                } else {
                    textToast = 'Please wait, updating the data';
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
    
                formData = $(this).serializeArray();
                url      = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: typeJson,
                    data: formData,
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
    
                        storeURL = "{{ route('roles.store') }}";
                        $('#form-user').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('.invalid-feedback').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-role").offset().top},'fast');
                        table.draw();
                    }
                });
            });

            // edit user
            $('body').on('click', '#btn-edit', function(){
                let id, editURL, updateURL;
                id      = $(this).data('id');
                editURL = "{{ route('roles.edit', ":id") }}";
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

                        updateURL = "{{ route('roles.update', ":id") }}";
                        updateURL = updateURL.replace(':id', id);
                        $('#form-role').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit role
            $('#cancel').on('click', function(){
                let storeURL;
                storeURL = "{{ route('roles.store') }}";
                $('#form-role').attr('action', storeURL).attr('method', 'post');
                $('#store').val('store');
                swal.fire({
                    toast: true, 
                    position: 'top-end',
                    icon: 'success',
                    text: 'Cancel Editing',
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
                url = "{{ route('roles.destroy', ":id") }}";
                url = url.replace(':id', id);
                
                swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'All related data will be deleted as well',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
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