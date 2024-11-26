<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\BreweryController;

// Welcome page
Route::group(['middleware' => 'web'], function () {
  Route::get('/', function () {
    return view('welcome');
  });
});

// Web Login 
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Logged-In User Routes
Route::group(['middleware' => 'auth'], function () {
  Route::get('/home', [UserController::class, 'index'])->name('home');
  Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
  Route::get('/breweries', [BreweryController::class, 'web_index'])->name('web_breweries');
});
