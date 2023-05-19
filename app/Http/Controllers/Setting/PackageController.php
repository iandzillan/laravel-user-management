<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($packages)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $btn = '<a class="btn btn-info btn-sm" id="btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ti ti-edit"></i></a>';
                    $btn = $btn . '<a class="btn btn-danger btn-sm" id="btn-delete" data-id="' . $row->id . '" title="Delete"><i class="ti ti-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('settings.package.index', [
            'title' => 'Menu Package',
            'name'  => Auth::user()->name,
            'menus' => Menu::all()
        ]);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getUser(User $user)
    {
        return response()->json($user);
    }
}
