<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\PackageController;
use App\Http\Controllers\Setting\SubMenuController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // user
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/roles', [UserController::class, 'getRoles'])->name('users.roles');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // role
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::patch('/{role} ', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/{role} ', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // menu
    Route::group(['prefix' => 'menus'], function () {
        Route::get('/', [MenuController::class, 'index'])->name('menus.index');
        Route::post('/store', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::patch('/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    });

    // sub menu
    Route::group(['prefix' => 'submenus'], function () {
        Route::get('/', [SubMenuController::class, 'index'])->name('submenus.index');
        Route::get('/menus', [SubMenuController::class, 'getMenus'])->name('submenus.menus');
        Route::get('/menus/{menu}', [SubMenuController::class, 'getMenu'])->name('submenus.menu');
        Route::post('/store', [SubMenuController::class, 'store'])->name('submenus.store');
        Route::get('/{submenu}/edit', [SubMenuController::class, 'edit'])->name('submenus.edit');
        Route::patch('/{submenu}', [SubMenuController::class, 'update'])->name('submenus.update');
        Route::delete('/{submenu}', [SubMenuController::class, 'destroy'])->name('submenus.destroy');
    });

    // permission
    Route::group(['prefix' => 'packages'], function () {
        Route::get('/', [PackageController::class, 'index'])->name('packages.index');
        Route::post('/store', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::patch('/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    });

    Route::get('/testing', [PackageController::class, 'testing']);
});
