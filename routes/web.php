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

Route::livewire('/verification', 'auth.register-confirm-email')->name('verification.verify')->middleware('guest');

Route::middleware('auth')->group(function () {
    // Route::livewire('/dashboard', Dashboard::class)->name('dashboard');
    Route::livewire('/dashboard', Home::class)->name('dashboard.home');
    Route::livewire('/dashboard/products', Products::class)->name('dashboard.products');
    Route::livewire('/dashboard/products/qr', 'dashboard.products.products-qr')->name('dashboard.products-qr');
    Route::livewire('/dashboard/users', 'dashboard.users.create-users')->name('dashboard.users');
    Route::livewire('/dashboard/profile', 'dashboard.profile.edit-profile')->name('profile.edit');
    Route::delete('/logout', [LogoutController::class, 'destroy'])->name('logout');

});

Route::livewire('/dashboard/employees', 'dashboard.employees')->name('dashboard.employees')->middleware('admin');
