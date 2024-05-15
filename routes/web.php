<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleSignInController;
use App\Http\Controllers\ExercisesController;
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/template', function() {
    return view('template.authentication-login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/google/signin', [GoogleSignInController::class, 'store'])->name('googleLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('exercises/index', [ExercisesController::class, 'index'])->name('exercisesIndex');
    Route::post('exercises/store', [ExercisesController::class, 'store'])->name('exercisesStore');
    Route::post('exercises/destroy', [ExercisesController::class, 'destroy'])->name('exercisesDestroy');
});