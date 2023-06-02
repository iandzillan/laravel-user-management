<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Modul::class);

        $modules = Modul::latest()->get();

        $user   = $request->user();
        if ($request->ajax()) {
            return DataTables::of($modules)
                ->addIndexColumn()
                ->addColumn('menus', function ($row) {
                    $list = '<ul>';
                    foreach ($row->menus->sortBy('code') as $menu) {
                        $list = $list . '<li> ' . $menu->code . ' - <i class="ti ti-' . $menu->icon . '"></i> ' . $menu->name . '</li>';
                    }
                    $list = $list . '</ul>';

                    return $list;
                })
                ->addColumn('actions', function ($row) use ($user) {
                    $btn_edit   = '';
                    $btn_info   = '';
                    $btn_delete = '';
                    if ($user->can('update', $row)) {
                        $btn_edit   = '<a class="btn btn-primary btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    }
                    if ($user->can('view', $row)) {
                        $btn_info   = ' <a class="btn btn-success btn-sm" id="btn-info" data-id="' . $row->id . '" title="Detail"><i class="ti ti-info-circle"></i></a>';
                    }
                    if ($user->can('delete', $row)) {
                        $btn_delete = ' <a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    }

                    return $btn_edit . $btn_info . $btn_delete;
                })
                ->rawColumns(['menus', 'icon', 'actions'])
                ->make(true);
        }

        return view('settings.modul.index', [
            'title' => 'Modul Master Data',
            'name'  => Auth::user()->name,
            'menus' => Menu::all()->sortBy(['code', 'sequence'])
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'        => 'required|unique:modules|min:4|max:4|alpha_num',
            'sequence'    => 'required|integer',
            'name'        => 'required|unique:modules',
            'description' => 'required',
            'menu_id'     => 'required'
        ], [
            'menu_id.required' => 'At least choose one menu'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu  = Menu::find($request->menu_id);
        $modul = new Modul();
        $modul->code        = $request->code;
        $modul->sequence    = $request->sequence;
        $modul->name        = $request->name;
        $modul->description = $request->description;
        $modul->save();
        $modul->menus()->sync($menu);

        return response()->json([
            'success' => true,
            'data'    => $modul
        ]);
    }

    public function show(Modul $modul)
    {
        // $this->authorize('update', $modul);

        return response()->json([
            'success' => true,
            'data'    => $modul,
            'menus'   => $modul->menus->pluck('id'),
            'info'    => $this->info($modul)
        ]);
    }

    public function info(Modul $modul)
    {
        $list = '<ul>';
        foreach ($modul->menus as $menu) {
            $list = $list . '<li data-jstree=\'{"opened": true, "icon": "ti ti-' . $menu->icon . '"}\'>' . $menu->code . ' - ' . $menu->name;
            $list = $list . '<ul>';
            foreach ($menu->permissions as $permission) {
                $list = $list . '<li data-jstree=\'{"opened": true, "icon": "ti ti-fingerprint"}\'>' . $permission->name . '</li>';
            }
            $list = $list . '</ul>';
            $list = $list . '</li>';
        }
        $list = $list . '</ul>';

        return $list;
    }

    public function update(Request $request, Modul $modul)
    {
        $validator = Validator::make($request->all(), [
            'code'        => ['required', 'min:4', 'max:4', 'alpha_num', Rule::unique('modules')->ignore($modul->id)],
            'sequence'    => 'required|integer',
            'name'        => ['required', Rule::unique('modules')->ignore($modul->id)],
            'description' => 'required',
            'menu_id'     => 'required'
        ], [
            'menu_id.required' => 'At least choose one menu'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $menu               = Menu::find($request->menu_id);
        $modul->code        = $request->code;
        $modul->sequence    = $request->sequence;
        $modul->name        = $request->name;
        $modul->description = $request->description;
        $modul->save();
        $modul->menus()->sync($menu);

        return response()->json([
            'success' => true,
            'data'    => $modul
        ]);
    }

    public function destroy(Modul $modul)
    {
        $modul->delete();

        return response()->json([
            'success' => true,
            'data'    => $modul
        ]);
    }
}
