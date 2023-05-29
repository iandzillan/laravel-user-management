<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($packages)
                ->addIndexColumn()
                ->addColumn('moduls', function ($row) {
                    $list = '<ul>';
                    foreach ($row->moduls->sortBy('code') as $modul) {
                        $list = $list . '<li class="mb-3"><strong>' . $modul->name . '</strong>';
                        $list = $list . '<ol>';
                        foreach ($modul->menus as $menu) {
                            $list = $list . '<li><i class="ti ti-' . $menu->icon . '"></i> ' . $menu->name . '</li>';
                        }
                        $list = $list . '</ol>';
                        $list = $list . '</li>';
                    }
                    $list = $list . '</ul>';

                    return $list;
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . ' <a class="btn btn-success btn-sm" id="btn-info" data-id="' . $row->id . '" title="Deatil"><i class="ti ti-info-circle"></i></a>';
                    $btn = $btn . ' <a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['moduls', 'actions'])
                ->make(true);
        }

        return view('settings.package.index', [
            'title'  => 'Menu Package',
            'name'   => Auth::user()->name,
            'moduls' => Modul::all()->sortBy('code')
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'        => 'required|unique:packages|min:2|max:2|alpha_num',
            'name'        => 'required',
            'description' => 'required',
            'modul_id' => 'required'
        ], [
            'modul_id.required' => 'Please select at least one modul'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $package = Package::create([
            'code'        => $request->code,
            'name'        => $request->name,
            'description' => $request->description
        ]);
        $moduls = Modul::find($request->modul_id);
        $package->moduls()->toggle($moduls);

        return response()->json([
            'success' => true,
            'data'    => $package
        ]);
    }

    public function show(Package $package)
    {
        return response()->json([
            'success'  => true,
            'package'  => $package,
            'moduls'   => $package->moduls->pluck('id'),
            'info'     => $this->info($package)
        ]);
    }

    public function info(Package $package)
    {
        $list = '<ul>';
        foreach ($package->moduls->sortBy('code') as $modul) {
            $list = $list . '<li data-jstree=\'{"opened": true, "icon": "ti ti-folder"}\'>' . $modul->code . ' - ' . $modul->name;
            $list = $list . '<ul>';
            foreach ($modul->menus->sortBy('code') as $menu) {
                $list = $list . '<li data-jstree=\'{"opened": true, "icon": "ti ti-' . $menu->icon . '"}\'>' . $menu->code . ' - ' . $menu->name;
                $list = $list . '<ul>';
                foreach ($menu->permissions as $permission) {
                    $list = $list . '<li data-jstree=\'{"opened": true, "icon": "ti ti-fingerprint"}\'>' . $permission->name . '</li>';
                }
                $list = $list . '</ul></li>';
            }
            $list = $list . '</ul></li>';
        }
        $list = $list . '</ul>';

        return $list;
    }

    public function update(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'code'        => ['required', 'min:2', 'max:2', 'alpha_num', Rule::unique('packages')->ignore($package->id)],
            'name'        => 'required',
            'description' => 'required',
            'modul_id'    => 'required'
        ], [
            'modul_id.required' => 'Please select at least one modul'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $package->update([
            'code'        => $request->code,
            'name'        => $request->name,
            'description' => $request->description
        ]);
        $moduls = Modul::find($request->modul_id);
        $package->moduls()->sync($moduls);

        return response()->json([
            'success' => true,
            'data'    => $package
        ]);
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json([
            'message' => true,
            'data'    => $package
        ]);
    }
}
