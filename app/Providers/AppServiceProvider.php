<?php

namespace App\Providers;

use App\Models\Modul;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Register any menu permission.
     */
    public function permission(User $user, $name)
    {
        $user_permission = [];

        foreach ($user->package->moduls as $modul) {
            foreach ($modul->menus->where('name', $name) as $menu) {
                foreach ($menu->permissions as $permission) {
                    $user_permission[] = $permission->name;
                }
            }
        }

        return $user_permission;
    }

    public function access(User $user)
    {
        $access = [];

        foreach ($user->package->moduls as $modul) {
            $access[] = $modul->id;
        }

        return $access;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('edit_permission', function (User $user) {
            return in_array('Update', $this->permission($user, 'Permission'));
        });

        Gate::define('delete_permission', function (User $user) {
            return in_array('Delete', $this->permission($user, 'Permission'));
        });

        Gate::define('update_menu', function (User $user) {
            return in_array('Update', $this->permission($user, 'Menu'));
        });

        Gate::define('delete_menu', function (User $user) {
            return in_array('Delete', $this->permission($user, 'Menu'));
        });

        Gate::define('update_modul', function (User $user, Modul $modul) {
            return in_array($modul->id, $this->access($user)) || in_array('Update', $this->permission($user, 'Modul'));
        });

        Gate::define('info_modul', function (User $user, Modul $modul) {
            return in_array($modul->id, $this->access($user)) || in_array('View', $this->permission($user, 'Modul'));
        });

        Gate::define('delete_modul', function (User $user, Modul $modul) {
            return in_array($modul->id, $this->access($user)) || in_array('Delete', $this->permission($user, 'Modul'));
        });
    }
}
