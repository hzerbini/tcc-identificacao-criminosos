<?php

use App\Http\Controllers\BouncerController;
use App\Http\Controllers\FilepondController;
use App\Http\Controllers\SuspectController;
use App\Http\Controllers\SuspectPhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPhotoController;
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

        Route::group(['prefix' => '{user}/photos'], function(){
            Route::post('/', [UserPhotoController::class, 'store'])->middleware('can:update,user');
            Route::delete('{photoId}', [UserPhotoController::class, 'destroy'])->middleware('can:update,user');
        });
    });

    Route::group(['prefix' => '/suspects'], function(){
        Route::get('/', [SuspectController::class, 'index'])->middleware('can:viewAll,App\\Models\\Suspect');
        Route::get('/{suspect}', [SuspectController::class, 'show'])->middleware('can:view,suspect');
        Route::post('/', [SuspectController::class, 'store'])->middleware('can:create,App\\Models\\Suspect');
        Route::patch('/{suspect}', [SuspectController::class, 'update'])->middleware('can:update,suspect');
        Route::delete('/{suspect}', [SuspectController::class, 'destroy'])->middleware('can:delete,suspect');

        Route::group(['prefix' => '{suspect}/photos'], function(){
            Route::post('/', [SuspectPhotoController::class, 'store'])->middleware('can:update,suspect');
            Route::delete('{photoId}', [SuspectPhotoController::class, 'destroy'])->middleware('can:update,suspect');
        });
    });

    Route::group(['prefix' => 'filepond'], function(){
        Route::post('/', [FilepondController::class, 'process']);
        Route::get('/', [FilepondController::class, 'restore']);
        Route::delete('/', [FilepondController::class, 'revert']);
    });
});
