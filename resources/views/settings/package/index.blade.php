@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Menu Packages</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Menu Package</h6>
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
                    <label for="description" class="form-label">Package Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    <div class="invalid-feedback d-none" role="alert" id="alert-description"></div>
                </div>
                <label for="#" class="form-label">Menus</label>
                <div class="invalid-feedback d-none" role="alert" id="alert-sub_menu_id"></div>
                <div class="row d-flex justify-content-start">
                    @forelse ($menus as $menu)
                        <div class="mb-3 col-lg-3 col-md-6">
                            <div class="accordion" id="accordionPanels{{ $menu->id }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-{{ $menu->id }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{ $menu->id }}" aria-expanded="true" aria-controls="panelsStayOpen-collapse{{ $menu->id }}">
                                            <i class="ti ti-{{ $menu->icon }} mx-1"></i>
                                            {{ $menu->name }}
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse{{ $menu->id }}" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-{{ $menu->id }}">
                                        <div class="accordion-body">
                                            <div class="list-group">
                                                @forelse ($menu->subMenus as $submenu)
                                                    <label class="list-group-item">
                                                        <input class="form-check-input me-1" type="checkbox" value="{{ $submenu->id }}" name="sub_menu_id[]" id="sub-menu-id">
                                                        {{ $submenu->name }}
                                                    </label>
                                                @empty
                                                    <p class="text-center">No data...</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No data...</p>
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
                            <th>Menu</th>
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
            table = $('#data-package').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $("#data-package").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('packages.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'menus', name: 'menus'},
                    {data: 'actions', name: 'actions'},
                ]
            });

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
                            $('textarea[name="'+i+'"]').addClass('is-invalid');
                        });
                    }
                });
            });

            $('body').on('click', '#btn-edit', function(){
                let id, editURL, updateURL, checkbox;
                id = $(this).data('id');
                editURL = "{{ route('packages.edit', ":id") }}";
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
                        $('#form-package').attr('action', updateURL).attr('method', 'patch');
                        $('#id').val(response.package.id);
                        $('#code').val(response.package.code);
                        $('#name').val(response.package.name);
                        $('#description').val(response.package.description);
                        $.each(response.submenus, function(i, submenu){
                            $('#sub-menu-id').val(submenu.id).attr('checked', 'checked');
                        });
                        $('#cancel').removeClass('d-none');
                        $('#store').val('edit');
                        $('html,body').animate({scrollTop: $("#form").offset().top},'fast');
                    }
                });
            });
        });
    </script>
@endsection