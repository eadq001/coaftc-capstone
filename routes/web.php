<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Products;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;




Route::livewire('/', Login::class)->name('home')->middleware('guest');
Route::livewire('/login', Login::class)->name('login')->middleware('guest');
Route::livewire('/register', Register::class)->name('register')->middleware('guest');

Route::middleware('auth')->group(function () {
//Route::livewire('/dashboard', Dashboard::class)->name('dashboard');
Route::livewire('/dashboard', Home::class)->name('dashboard.home');
Route::livewire('/dashboard/products', Products::class)->name('dashboard.products');
Route::delete('/logout', [LogoutController::class, 'destroy'])->name('logout');

});
