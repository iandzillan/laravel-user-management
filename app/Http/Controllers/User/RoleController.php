<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.role.index', [
            'title' => 'Role',
            'name'  => Auth::user()->name
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'name' => ucwords($request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }

    public function edit(Role $role)
    {
        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('roles')->ignore($role->id)]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role->update([
            'name' => ucwords($request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'data'    => $role
        ]);
    }
}
