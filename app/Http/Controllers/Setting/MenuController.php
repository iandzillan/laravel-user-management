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
        $this->authorize('viewAny', Menu::class);

        if (Auth::user()->email == 'admin@admin.com') {
            $menus = Menu::all()->sortBy('code');
        } else {
            $menus = Menu::with('moduls', 'moduls.packages', 'moduls.packages.users')->whereHas('moduls.packages.users', function ($q) {
                $q->where('id', Auth::user()->id);
            })->get()->sortBy('code');
        }

        $user  = $request->user();

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
                ->addColumn('actions', function ($row) use ($user) {
                    $btn_edit   = '--';
                    $btn_delete = '';
                    if ($user->can('update_menu')) {
                        $btn_edit = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    }
                    if ($user->can('delete_menu')) {
                        $btn_delete = ' <a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    }
                    return $btn_edit . $btn_delete;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('settings.menu.index', [
            'title'       => 'Menu Master Data',
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Menu::class);

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
        $this->authorize('update', Menu::class);

        return response()->json([
            'success'     => true,
            'data'        => $menu,
            'permissions' => $menu->permissions->pluck('id')
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', Menu::class);

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
        $this->authorize('delete', Menu::class);

        $menu->delete();
        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }

    public function testing()
    {
        $menu = Menu::with('moduls', 'moduls.packages', 'moduls.packages.users')->whereHas('moduls.packages.users', function ($q) {
            $q->where('id', Auth::user()->id);
        })->get()->sortBy('code');

        dd($menu);
    }
}
