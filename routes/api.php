<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\UserController;


Route::post('/token', [UserController::class, 'getToken']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('create', [UserController::class, 'createUser']);
    Route::post('update', [UserController::class, 'updateUser']);
    Route::post('delete', [UserController::class, 'deleteUser']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
