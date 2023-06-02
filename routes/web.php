<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Setting\PermissionController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ModulController;
use App\Http\Controllers\User\EmployeeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Warehouse\CategoryController;
use App\Http\Controllers\Warehouse\UomController;
use App\Http\Controllers\Warehouse\CurrencyController;
use App\Models\Employee;
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
        Route::get('/get-employees', [UserController::class, 'getEmployees'])->name('users.getEmployees')->can('create', User::class);
        Route::post('/store', [UserController::class, 'store'])->name('users.store')->can('create', User::class);
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show')->can('update', User::class);
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update')->can('update', User::class);
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->can('delete', User::class);
    });

    // employee
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index')->can('viewAny', Employee::class);
        Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store')->can('create', Employee::class);
        Route::get('/{employee}/show', [EmployeeController::class, 'show'])->name('employees.show')->can('update', Employee::class);
        Route::patch('/{employee}', [EmployeeController::class, 'update'])->name('employees.update')->can('update', Employee::class);
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->can('delete', Employee::class);
    });

    // category
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}/show', [CategoryController::class, 'show'])->name('categories.show');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // uom
    Route::group(['prefix' => 'uoms'], function () {
        Route::get('/', [UomController::class, 'index'])->name('uoms.index');
        Route::post('/store', [UomController::class, 'store'])->name('uoms.store');
        Route::get('/{uom}/show', [UomController::class, 'show'])->name('uoms.show');
        Route::patch('/{uom}', [UomController::class, 'update'])->name('uoms.update');
        Route::delete('/{uom}', [UomController::class, 'destroy'])->name('uoms.destroy');
    });

    // currency 
    Route::group(['prefix' => 'currencies'], function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('currencies.index');
        Route::post('/store', [CurrencyController::class, 'store'])->name('currencies.store');
        Route::get('/{currency}/show', [CurrencyController::class, 'show'])->name('currencies.show');
        Route::patch('/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
        Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
    });
});
