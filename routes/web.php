<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TotalizerPriceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HistoryLogController;

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




Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

        // Roles
        Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('admin.roles-permissions.index');
        Route::post('roles-permissions/role', [RolePermissionController::class, 'storeRole'])->name('admin.roles-permissions.storeRole');
        Route::put('roles-permissions/role/{id}', [RolePermissionController::class, 'updateRole'])->name('admin.roles-permissions.updateRole');
        Route::delete('roles-permissions/role/{id}', [RolePermissionController::class, 'destroyRole'])->name('admin.roles-permissions.destroyRole');

        // Permissions
        Route::post('roles-permissions/permission', [RolePermissionController::class, 'storePermission'])->name('admin.roles-permissions.storePermission');
        Route::put('roles-permissions/permission/{id}', [RolePermissionController::class, 'updatePermission'])->name('admin.roles-permissions.updatePermission');
        Route::delete('roles-permissions/permission/{id}', [RolePermissionController::class, 'destroyPermission'])->name('admin.roles-permissions.destroyPermission');
        Route::put('roles-permissions/role/{roleId}/assign-permissions', [RolePermissionController::class, 'assignPermissions'])->name('admin.roles-permissions.assignPermissions');

        // Users
        Route::resource('users', UserController::class);
    });
    
    Route::get('report-totalizer', [ReportController::class, 'reportTotalizer'])->name('report.totalizer');
    Route::get('/report/totalizer/pdf', [ReportController::class, 'reportTotalizerPdf'])->name('report.totalizer.pdf');
    Route::resource('areas', AreaController::class);
    Route::resource('devices', DeviceController::class);
    Route::resource('totalizer-prices', TotalizerPriceController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('history-logs', HistoryLogController::class);
});