<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions   = Permission::all();

        if ($request->ajax()) {
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" title="Edit" data-id="' . $row->id . '"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . ' <a class="btn btn-danger btn-sm" id="btn-delete" title="Delete" data-id="' . $row->id . '"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.permission.index', [
            'title'    => 'Permission',
            'name'     => Auth::user()->name,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission = Permission::create([
            'name' => ucfirst($request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function show(Permission $permission)
    {
        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('permissions')->ignore($permission->id)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission->update([
            'name' => ucfirst($request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }
}
