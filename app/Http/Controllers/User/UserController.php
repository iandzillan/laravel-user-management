<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" data-id="' . $row->id . '" title="Edit user" id="btn-edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" data-id="' . $row->id . '" title="Delete user" id="btn-delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('role', function ($row) {
                    return $row->role->name;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.user.index', [
            'title' => 'Users',
            'name'  => Auth::user()->name
        ]);
    }

    public function getRoles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email:rfc,dns|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function edit(User $user)
    {
        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => $user->password,
        ]);

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
