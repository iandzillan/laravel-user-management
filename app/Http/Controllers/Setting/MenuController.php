<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return '<i class="ti ti-' . $row->icon . '"></i>';
                })
                ->addColumn('permissions', function ($row) {
                    $permissions = $row->permissions->pluck('name');
                    $data = [];
                    foreach ($permissions as $permission) {
                        array_push($data, $permission);
                    }

                    return implode(', ', $data);
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . ' <a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('settings.menu.index', [
            'title'       => 'Menu Master Data',
            'name'        => Auth::user()->name,
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'          => 'required|min:3|max:3|alpha_num|unique:menus',
            'name'          => 'required',
            'icon'          => 'required',
            'route_name'    => 'required',
            'permission_id' => 'required'
        ], [
            'permission_id.required' => 'Please select at leats one permission'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permissions      = Permission::find($request->permission_id);
        $menu             = new Menu();
        $menu->code       = strtoupper($request->code);
        $menu->icon       = $request->icon;
        $menu->name       = ucwords($request->name);
        $menu->route_name = strtolower($request->route_name);
        $menu->save();
        $menu->permissions()->toggle($permissions);

        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }

    public function show(Menu $menu)
    {
        return response()->json([
            'success'     => true,
            'data'        => $menu,
            'permissions' => $menu->permissions->pluck('id')
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'code'          => ['required', 'min:3', 'max:3', 'alpha_num', Rule::unique('menus')->ignore($menu->id)],
            'name'          => 'required',
            'icon'          => 'required',
            'route_name'    => 'required',
            'permission_id' => 'required'
        ], [
            'permission_id.required' => 'Please select at leats one permission'
        ]);

        $permissions      = Permission::find($request->permission_id);
        $menu->code       = strtoupper($request->code);
        $menu->icon       = $request->icon;
        $menu->name       = ucwords($request->name);
        $menu->route_name = strtolower($request->route_name);
        $menu->save();
        $menu->permissions()->sync($permissions);

        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }
}
