<?php

use App\Http\Controllers\RegisterUsersController;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::livewire('/login', Login::class)->name('login');

Route::middleware('auth')->group(function () {
Route::livewire('/dashboard', Dashboard::class)->name('dashboard');

});
