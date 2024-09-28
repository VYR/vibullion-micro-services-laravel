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
        Route::post('/login', [UserController::class,'login']);
        Route::post('/update-user-details', [UserController::class,'updateUserDetails']);
        Route::post('/send-otp', [UserController::class,'sendOtpByMobile']);
        Route::post('/verify-otp', [UserController::class,'verifyOtp']);

    });
    /**Admin routes */
    Route::group(['prefix' => 'admin'], function() {

        Route::get('/total-users', [UserController::class,'totalUsers']);

    });
     /**Call micro services */
     Route::any('{serviceName}/{segment1?}/{segment2?}', [UserController::class,'callMicroServices']);
});

Route::fallback(function () {
    return response()->json(['message'=> 'Invalid Request'],404);
});
