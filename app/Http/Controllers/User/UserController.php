<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->email === 'admin@admin.com') {
            $users = User::latest()->get();
        } else {
            $users = User::where('email', '!=', 'admin@admin.com')->latest()->get();
        }

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('package', function ($row) {
                    if ($row->package_id === null) {
                        return "--";
                    } else {
                        return $row->package->name;
                    }
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" data-id="' . $row->id . '" title="Edit" id="btn-edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . ' <a class="btn btn-success btn-sm" data-id="' . $row->id . '" title="Detail" id="btn-info"><i class="ti ti-info-circle"></i></a>';
                    $btn = $btn . ' <a class="btn btn-danger btn-sm" data-id="' . $row->id . '" title="Delete" id="btn-delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['packages', 'actions'])
                ->make(true);
        }

        return view('settings.user.index', [
            'title'    => 'Users',
            'name'     => Auth::user()->name,
            'packages' => Package::all()
        ]);
    }

    public function getPackages()
    {
        $packages = Package::all();
        return response()->json($packages);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email:rfc,dns|unique:users',
            'username'   => 'required|unique:users',
            'password'   => 'required|confirmed',
            'package_id' => 'required'
        ], [
            'package_id.required' => 'Please choose the package'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $package       = Package::find($request->package_id);
        $user           = new User();
        $user->name     = ucwords($request->name);
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->package()->associate($package);
        $user->save();


        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function show(User $user)
    {
        if ($user->package_id === null) {
            $package = null;
        } else {
            $package = $user->package_id;
        }

        return response()->json([
            'success'  => true,
            'data'     => $user,
            'package'  => $package,
            'info'     => $this->info($user)
        ]);
    }

    public function info(User $user)
    {
        if ($user->package_id === null) {
            $list = null;
        } else {
            $list = '<ul>';
            foreach ($user->package->moduls as $modul) {
                $list = $list . '<li data-jstree=\'{"opened":true, "icon":"ti ti-folder"}\'>' . $modul->code . ' - ' . $modul->name;
                $list = $list . '<ul>';
                foreach ($modul->menus as $menu) {
                    $list = $list . '<li data-jstree=\'{"opened":true, "icon":"ti ti-' . $menu->icon . '"}\'>' . $menu->code . ' - ' . $menu->name;
                    $list = $list . '<ul>';
                    foreach ($menu->permissions as $permission) {
                        $list = $list . '<li data-jstree=\'{"opened":true, "icon":"ti ti-fingerprint"}\'>' . $permission->name . '</li>';
                    }
                    $list = $list . '</ul>';
                }
                $list = $list . '</ul>';
                $list = $list . '</li>';
            }
            $list = $list . '</ul>';
        }


        return $list;
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'username'   => ['required', Rule::unique('users')->ignore($user->id)],
            'password'   => 'sometimes|confirmed',
            'package_id' => 'required'
        ], [
            'package_id.required' => 'Please choose the package'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $package        = Package::find($request->package_id);
        $user->name     = ucwords($request->name);
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = $user->password;
        $user->package()->associate($package);
        $user->save();

        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }
}
