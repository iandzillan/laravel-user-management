@extends('layouts.app')

@section('content')
    {{-- form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Modul</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Modul</h6>
            <form action="{{ route('moduls.store') }}" method="post" id="form-modul">
                <div class="row d-flex justify-content-start mb-3">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Example: T001">
                        <div class="invalid-feedback d-none" role="alert" id="alert-code"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                    <div class="mb-3 col-lg-12 col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        <div class="invalid-feedback d-none" role="alert" id="alert-description"></div>
                    </div>
                    <label for="menu-id" class="form-label">Menu</label>
                    <div class="invalid-feedback d-none" role="alert" id="alert-menu_id"></div>
                    <div class="row d-flex justify-content-start">
                        @forelse ($menus as $menu)
                            <div class="mb-3 col-lg-4 col-md-6">
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <div class="accordion-header d-flex align-items-center" style="column-gap: 1rem; padding-left: 1rem">
                                            <input type="checkbox" class="form-check-input" name="menu_id[]" id="menu-id" value="{{ $menu->id }}">
                                            <button class="accordion-button" style="background: none; padding-left: 0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-{{ $menu->code }}">
                                                {{ $menu->code }} - <i class="ti ti-{{ $menu->icon }}"></i> &nbsp; {{ $menu->name }}
                                            </button>
                                        </div>
                                        <div id="panelsStayOpen-{{ $menu->code }}" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <strong>Permissions:</strong>
                                                <ul>
                                                    @forelse ($menu->permissions as $permission)
                                                        <li>{{ $permission->name }}</li>
                                                    @empty
                                                        <p>No data...</p>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>No data...</p>
                        @endforelse
                    </div>
                    <div class="invalid-feedback d-none" role="alert" id="alert-menu_id"></div>
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel" value="cancel">Cancel</button>
            </form>
        </div>
    </div>

    <div class="card" id="table-modul">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Modul List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-modul" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Modul</th>
                            <th>Description</th>
                            <th>Menus</th>
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
            table = $('#data-modul').DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                initComplete: function (settings, json) {  
                    $("#data-modul").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('moduls.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description', orderable: false},
                    {data: 'menus', name: 'menus'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // store modul
            $('#form-modul').on('submit', function(e){
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
                            text: response.data.name + message,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000
                        });
                        let storeURL = "{{ route('moduls.store') }}";
                        $('#form-modul').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-modul").offset().top},'fast');
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
                        $('textarea').removeClass('is-invalid');
                        console.log(error.responseJSON);
                        $.each(error.responseJSON, function(i, error){
                            $('#alert-'+i).addClass('d-block').removeClass('d-none').html(error[0]);
                            $('input[name="'+i+'"]').addClass('is-invalid');
                            $('input:checkbox[name="'+i+'[]"]').addClass('is-invalid');
                            $('textarea[name="'+i+'"]').addClass('is-invalid');
                        });
                    }
                });
            });

            // edit modul 
            $('body').on('click', '#btn-edit', function(){
                let id, editURL;
                id      = $(this).data('id');
                editURL = "{{ route('moduls.show', ":id") }}";
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

                        let updateURL = "{{ route('moduls.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-modul').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.data.id);
                        $('#code').val(response.data.code);
                        $('#name').val(response.data.name);
                        $('#icon').val(response.data.icon);
                        $('#description').val(response.data.description);
                        $.each(response.menus, function(i, menus){
                            $('input:checkbox[value="'+menus+'"]').prop('checked', true);
                        });
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit user
            $('#cancel').on('click', function(){
                let storeURL = "{{ route('moduls.store') }}";
                $('#form-modul').attr('action', storeURL).attr('method', 'post');
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
                url = "{{ route('moduls.destroy', ':id') }}";
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
                            timerProgressBar: true
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