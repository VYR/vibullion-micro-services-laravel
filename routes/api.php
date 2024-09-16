<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' > ['auth:sanctum'],
    'except' => ['signup']
], function () {
    /**User routes */
    Route::group(['prefix' => 'user'], function() {

        Route::post('/signup', [UserController::class,'signup']);

    });
    /**Admin routes */
    Route::group(['prefix' => 'admin'], function() {

            Route::get('/total-users', [UserController::class,'totalUsers']);

        });
    });

Route::fallback(function () {
    return response()->json(['message'=> 'Invalid Request'],404);
});
