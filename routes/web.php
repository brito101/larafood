<?php

use App\Http\Controllers\Admin\{
    PlanController,
    PlanDetailController,
};
use App\Http\Controllers\Admin\ACL\PermissionController;
use App\Http\Controllers\Admin\ACL\RoleController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.plans.index');
        })->name('home');

        Route::resource("plans/{id}/details", PlanDetailController::class);

        Route::resource("plans", PlanController::class);

        /**
         * ACL
         * */
        /** Permissions */
        Route::resource('permission', PermissionController::class);

        /** Roles */
        Route::get('role/{role}/permission', [RoleController::class, 'permissions'])->name('role.permissions');
        Route::put('role/{role}/permission/sync', [RoleController::class, 'permissionsSync'])->name('role.permissionsSync');
        Route::resource('role', RoleController::class);
    });
});


/**
 * Site
 */
Route::get('/plan/{url}', [SiteController::class, 'plan'])->name('plan.subscription');
Route::get('/', [SiteController::class, 'index'])->name('site.home');

Auth::routes();
