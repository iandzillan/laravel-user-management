@extends('layouts.app')

@section('content')
    @can('create', App\Models\User::class)    
        {{-- Form --}}
        <div class="card" id="form">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Users</h5>
                <hr>
                <h6 class="fw-semibold mb-3">Form User</h6>
                <form action="{{ route('users.store') }}" method="post" id="form-user">
                    <div class="row d-flex justify-content-start">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3 col-lg-6 col-md-12" id="employee">
                            <label for="employee-id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee-id" class="form-select"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-employee_id"></div>
                        </div>
                        <div class="mb-3 col-lg-6 col-md-12">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control">
                            <div class="invalid-feedback d-none" role="alert" id="alert-username"></div>
                        </div>
                        <div class="mb-3 col-lg-12 col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <div class="invalid-feedback d-none" role="alert" id="alert-email"></div>
                        </div>
                        <div class="mb-3 col-lg-6 col-md-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password"></div>
                        </div>
                        <div class="mb-3 col-lg-6 col-md-12">
                            <label for="password" class="form-label">Password Confirmation</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password_confirmation"></div>
                        </div>

                        @can('setModul', App\Models\User::class)    
                            <label for="modul-id" class="form-label">Modul</label>
                            <div class="invalid-feedback d-none" role="alert" id="alert-modul_id"></div>
                            <div class="row d-flex justify-content-start">
                                @forelse ($modules as $modul)
                                    <div class="mb-3 col-lg-4 col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="modul_id[]" id="modul-{{ $modul->code }}" value="{{ $modul->id }}">
                                            <div class="accordion" id="accordion-menu">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $modul->code }}">
                                                            {{ $modul->code }} -&nbsp;{{ $modul->name }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-{{ $modul->code }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <strong>Description: </strong>
                                                            <p>{{ $modul->description }}</p>
                                                            <strong>Menu:</strong>
                                                            <ol>
                                                                @forelse ($modul->menus->sortBy('sequence') as $menu)
                                                                    <li>{{ $menu->code }} - <i class="ti ti-{{ $menu->icon }}"></i>&nbsp;{{ $menu->name }}</li>
                                                                @empty
                                                                    <p>No data...</p>
                                                                @endforelse
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>No data...</p>
                                @endforelse
                            </div>
                        @endcan

                    </div>
                    <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                    <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
                </form>
            </div>
        </div>
    @endcan

    {{-- Data Tables --}}
    <div class="card" id="table-user">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Users List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-users" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Modules</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal-info" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="table-info">
                            <tr>
                                <td>User</td>
                                <td>:</td>
                                <td id="user-name"></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td id="user-email"></td>
                            </tr>
                            <tr>
                                <td>Detail Access</td>
                                <td>:</td>
                                <td id="package-tree">
                                    <div id="detail-package"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modal-close">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        $(document).ready(function(){
            // get employee
            function employee(){
                $.get("{{ route('users.getEmployees') }}", function(response){
                    $('#employee-id').empty().prop('disabled', false);
                    $('#employee-id').append('<option selected disabled> -- Choose -- </option>');
                    $.each(response, function(i, employee){
                        $('#employee-id').append('<option value="'+employee.id+'">'+employee.name+'</option>');
                    });
                });
            }

            // call employee function
            employee();

            // data table
            let table = $('#data-users').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-users").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'modules', name: 'modules'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: "13%"},
                ]
            });

            // store user
            $('#form-user').on('submit', function(e){
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
                    $('#employee-id').prop('disabled', false);
                    $('#username').prop('disabled', false);
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
                            text: 'User account ' +response.employee.name + message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        let storeURL = "{{ route('users.store') }}";
                        $('#form-user').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        employee();
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-user").offset().top},'fast');
                        table.draw();
                    }, error: function(error){
                        console.log(error.responseJSON.message);
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
                            console.log(i);
                            $('#alert-'+i).addClass('d-block').removeClass('d-none').html(error[0]);
                            $('input[name="'+i+'"]').addClass('is-invalid');
                            $('select[name="'+i+'"]').addClass('is-invalid');
                            $('input:checkbox[name="'+i+'[]"]').addClass('is-invalid');
                        });
                    }
                });
            });

            // edit user
            $('body').on('click', '#btn-edit', function(){
                let id        = $(this).data('id');
                let editURL   = "{{ route('users.show', ":id") }}";
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
                            text: "You're editing " + response.employee.name + " user account",
                            showConfirmButton: false
                        });

                        let updateURL = "{{ route('users.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-user').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#username').val(response.data.username);
                        $('#employee-id').append('<option value="'+response.employee.id+'" selected>'+response.employee.name+'</option>').prop('disabled', true);
                        $('#email').val(response.data.email);
                        $.each(response.modules, function(i, modul){
                            $('input:checkbox[value="'+modul+'"]').prop('checked', true);
                        });
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }, error: function(error){
                        console.log(error.responseJSON.message);
                    }
                });
            });

            // cancel edit user
            $('#cancel').on('click', function(){
                let storeURL = "{{ route('users.store') }}";
                $('#form-user').attr('action', storeURL).attr('method', 'post');
                $('#store').val('store');
                employee();
                $('#username').prop('disabled', false);
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
                url = "{{ route('users.destroy', ':id') }}";
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
                                    text: 'User account ' + response.employee.name + ' has been deleted',
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 2000
                                });
                                employee();
                                table.draw();
                            }
                        });
                    }
                });
            });

            // info button
            $('body').on('click', '#btn-info', function(){
                let id, url; 
                id  = $(this).data('id');
                url = "{{ route('users.show', ":id") }}";
                url = url.replace(':id', id);
                $.get(url, function(response){
                    $('#user-name').html(response.employee.name);
                    $('#user-email').html(response.data.email);
                    $('#detail-package').jstree('destroy').append(response.info).jstree();
                    $('#modal-info').modal('show');
                });
            });
        });
    </script>
@endsection