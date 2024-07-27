<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ConsultationsController;



Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
     
Route::middleware('auth:api')->group( function () {
    Route::get('profile', [RegisterController::class, 'profile']);   
    Route::post('profile', [RegisterController::class, 'profileUpdate']);   
    Route::get('logout', [RegisterController::class, 'logout']);   
    Route::resource('consultations', ConsultationsController::class);
});

 // Route::get('/user', function (Request $request) {
 //        return $request->user();
 //    })->middleware('auth:api');