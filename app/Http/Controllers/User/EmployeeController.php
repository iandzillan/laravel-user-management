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
        $this->authorize('viewAny', Employee::class);

        $employees = Employee::latest()->get();
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    $username = '';
                    if ($row->user != null) {
                        $username = $row->user->username;
                    }
                    return $username;
                })
                ->addColumn('actions', function ($row) use ($request) {
                    $btn_edit   = '';
                    $btn_delete = '';
                    if ($request->user()->can('update', $row)) {
                        $btn_edit   = '<a id="btn-edit" data-id="' . $row->id . '" class="btn btn-info btn-sm" title="Edit"><i class="ti ti-edit"></i></a>';
                    }
                    if ($request->user()->can('delete', $row)) {
                        $btn_delete = ' <a id="btn-delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete"><i class="ti ti-trash"></i></a>';
                    }

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
        $this->authorize('create', Employee::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dept' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::create([
            'name' => $request->name,
            'dept' => $request->dept
        ]);

        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function show(Employee $employee)
    {
        $this->authorize('update', $employee);
        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dept' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee->update([
            'name' => $request->name,
            'dept' => $request->dept
        ]);

        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);

        $employee->delete();
        return response()->json([
            'success' => true,
            'data'    => $employee
        ]);
    }
}
