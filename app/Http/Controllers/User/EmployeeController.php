<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn_edit   = '<a id="btn-edit" data-id="' . $row->id . '" class="btn btn-info btn-sm"><i class="ti ti-edit"></i></a>';
                    $btn_delete = ' <a id="btn-delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i></a>';

                    return $btn_edit . $btn_delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.employee.index', [
            'title' => 'Employee',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dept' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::create([
            'name' => $request->name,
            'dept' => $request->department
        ]);

        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function show(Employee $employee)
    {
        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dept' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee->update([
            'name' => $request->name,
            'dept' => $request->department
        ]);

        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }
}
