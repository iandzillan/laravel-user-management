@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Users</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form User</h6>
            <form action="{{ route('users.store') }}" method="post" id="form-user">
                <div class="row d-flex justify-content-start">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
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
                    <div class="mb-3 col-lg-12 col-md-12">
                        <label class="form-label">Package</label>
                        <div class="invalid-feedback d-none" role="alert" id="alert-package_id"></div>
                        <div class="row d-flex justify-content-start">
                            @forelse ($packages as $package)
                                <div class="mb-3 col-lg-4 col-md-6">
                                    <div class="accordion">
                                        <div class="accordion-item">
                                            <div class="accordion-header d-flex align-items-center" style="column-gap: 1rem; padding-left: 1rem">
                                                <input type="checkbox" class="form-check-input" name="package_id[]" id="package-id" value="{{ $package->id }}">
                                                <button class="accordion-button" style="background: none; padding-left: 0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-{{ $package->code }}">
                                                {{ $package->code }} - {{ $package->name }}
                                            </button>
                                            </div>
                                            <div id="panelsStayOpen-{{ $package->code }}" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <strong>Modul:</strong>
                                                    <ul>
                                                        @forelse ($package->moduls->sortBy('code') as $modul)
                                                            <li>{{ $modul->code }} - {{ $modul->name }}</li>
                                                            <ul>
                                                                @forelse ($modul->menus->sortBy('code') as $menu)
                                                                    <li class="mx-3">{{ $menu->code }} - <i class="ti ti-{{ $menu->icon }}"></i> {{ $menu->name }}</li>
                                                                @empty
                                                                    <p class="text-center">No data...</p>
                                                                @endforelse
                                                            </ul>
                                                        @empty
                                                            <p class="text-center">No data...</p>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No data...</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
            </form>
        </div>
    </div>

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
                            <th>Package</th>
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
                    {data: 'packages', name: 'packages'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // store user
            $('#form-user').on('submit', function(e){
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
                        let storeURL = "{{ route('users.store') }}";
                        $('#form-user').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-user").offset().top},'fast');
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

            // edit user
            $('body').on('click', '#btn-edit', function(){
                let id        = $(this).data('id');
                let editURL   = "{{ route('users.edit', ":id") }}";
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
                            text: "You're editing " + response.data.name + " user data",
                            showConfirmButton: false
                        });

                        let updateURL = "{{ route('users.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-user').attr('action', updateURL);
                        $('#form-user').attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#username').val(response.data.username);
                        $('#email').val(response.data.email);
                        $.each(response.packages, function(i, package){
                            $('input:checkbox[value="'+package+'"]').prop('checked', true);
                        });
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit user
            $('#cancel').on('click', function(){
                let storeURL = "{{ route('users.store') }}";
                $('#form-user').attr('action', storeURL).attr('method', 'post');
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