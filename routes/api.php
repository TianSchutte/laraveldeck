<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\BearerAuth;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/register', [LoginController::class, 'register']);
Route::post('/user/login', [LoginController::class, 'login']);

Route::middleware([BearerAuth::class])->group(function () {
    //verify bearer token supplied
    Route::get('/user/all', [UserController::class, 'index']);
    Route::get('/user/{user:id}', [UserController::class, 'show']);

    //verify bearer token supplied and token's relevant user also has neccessary spattie permissions
    Route::group(['middleware' => ['role:Admin|Moderator']], function () {
        Route::put('/user/update/{user}', [UserController::class, 'update']);
        Route::delete('/user/delete/{user}', [UserController::class, 'destroy']);


    });
//todo: use permissions instead of grouped roles for some to test
    Route::group(['middleware' => ['role:Admin']], function () {

        Route::get('/user/view/access_tokens/{user}', [UserController::class, 'showUserAccessTokens']);

        //Role Controls
        Route::get('/role/all', [RoleController::class, 'index']);
        Route::get('/role/{role}', [RoleController::class, 'show']);
        Route::post('/role/create/', [RoleController::class, 'create']);
        Route::put('/role/update/{role}', [RoleController::class, 'update']);
        Route::delete('/role/delete/{role}', [RoleController::class, 'destroy']);

        //Roles And User Controls
        Route::post('/role/grant/{user}', [RoleController::class, 'grantUserRole']);
        Route::post('/role/revoke/{user}', [RoleController::class, 'revokeUserRole']);
        Route::get('/role/view/users', [RoleController::class, 'viewRoleUsers']);
        Route::get('/role/assignPermission/', [RoleController::class, 'assignPermissions']);

        //Permission Controls
        Route::get('/permission/all', [PermissionController::class, 'index']);
        Route::get('/permission/{permission}', [PermissionController::class, 'show']);
        Route::put('/permission/update/{permission}', [PermissionController::class, 'update']);
        Route::delete('/permission/delete/{permission}', [PermissionController::class, 'destroy']);
        Route::post('/permission/create/', [PermissionController::class, 'create']);
        Route::post('/permission/assignRole/', [PermissionController::class, 'assignRole']);

    });
});
