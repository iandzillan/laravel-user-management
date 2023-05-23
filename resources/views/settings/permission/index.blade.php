@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">User Permission</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form User Permission</h6>
            <form action="#" method="post" id="form-permission">
                <div class="row d-flex justify-content-start">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="user" class="form-label">User</label>
                        <select name="user_id" id="user-id" class="form-select"></select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-user_id"></div>
                    </div>
                </div>
                <label for="#" class="form-label">Packages</label>
                <div class="invalid-feedback d-none" role="alert" id="alert-packages_id"></div>
                <div class="row d-flex justify-content-start">
                    @forelse ($packages as $package)
                    <div class="mb-3 col-lg-3 col-md-6">
                        <div class="accordion">
                            <div class="accordion-item">
                                <div class="accordion-header d-flex align-items-center" style="column-gap: 1rem; padding-left: 1rem" id="open-{{ $package->id }}">
                                    <input type="checkbox" class="form-check-input" id="package-id" value="{{ $package->id }}">
                                    <a type="button" class="accordion-button" style="background: none; padding-left: 0" data-bs-toggle="collapse" data-bs-target="#open-collapse-{{ $package->id }}">{{ $package->code }} - {{ $package->name }}</a>
                                </div>
                                <div id="open-collapse-{{ $package->id }}" class="accordion-collapse collapse show" aria-labelledby="open-{{ $package->id }}">
                                    <div class="accordion-body">
                                        $@foreach ($package->subMenus->groupBy('menu_id') as $submenu)
                                            <p>{{ $submenu }}</p>
                                        @endforeach
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
    <div class="card" id="table-permission">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">User Permission List</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="data-permission" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Package</th>
                            <th>Access</th>
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
            table = $('#data-permission').DataTable({
                
            });

            // form select users
            $.get("{{ route('permissions.getusers') }}", function(response){
                $('#user-id').append('<option disabled selected>-- Choose --</option>');
                $.each(response, function(i, user){
                    $('#user-id').append('<option value="'+user.id+'">'+user.name+'</option>');
                });
            });

            // store permission
            $('#form-permission').on('submit', function(e){
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
                        let storeURL = "{{ route('permissions.store') }}";
                        $('#form-permission').trigger('reset').attr('action', storeURL).attr('method', 'post');
                        $('#user-id option:first').prop('selected', true).change();
                        $('#package-id option:first').prop('selected', true).change();
                        $('.invalid-feedback').removeClass('d-block').addClass('d-none');
                        $('input').removeClass('is-invalid');
                        $('#store').val('store');
                        $('#cancel').addClass('d-none');
                        $('html,body').animate({scrollTop: $("#table-permission").offset().top},'fast');
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
        });
    </script>
@endsection