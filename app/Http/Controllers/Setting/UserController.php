<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm mx-2" data-id="' . $row->id . '" title="Edit user"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" data-id="' . $row->id . '" title="Delete user"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.user.index', [
            'title' => 'Users'
        ]);
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
}
