<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\admin\LoginController as AdminLoginController;
use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Auth;

//Home Page
Route::get('/', function () { 
    return view('welcome');
});


//Guest middleware
Route::group(['middleware'=>'guest'],function(){
    Route::get('/login', [LoginController::class, 'index'])->name('user.login');
    Route::post('/login', [LoginController::class, 'login'])->name('user.auth.login');
    Route::get('/register', [LoginController::class, 'register'])->name('user.register');
    Route::post('/register', [LoginController::class, 'newRgister'])->name('user.new.register');        
});
//Authentiated middleware
Route::group(['middleware'=>'auth'],function(){
    //User DashBoard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    //Update Profile
    Route::resource('profile',UserProfileController::class);//[ 'except' => ['index','update'] ]
    //Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('user.logout');       
});

//Admin Panel

Route::group(['prefix' => 'admin'], function () {

    //Guest middleware for admin
    Route::group(['middleware'=>'admin.guest'],function(){  
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('admin.auth.login');  
    });
    //Authentiated middleware for admin
    Route::group(['middleware'=>'admin.auth'],function(){  
        //Admin DashBoard  
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        //Logout
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');  
    });
});





