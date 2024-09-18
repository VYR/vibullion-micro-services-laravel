<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SingleContentController;
use Illuminate\Support\Facades\Route;


Route::group([], function () {
    /**User routes */
    Route::group(['prefix' => 'user'], function() {

        Route::post('/signup', [UserController::class,'signup']);

    });
    /**Single Content routes */
    Route::group(['prefix' => 'single-content'], function() {

        Route::post('/add', [SingleContentController::class,'addSingleContent']);

    });
    /**Admin routes */
    Route::group(['prefix' => 'admin'], function() {

        Route::get('/total-users', [UserController::class,'totalUsers']);

    });
    /**Call micro services */
    Route::any('{serviceURL}/{segment1?}/{segment2?}', [UserController::class,'callMicroServices']);

});

Route::fallback(function () {
    return response()->json(['message'=> 'Invalid Request route'],404);
});
