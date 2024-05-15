<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleSignInController;
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/template', function() {
    return view('template.authentication-login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/google/signin', [GoogleSignInController::class, 'store'])->name('googleLogin');