<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SubMenuController extends Controller
{
    public function index(Request $request)
    {
        $submenus = SubMenu::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($submenus)
                ->addIndexColumn()
                ->addColumn('code', function ($row) {
                    return $row->menu->code . '-' . $row->code;
                })
                ->addColumn('menu', function ($row) {
                    return $row->menu->name;
                })
                ->addColumn('icon', function ($row) {
                    return '<i class="ti ti-' . $row->icon . '"></i>';
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('settings.submenu.index', [
            'title' => 'Master Data Sub Menu',
            'name'  => Auth::user()->name
        ]);
    }

    public function getMenus()
    {
        $menus = Menu::all();
        return response()->json($menus);
    }

    public function getMenu($menu)
    {
        $menu = Menu::where('id', $menu)->first();
        return response()->json($menu);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'    => 'required|min:3|max:3|alpha_num|unique:sub_menus',
            'menu_id' => 'required',
            'name'    => 'required|unique:sub_menus',
            'icon'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu    = Menu::where('id', $request->menu_id)->first();
        $submenu = new SubMenu();
        $submenu->code = strtoupper($request->code);
        $submenu->icon = $request->icon;
        $submenu->name = ucwords($request->name);
        $submenu->menu()->associate($menu);
        $submenu->save();

        return response()->json([
            'success' => true,
            'data'    => $submenu
        ]);
    }

    public function edit(SubMenu $submenu)
    {
        return response()->json([
            'success' => true,
            'data'    => $submenu
        ]);
    }

    public function update(Request $request, SubMenu $submenu)
    {
        $validator = Validator::make($request->all(), [
            'code'    => ['required', 'min:3', 'max:3', 'alpha_num', Rule::unique('sub_menus')->ignore($submenu->id)],
            'menu_id' => 'required',
            'name'    => ['required', Rule::unique('sub_menus')->ignore($submenu->id)],
            'icon'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu    = Menu::where('id', $request->menu_id)->first();
        $submenu->code = strtoupper($request->code);
        $submenu->icon = $request->icon;
        $submenu->name = ucwords($request->name);
        $submenu->menu()->associate($menu);
        $submenu->save();

        return response()->json([
            'success' => true,
            'data'    => $submenu
        ]);
    }

    public function destroy(SubMenu $submenu)
    {
        $submenu->delete();

        return response()->json([
            'success' => true,
            'data'    => $submenu
        ]);
    }
}
