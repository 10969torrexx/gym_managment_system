<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleSignInController;
use App\Http\Controllers\ExercisesController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OneTimePasswordController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/template', function() {
    return view('template.authentication-login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/google/signin', [GoogleSignInController::class, 'store'])->name('googleLogin');

Route::get('otp/index', [OneTimePasswordController::class, 'index'])->name('otpIndex');

Route::middleware(['auth'])->group(function () {
    Route::get('exercises/index', [ExercisesController::class, 'index'])->name('exercisesIndex');
    Route::post('exercises/store', [ExercisesController::class, 'store'])->name('exercisesStore');
    Route::post('exercises/destroy', [ExercisesController::class, 'destroy'])->name('exercisesDestroy');
    Route::post('exercises/update', [ExercisesController::class, 'update'])->name('exercisesUpdate');

    Route::get('members/index', [MembersController::class, 'index'])->name('membersIndex');
    Route::post('members/destroy', [MembersController::class, 'destroy'])->name('membersDestroy');
    Route::post('members/update', [MembersController::class, 'update'])->name('membersUpdate');

    Route::get('users/index', [UsersController::class, 'index'])->name('usersIndex');
});