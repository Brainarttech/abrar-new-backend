<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\PermissionController;
use App\Http\Controllers\api\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);

// User

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('user')->group(function(){
    Route::get('all',[AuthController::class,'users']);
    Route::post('store',[AuthController::class,'store']);
    Route::get('delete/{id}',[AuthController::class,'destroy']);
    Route::get('edit/{id}',[AuthController::class,'edit']);
    Route::get('update',[AuthController::class,'update']);
});

// Roles
Route::prefix('role')->group(function(){
    Route::get('all',[RoleController::class,'index']);
    Route::post('create',[RoleController::class,'store']);
    Route::get('delete/{id}',[RoleController::class,'destroy']);
    Route::get('edit/{id}',[RoleController::class,'edit']);
    Route::post('update',[RoleController::class,'update']);

});


// Permissions

Route::prefix('permission')->group(function(){

    //Permission categories
    Route::get('categories',[PermissionController::class,'all_categories']);
    Route::post('store-category',[PermissionController::class,'store_category']);
    Route::get('edit-category/{id}',[PermissionController::class,'edit_category']);
    Route::get('update-category',[PermissionController::class,'update_category']);
    //
    Route::get('all',[PermissionController::class,'index']);
    Route::post('create',[PermissionController::class,'store']);
    Route::get('delete/{id}',[PermissionController::class,'destroy']);
    Route::get('edit/{id}',[PermissionController::class,'edit']);
    Route::post('update',[PermissionController::class,'update']);
});


Route::prefix('category')->group(function(){
    Route::get('all',[PermissionController::class,'index']);
    Route::post('store',[PermissionController::class,'store']);
    Route::get('edit/{id}',[PermissionController::class,'edit']);
    Route::post('update',[PermissionController::class,'update']);
});


});




    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\api\PermissionController::class, 'destroy']);

    Route::get('roles',[RoleController::class,'index']);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\api\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\api\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\api\RoleController::class, 'givePermissionToRole']);

    // Route::resource('users', App\Http\Controllers\api\UserController::class);
    Route::get('users/{userId}/delete', [AuthController::class, 'destroy']);


