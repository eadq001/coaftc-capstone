<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Products;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::delete('/logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::livewire('/', Login::class);
    Route::livewire('/login', Login::class)->name('login');
    Route::livewire('/register', Register::class)->name('register');
    Route::livewire('/verification', 'auth.register-confirm-email')->name('verification.verify');
    Route::livewire('/password-reset', 'auth.password-reset')->name('password.reset');
});

Route::middleware(['auth', 'role:admin,cashier,inventory_clerk'])->prefix('/dashboard')->group(function () {
    Route::livewire('/sales', 'dashboard.sales.add-sales')->name('dashboard.sales');
    Route::livewire('/products/qr', 'dashboard.products.products-qr')->name('dashboard.products-qr');
    Route::livewire('/reports', 'dashboard.reports')->name('dashboard.reports');

});

Route::middleware(['auth', 'role:admin'])->prefix('/dashboard')->group(function () {
    Route::livewire('/users', 'dashboard.users.create-users')->name('dashboard.users');
    Route::livewire('/employees', 'dashboard.employees')->name('dashboard.employees');
    Route::livewire('/archived-products', 'dashboard.archives.archive-products')->name('dashboard.archived-products');
    Route::livewire('/backup-and-restore', 'dashboard.backup-and-restore')->name('dashboard.backup-and-restore');
    Route::livewire('/logs', 'dashboard.logs')->name('dashboard.logs');
});


Route::middleware(['auth', 'role:admin,inventory_clerk'])->prefix('/dashboard')->group(function () {
    Route::livewire('/products', Products::class)->name('dashboard.products');
});

Route::middleware(['auth', 'role:admin,cashier'])->prefix('/dashboard')->group(function () {
    Route::livewire('/sales', 'dashboard.sales.add-sales')->name('dashboard.sales');
});

Route::middleware(['auth', 'role:admin,inventory_clerk,cashier'])->prefix('/dashboard')->group(function () {
    Route::livewire('/', Home::class)->name('dashboard.home');
    Route::livewire('/profile', 'dashboard.profile.edit-profile')->name('profile.edit');
});

