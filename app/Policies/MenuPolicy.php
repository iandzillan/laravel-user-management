<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    /**
     * Determine the user permission for manage menu.
     */
    public function permission(User $user)
    {
        $user_permission = [];

        foreach ($user->package->moduls as $modul) {
            foreach ($modul->menus->where('name', 'Menu') as $menu) {
                foreach ($menu->permissions as $permission) {
                    $user_permission[] = $permission->name;
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
        return in_array('ViewAny', $this->permission($user));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Menu $menu)
    {
        //
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
    public function update(User $user)
    {
        return in_array('Update', $this->permission($user));
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
    public function restore(User $user, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Menu $menu)
    {
        //
    }
}
