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
use App\Http\Controllers\Warehouse\ItemController;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\Permission;
use App\Models\Uom;
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
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // employee
    Route::group(['prefix' => 'employees'], function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index')->can('viewAny', Employee::class);
        Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store')->can('create', Employee::class);
        Route::get('/{employee}/show', [EmployeeController::class, 'show'])->name('employees.show');
        Route::patch('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // category
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index')->can('viewAny', Category::class);
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store')->can('create', Category::class);
        Route::get('/{category}/show', [CategoryController::class, 'show'])->name('categories.show')->can('update', Category::class);
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('categories.update')->can('update', Category::class);
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->can('delete', Category::class);
    });

    // uom
    Route::group(['prefix' => 'uoms'], function () {
        Route::get('/', [UomController::class, 'index'])->name('uoms.index')->can('viewAny', Uom::class);
        Route::post('/store', [UomController::class, 'store'])->name('uoms.store')->can('create', Uom::class);
        Route::get('/{uom}/show', [UomController::class, 'show'])->name('uoms.show')->can('update', Uom::class);
        Route::patch('/{uom}', [UomController::class, 'update'])->name('uoms.update')->can('update', Uom::class);
        Route::delete('/{uom}', [UomController::class, 'destroy'])->name('uoms.destroy')->can('delete', Uom::class);
    });

    // currency 
    Route::group(['prefix' => 'currencies'], function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('currencies.index')->can('viewAny', Currency::class);
        Route::post('/store', [CurrencyController::class, 'store'])->name('currencies.store')->can('create', Currency::class);
        Route::get('/{currency}/show', [CurrencyController::class, 'show'])->name('currencies.show')->can('update', Currency::class);
        Route::patch('/{currency}', [CurrencyController::class, 'update'])->name('currencies.update')->can('update', Currency::class);
        Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy')->can('delete', Currency::class);
    });

    // item
    Route::group(['prefix' => 'items'], function () {
        Route::get('/', [ItemController::class, 'index'])->name('items.index');
        Route::post('/store', [ItemController::class, 'store'])->name('items.store');
        Route::get('/{item}/show', [ItemController::class, 'show'])->name('items.show');
        Route::patch('/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    });
});
