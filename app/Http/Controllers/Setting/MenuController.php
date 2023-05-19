<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::withCount('subMenus')->latest()->get();

        if ($request->ajax()) {
            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return '<i class="ti ti-' . $row->icon . '"></i>';
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-primary btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';

                    return $btn;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('settings.menu.index', [
            'title' => 'Menu Master Data',
            'name'  => Auth::user()->name
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'        => 'required|unique:menus|min:4|max:4|alpha_num',
            'name'        => 'required|unique:menus',
            'icon'        => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu = Menu::create([
            'code'        => strtoupper($request->code),
            'name'        => ucwords($request->name),
            'icon'        => $request->icon,
            'description' => ucfirst($request->description)
        ]);

        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }

    public function edit(Menu $menu)
    {
        return response()->json([
            'success' => true,
            'data'    => $menu
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'code'        => ['required', 'min:4', 'max:4', 'alpha_num', Rule::unique('menus')->ignore($menu->id)],
            'name'        => ['required', Rule::unique('menus')->ignore($menu->id)],
            'icon'        => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu->update([
            'code'        => strtoupper($request->code),
            'name'        => ucwords($request->name),
            'icon'        => $request->icon,
            'description' => ucfirst($request->description)
        ]);

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
