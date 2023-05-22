<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Package;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($packages)
                ->addIndexColumn()
                ->addColumn('menus', function ($row) {
                    $id = [];
                    $submenus = $row->subMenus;
                    foreach ($submenus as $submenu) {
                        $id[] = $submenu->id;
                    }

                    $menus = Menu::with('subMenus')->whereHas('subMenus', function ($q) use ($id) {
                        $q->whereIn('id', $id);
                    })->get();

                    $list = '<ol class="list-group list-group-flush">';
                    foreach ($menus as $menu) {
                        $list = $list . '<li class="list-group-item"><b>' . $menu->name . ':</b>';
                        $list = $list . '<ul>';
                        foreach ($menu->subMenus->whereIn('id', $id) as $submenu) {
                            $list = $list . '<li>' . $submenu->name . '</li>';
                        }
                        $list = $list . '</ul></li>';
                    }
                    $list = $list . '</ol>';

                    return $list;
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['menus', 'actions'])
                ->make(true);
        }

        return view('settings.package.index', [
            'title' => 'Menu Package',
            'name'  => Auth::user()->name,
            'menus' => Menu::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'        => 'required|unique:packages|min:2|max:2|alpha_num',
            'name'        => 'required',
            'description' => 'required',
            'sub_menu_id' => 'required'
        ], [
            'sub_menu_id.required' => 'Please select at least one menu'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $package = Package::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'description' => $request->description
        ]);
        $submenus = SubMenu::find($request->sub_menu_id);
        $package->subMenus()->toggle($submenus);

        return response()->json([
            'success' => true,
            'data'    => $package
        ]);
    }

    public function edit(Package $package)
    {
        return response()->json([
            'success'  => true,
            'package'  => $package,
            'submenus' => $package->subMenus
        ]);
    }

    public function testing()
    {
        $package = Package::with('subMenus')->where('id', 6)->first();

        dd($package);
    }
}
