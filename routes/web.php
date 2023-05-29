<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Setting\PermissionController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ModulController;
use App\Http\Controllers\Setting\PackageController;
use App\Http\Controllers\User\UserController;
use App\Models\Menu;
use App\Models\Permission;
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
    Route::get('/testing', [MenuController::class, 'testing']);

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
    Route::group(['prefix' => 'moduls'], function () {
        Route::get('/', [ModulController::class, 'index'])->name('moduls.index');
        Route::post('/store', [ModulController::class, 'store'])->name('moduls.store');
        Route::get('/{modul}/show', [ModulController::class, 'show'])->name('moduls.show');
        Route::patch('/{modul}', [ModulController::class, 'update'])->name('moduls.update');
        Route::delete('/{modul}', [ModulController::class, 'destroy'])->name('moduls.destroy');
    });

    // package
    Route::group(['prefix' => 'packages'], function () {
        Route::get('/', [PackageController::class, 'index'])->name('packages.index');
        Route::post('/store', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/{package}/show', [PackageController::class, 'show'])->name('packages.show');
        Route::patch('/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    });

    // user
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/get-packages', [UserController::class, 'getPackages'])->name('users.getpackages');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
