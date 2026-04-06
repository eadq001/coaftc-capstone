<?php

use App\Http\Controllers\RegisterUsersController;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get('/register', [RegisterUsersController::class, 'show'])->name('password.request');
Route::get('/login', Login::class)->name('login');
Route::get('/rasdsad', [RegisterUsersController::class, 'show'])->name('register');
