<?php

use App\Http\Controllers\RegisterUsersController;
use App\Livewire\Dashboard;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Products;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::livewire('/login', Login::class)->name('login')->middleware('guest');

Route::middleware('auth')->group(function () {
//Route::livewire('/dashboard', Dashboard::class)->name('dashboard');
Route::livewire('/dashboard', Home::class)->name('dashboard.home');
Route::livewire('/dashboard/products', Products::class)->name('dashboard.products');

});
