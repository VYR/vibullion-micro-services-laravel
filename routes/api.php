<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SingleContentController;
use Illuminate\Support\Facades\Route;


Route::group([], function () {
    /**User routes */
    Route::group(['prefix' => 'user'], function() {

        Route::post('/signup', [UserController::class,'signup']);
        Route::post('/get-aadhar-url', [UserController::class,'getAadharUrl']);
        Route::post('/update-bank-details', [UserController::class,'updateBankDetails']);
        Route::post('/update-delivery-address', [UserController::class,'updateDeliveryAddress']);
        Route::post('/update-payment-details', [UserController::class,'updatePaymentDetails']);
        Route::post('/add-payment-details', [UserController::class,'addPaymentDetails']);

    });
    /**Single Content routes */
    Route::group(['prefix' => 'single-content'], function() {

        Route::post('/add', [SingleContentController::class,'addSingleContent']);
        Route::get('/get', [SingleContentController::class,'getSingleContent']);

    });
    /**Admin routes */
    Route::group(['prefix' => 'admin'], function() {

        Route::get('/total-users', [UserController::class,'totalUsers']);

    });

});

Route::fallback(function () {
    return response()->json(['message'=> 'Invalid Request route'],404);
});
