<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Modul;
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
        $this->authorize('viewAny', User::class);

        $users = User::latest()->get();

        if (Auth::user()->email != 'admin@admin.com') {
            $users = User::where('email', '!=', 'admin@admin.com')->latest()->get();
        }

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->employee->name;
                })
                ->addColumn('modules', function ($row) {
                    $list = '<ol>';
                    foreach ($row->modules as $modul) {
                        $list = $list . '<li>' . $modul->code . ' - ' . $modul->name . '</li>';
                    }
                    $list = $list . '<ol>';

                    return $list;
                })
                ->addColumn('actions', function ($row) use ($request) {
                    $btn_edit   = '';
                    $btn_info   = '';
                    $btn_delete = '';
                    if ($request->user()->can('update', $row)) {
                        $btn_edit = '<a class="btn btn-info btn-sm" data-id="' . $row->id . '" title="Edit" id="btn-edit"><i class="ti ti-edit"></i></a>';
                    }
                    if ($request->user()->can('view', $row)) {
                        $btn_info = ' <a class="btn btn-success btn-sm" data-id="' . $row->id . '" title="Detail" id="btn-info"><i class="ti ti-info-circle"></i></a>';
                    }
                    if ($request->user()->can('delete', $row)) {
                        $btn_delete = ' <a class="btn btn-danger btn-sm" data-id="' . $row->id . '" title="Delete" id="btn-delete"><i class="ti ti-trash"></i></a>';
                    }
                    return $btn_edit . $btn_info . $btn_delete;
                })
                ->rawColumns(['modules', 'actions'])
                ->make(true);
        }

        return view('settings.user.index', [
            'title'    => 'Users',
            'modules'  => Modul::all()->sortBy('sequence')
        ]);
    }

    public function getEmployees()
    {
        $employees = Employee::doesntHave('user')->get(['id', 'name']);

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'email'       => 'required|email:rfc,dns|unique:users',
            'username'    => 'required|unique:users',
            'password'    => 'required|confirmed',
            'modul_id'    => 'sometimes',
        ], [
            'employee_id.required' => 'Please select the employee'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user              = new User();
        $user->employee_id = $request->employee_id;
        $user->email       = $request->email;
        $user->username    = $request->username;
        $user->password    = Hash::make($request->password);
        $user->save();

        if ($request->modul_id) {
            $modules = Modul::find($request->modul_id);
            $user->modules()->sync($modules);
        }


        return response()->json([
            'success'  => true,
            'data'     => $user,
            'employee' => $user->employee
        ]);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->json([
            'success'  => true,
            'data'     => $user,
            'modules'  => $user->modules->pluck('id'),
            'employee' => $user->employee,
            'info'     => $this->info($user)
        ]);
    }

    public function info(User $user)
    {
        $list = '<ul>';
        foreach ($user->modules as $modul) {
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

        return $list;
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'email'      => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'username'   => ['required', Rule::unique('users')->ignore($user->id)],
            'password'   => 'sometimes|confirmed',
            'modul_id'   => 'sometimes'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->employee_id = $request->employee_id;
        $user->email       = $request->email;
        $user->username    = $request->username;
        $user->password    = $user->password;
        $user->save();
        $modules           = Modul::find($request->modul_id);
        $user->modules()->sync($modules);

        return response()->json([
            'success'  => true,
            'data'     => $user,
            'employee' => $user->employee
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        return response()->json([
            'success'  => true,
            'data'     => $user,
            'employee' => $user->employee
        ]);
    }
}
