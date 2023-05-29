<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Modul;
use App\Models\User;

class ModulPolicy
{
    /**
     * Determine the permission of authenticated user
     */
    public function permission(User $user)
    {
        $user_permission = [];

        foreach ($user->package->moduls as $modul) {
            foreach ($modul->menus->where('name', 'Modul') as $menu) {
                foreach ($menu->permissions as $permission) {
                    $user_permission[] = $permission->name;
                }
            }
        }

        return $user_permission;
    }

    public function access(User $user)
    {
        $user_access = [];

        foreach ($user->package->moduls as $modul) {
            $user_access[] = $modul->id;
        }

        return $user_access;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array('ViewAny', $this->permission($user));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Modul $modul)
    {
        return in_array('View', $this->permission($user)) || in_array($modul->id, $this->access($user));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array('Create', $this->permission($user));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Modul $modul)
    {
        return in_array('Update', $this->permission($user)) || in_array($modul->id, $this->access($user));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user)
    {
        return in_array('Delete', $this->permission($user));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Modul $modul)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Modul $modul)
    {
        //
    }
}
