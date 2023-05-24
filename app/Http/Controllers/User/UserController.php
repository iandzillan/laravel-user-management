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
        $users = User::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('packages', function ($row) {
                    $list = '<ul>';
                    foreach ($row->packages as $package) {
                        $list = $list . '<li><strong>' . $package->name . '</strong></li>';
                        $list = $list . '<ol>';
                        foreach ($package->moduls as $modul) {
                            $list = $list . '<li>' . $modul->name . '</li>';
                            $list = $list . '<ul>';
                            foreach ($modul->menus as $menu) {
                                $list = $list . '<li class="mx-1"> <i class="ti ti-' . $menu->icon . '"></i> ' . $menu->name . '</li>';
                            }
                            $list = $list . '</ul>';
                        }
                        $list = $list . '</ol>';
                    }
                    $list = $list . '</ul>';
                    return $list;
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" data-id="' . $row->id . '" title="Edit user" id="btn-edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" data-id="' . $row->id . '" title="Delete user" id="btn-delete"><i class="ti ti-trash"></i></a>';
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email:rfc,dns|unique:users',
            'username'   => 'required|unique:users',
            'password'   => 'required|confirmed',
            'package_id' => 'required'
        ], [
            'package_id.required' => 'Please select at least one package'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $packages       = Package::find($request->package_id);
        $user           = new User();
        $user->name     = ucwords($request->name);
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->packages()->toggle($packages);


        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function edit(User $user)
    {
        return response()->json([
            'success'  => true,
            'data'     => $user,
            'packages' => $user->packages->pluck('id')
        ]);
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
            'package_id.required' => 'Please select at least one package'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $packages       = Package::find($request->package_id);
        $user->name     = ucwords($request->name);
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = $user->password;
        $user->save();
        $user->packages()->sync($packages);

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
