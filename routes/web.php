<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Setting\PermissionController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ModulController;
use App\Http\Controllers\Setting\PackageController;
use App\Http\Controllers\User\UserController;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // permission
    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index')->can('viewAny', Permission::class);
        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store')->can('create', Permission::class);
        Route::get('/{permission}/show', [PermissionController::class, 'show'])->name('permissions.show')->can('update', Permission::class);
        Route::patch('/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->can('update', Permission::class);
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->can('delete', Permission::class);
    });

    // menu
    Route::group(['prefix' => 'menus'], function () {
        Route::get('/', [MenuController::class, 'index'])->name('menus.index')->can('viewAny', Menu::class);
        Route::post('/store', [MenuController::class, 'store'])->name('menus.store')->can('create', Menu::class);
        Route::get('/{menu}/show', [MenuController::class, 'show'])->name('menus.show')->can('update', Menu::class);
        Route::patch('/{menu}', [MenuController::class, 'update'])->name('menus.update')->can('update', Menu::class);
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy')->can('delete', Menu::class);
    });

    // modul
    Route::group(['prefix' => 'modules'], function () {
        Route::get('/', [ModulController::class, 'index'])->name('modules.index')->can('viewAny', Modul::class);
        Route::post('/store', [ModulController::class, 'store'])->name('modules.store')->can('create', Modul::class);
        Route::get('/{modul}/show', [ModulController::class, 'show'])->name('modules.show')->can('update', Modul::class);
        Route::patch('/{modul}', [ModulController::class, 'update'])->name('modules.update')->can('update', Modul::class);
        Route::delete('/{modul}', [ModulController::class, 'destroy'])->name('modules.destroy')->can('delete', Modul::class);
    });

    // user
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index')->can('viewAny', User::class);
        Route::post('/store', [UserController::class, 'store'])->name('users.store')->can('create', User::class);
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show')->can('update', User::class);
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update')->can('update', User::class);
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->can('delete', User::class);
    });
});
