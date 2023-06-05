<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Get user permission from user menu.
     */
    public function permission(User $user)
    {
        $user_permission = [];

        foreach ($user->modules as $modul) {
            foreach ($modul->menus->where('route_name', 'users.index') as $menu) {
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
        return in_array('viewAny', $this->permission($user));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        if ($model->id === $user->id) {
            return in_array('update', $this->permission($user));
        }
        if ($model->username != 'superadmin') {
            return in_array('update', $this->permission($user));
        }
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
    public function update(User $user, User $model)
    {
        if ($model->id === $user->id) {
            return in_array('update', $this->permission($user));
        }
        if ($model->username != 'superadmin') {
            return in_array('update', $this->permission($user));
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        if ($model->id === $user->id) {
            return in_array('update', $this->permission($user));
        }
        if ($model->username != 'superadmin') {
            return in_array('update', $this->permission($user));
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can set the modules of the model.
     */
    public function setModul(User $user)
    {
        return in_array('setModul', $this->permission($user));
    }
}
