@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Packages</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Package</h6>
            <form action="{{ route('packages.store') }}" method="post" id="form-package">
                @method('post')
                @csrf
                <div class="row d-flex justify-content-start">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Example: T1">
                        <div class="invalid-feedback d-none" role="alert" id="alert-code"></div>
                    </div>
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                    </div>
                </div>
                <div class="mb-3 col-lg-12 col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    <div class="invalid-feedback d-none" role="alert" id="alert-description"></div>
                </div>
                <label for="#" class="form-label">Modul</label>
                <div class="invalid-feedback d-none" role="alert" id="alert-modul_id"></div>
                <div class="row d-flex justify-content-start">
                    @forelse ($moduls as $modul)
                        <div class="mb-3 col-lg-4 col-md-6">
                            <div class="accordion">
                                <div class="accordion-item">
                                    <div class="accordion-header d-flex align-items-center" style="column-gap: 1rem; padding-left: 1rem">
                                        <input type="checkbox" class="form-check-input" name="modul_id[]" id="modul-id" value="{{ $modul->id }}">
                                        <button class="accordion-button" style="background: none; padding-left: 0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-{{ $modul->code }}">
                                            {{ $modul->code }} - {{ $modul->name }}
                                        </button>
                                    </div>
                                    <div id="panelsStayOpen-{{ $modul->code }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <strong>Menus:</strong>
                                            <ul>
                                                @forelse ($modul->menus->sortBy('code') as $menu)
                                                    <li>{{ $menu->code }} - <i class="ti ti-{{ $menu->icon }}"></i> {{ $menu->name }}</li>
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
                        <p class="text-center">No Data...</p>
                    @endforelse
                </div>
                <button type="submit" class="btn btn-primary" id="store" value="store">Submit</button>
                <button type="reset" class="btn btn-danger d-none" id="cancel">Cancel</button>
            </form>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="card" id="table-package">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Packages List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-package" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Package</th>
                            <th>Description</th>
                            <th>Modul</th>
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
                                <td>Code</td>
                                <td>:</td>
                                <td id="package-code"></td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>:</td>
                                <td id="package-name"></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>:</td>
                                <td id="package-desc"></td>
                            </tr>
                            <tr>
                                <td>Detail Package</td>
                                <td>:</td>
                                <td>
                                    <div class="detail-package"></div>
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

    <script>
        $(document).ready(function(){
            let table;
            // table package
            table = $('#data-package').DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                initComplete: function (settings, json) {         
                    $("#data-package").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");
                },
                ajax: "{{ route('packages.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description', orderable: false},
                    {data: 'moduls', name: 'moduls', orderable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });

            // store package
            $('#form-package').on('submit', function(e){
                e.preventDefault();
                let textToast, typeJson, message, formData, url, storeURL, btnValue;
                btnValue = $('#store').val();
                if (btnValue === 'store') {
                    textToast = 'Please wait, storing the package...';
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
                            timer: 2000
                        });
                        storeURL = "{{ route('packages.store') }}";
                        $('#form-package').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('textarea').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-package").offset().top},'fast');
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
                        $('textarea').removeClass('is-invalid');
                        $.each(error.responseJSON, function(i, error){
                            $('#alert-'+i).addClass('d-block').removeClass('d-none').html(error[0]);
                            $('input[name="'+i+'"]').addClass('is-invalid');
                            $('input:checkbox[name="'+i+'[]"]').addClass('is-invalid');
                            $('textarea[name="'+i+'"]').addClass('is-invalid');
                        });
                    }
                });
            });

            // edit package
            $('body').on('click', '#btn-edit', function(){
                let id, editURL, updateURL, checkbox;
                id = $(this).data('id');
                editURL = "{{ route('packages.show', ":id") }}";
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
                            text: "You're editing " + response.package.name + " package",
                            showConfirmButton: false
                        });

                        updateURL = "{{ route('packages.update', ":id") }}";
                        updateURL     = updateURL.replace(':id', id);
                        $('#form-package').attr('action', updateURL).attr('method', 'patch').trigger('reset');
                        $('#id').val(response.package.id);
                        $('#code').val(response.package.code);
                        $('#name').val(response.package.name);
                        $('#description').val(response.package.description);
                        $.each(response.moduls, function(i, moduls){
                            $('input:checkbox[value="'+moduls+'"]').prop('checked', true);
                        });
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });

            // cancel edit package
            $('#cancel').on('click', function(){
                let storeURL;
                storeURL = "{{ route('packages.store') }}";
                $('#form-package').attr('action', storeURL).attr('method', 'post');
                $('#store').val('store');
                swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: 'Cancel editing',
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
                $(this).addClass('d-none');
                $('.invalid-feedback').removeClass('d-block');
                $('input').removeClass('is-invalid');
            });

            // delete package 
            $('body').on('click', '#btn-delete', function(){
                let id, url;
                id  = $(this).data('id');
                url = "{{ route('packages.destroy', ':id') }}";
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
                                    timerProgressBar: true
                                });
                                table.draw();
                            }, error: function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });
            });

            // info button
            $('body').on('click', '#btn-info', function(){
                let id, url, data; 
                id  = $(this).data('id');
                url = "{{ route('packages.show', ":id") }}";
                url = url.replace(':id', id);
                $.get(url, function(response){
                    console.log(response);
                    $('#package-code').html(response.package.code);
                    $('#package-name').html(response.package.name);
                    $('#package-desc').html(response.package.description);
                    $('.detail-package').attr('id', 'tree');
                    $('#tree').html(response.info).jstree();
                    $('#modal-info').modal('show');
                });
            });
        });
    </script>
@endsection