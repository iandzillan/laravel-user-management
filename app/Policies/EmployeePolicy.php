<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function permission(User $user)
    {
        $user_permission = [];
        foreach ($user->modules as $modul) {
            foreach ($modul->menus->where('route_name', 'employees.index') as $menu) {
                foreach ($menu->permissions as $permission) {
                    array_push($user_permission, $permission->name);
                }
            }
        }

        return $user_permission;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array('viewAny', $this->permission($user));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee)
    {
        return in_array('view', $this->permission($user));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array('create', $this->permission($user));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employee $employee)
    {
        return in_array('update', $this->permission($user)) && $employee->user->username != 'superadmin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user)
    {
        return in_array('delete', $this->permission($user));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user)
    {
        //
    }
}
