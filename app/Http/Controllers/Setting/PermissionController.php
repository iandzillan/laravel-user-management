<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $packages   = Package::all();
        $id_submenu = [];
        foreach ($packages as $package) {
            $submenus = $package->subMenus;
            foreach ($submenus as $submenu) {
                array_push($id_submenu, $submenu->id);
            }
            $menus = Menu::with('subMenus')->whereHas('subMenus', function ($q) use ($id_submenu) {
                $q->whereIn('id', $id_submenu);
            })->get();
        }

        return view('settings.permission.index', [
            'title'    => 'Permission',
            'name'     => Auth::user()->name,
            'packages' => $packages,
            'menus'    => $menus
        ]);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required',
            'package_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }

    public function testing()
    {
    }
}
