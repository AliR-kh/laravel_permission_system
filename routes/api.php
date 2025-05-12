<?php

use App\Http\Controllers\Main\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('login/')->name('login.')->group(function () {
    Route::post('',[\App\Http\Controllers\Authentication\App::class,'register']);
    Route::post('verify',[\App\Http\Controllers\Authentication\App::class,'verified']);
});


Route::prefix('admin/')->name('admin.')->middleware(['auth:sanctum','role:Super Admin'])->group(function () {
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('',[\App\Http\Controllers\Admin\Roles\RoleController::class,'index']);
        Route::post('store',[\App\Http\Controllers\Admin\Roles\RoleController::class,'store']);
        Route::post('update/{role}',[\App\Http\Controllers\Admin\Roles\RoleController::class,'update']);
    Route::prefix('assign')->name('roles.')->group(function () {
        Route::get('team',[\App\Http\Controllers\Admin\Roles\AssignRoleController::class,'team']);
        Route::post('user',[\App\Http\Controllers\Admin\Roles\AssignRoleController::class,'user']);
    });
    });
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('',[\App\Http\Controllers\Admin\Permissions\PermissionController::class,'index']);
        Route::post('store',[\App\Http\Controllers\Admin\Permissions\PermissionController::class,'store']);
        Route::post('update/{permission}',[\App\Http\Controllers\Admin\Permissions\PermissionController::class,'update']);
    Route::prefix('assign')->name('roles.')->group(function () {
        Route::get('team',[\App\Http\Controllers\Admin\Permissions\AssignPermissionController::class,'team']);
        Route::post('user',[\App\Http\Controllers\Admin\Permissions\AssignPermissionController::class,'user']);
    });
    });

    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('',[\App\Http\Controllers\Admin\TeamController::class,'index']);
        Route::post('store',[\App\Http\Controllers\Admin\TeamController::class,'store']);
        Route::post('update/{team}',[\App\Http\Controllers\Admin\TeamController::class,'update']);
        Route::post('assign-user',[\App\Http\Controllers\Admin\TeamController::class,'assignUser']);
    });
});

Route::prefix("products")->name("products.")->middleware('auth:sanctum')->group(function () {
    Route::get("",[ProductController::class,'index'])->name("index");
    Route::get("show/{product}",[ProductController::class,'show'])->name("show");
    Route::post("store",[ProductController::class,'store'])->name("store");
    Route::post("update/{product}",[ProductController::class,'update'])->name("update");
});
