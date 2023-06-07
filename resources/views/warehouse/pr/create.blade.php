@extends('layouts.app')

@section('content')
    {{-- form --}}
    <div class="card" id="form">
        <div class="card-body">
            <div class="card-title fw-semibold mb-4">Purchase Requisition</div>
            <hr>
            <form action="#" method="post" id="form-pr">
                @method('post')
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="row d-flex justify-content-between mb-3">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="pr_number" class="form-label">PR Number</label>
                        <input type="text" name="pr_number" id="pr_number" class="form-control">
                        <div class="invalid-feedback d-none" role="alert" id="alert-pr_number"></div>
                    </div>
                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="employee_id" class="form-label">Requester</label>
                        <select name="employee_id" id="employee_id" class="form-select">
                            <option disabled selected> -- Choose -- </option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-pr_number"></div>
                    </div>
                </div>
                <hr>
                <h5>Detail PR</h5>
                <div class="row justify-content-end">
                    <div class="col-4">
                        <div class="row mb-3">
                            <label for="created_at" class="col-3 col-form-label">Date</label>
                            <div class="col-9">
                                <input type="date" name="created_at" id="created_at" class="form-control">
                                <div class="invalid-feedback" role="alert" id="alert-created_at"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="created_at" class="col-3 col-form-label">Due date</label>
                            <div class="col-9">
                                <input type="date" name="due_date" id="due_date" class="form-control">
                                <div class="invalid-feedback" role="alert" id="alert-due_date"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-flex justify-content-start">
                    <div class="col">
                        <a class="btn btn-danger" href="{{ route('prs.index') }}">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection