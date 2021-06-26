<?php

use App\Http\Controllers\BouncerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Storage;
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
    return UserResource::make($request->user());
});

Route::get('teste', function(){
    return Storage::allFiles();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::group(['prefix' => '/users'], function() {
        Route::get('/', [UserController::class, 'index'])->middleware('can:viewAll,App\\Models\\User');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('can:view,user');
        Route::post('/', [UserController::class, 'store'])->middleware('can:create,App\\Models\\User');
        Route::patch('/{user}', [UserController::class, 'update'])->middleware('can:update,user');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user');

        Route::group(['prefix' => '{user}'], function(){
            Route::post('/allow', [BouncerController::class, 'store'])->middleware('can:managePermissions');
            Route::delete('/disallow', [BouncerController::class, 'destroy'])->middleware('can:managePermissions');
        });
    });
});
