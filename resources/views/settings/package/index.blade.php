@extends('layouts.app')

@section('content')
    {{-- Form --}}
    <div class="card" id="form">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Menu Packages</h5>
            <hr>
            <h6 class="fw-semibold mb-3">Form Menu Package</h6>
            <form action="#" method="post" id="form-package">
                <div class="row d-flex justify-content-start">
                    <div class="mb-3 col-lg-6 col-md-12">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" name="code" id="code" class="form-control">
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
                                                        <input class="form-check-input me-1" type="checkbox" value="{{ $submenu->id }}" name="sub_menu_id" id="sub-menu-id">
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
@endsection