<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' > ['auth:sanctum'],
    'except' => ['signup']
], function () {

    Route::post('/signup', [UserController::class,'signup']);




});
