<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Permission::class);

        $user = $request->user();
        $permissions   = Permission::all();
        if ($request->ajax()) {
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) use ($user) {
                    $btn_edit   = '--';
                    $btn_delete = null;
                    if ($user->can('update', Permission::class)) {
                        $btn_edit = '<a class="btn btn-info btn-sm" id="btn-edit" title="Edit" data-id="' . $row->id . '"><i class="ti ti-edit"></i></a>';
                    }
                    if ($user->can('delete', Permission::class)) {
                        $btn_delete = ' <a class="btn btn-danger btn-sm" id="btn-delete" title="Delete" data-id="' . $row->id . '"><i class="ti ti-trash"></i></a>';
                    }

                    return $btn_edit . $btn_delete;
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
        $this->authorize('create', Permission::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission = Permission::create([
            'name' => str_replace(' ', '', $request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function show(Permission $permission)
    {
        $this->authorize('update', Permission::class);

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', Permission::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('permissions')->ignore($permission->id)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission->update([
            'name' => str_replace(' ', '', $request->name)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', Permission::class);

        $permission->delete();
        return response()->json([
            'success' => true,
            'data'    => $permission
        ]);
    }
}
