<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Home;
use App\Livewire\Dashboard\Products;
use App\Livewire\Dashboard\Sales\VoidSales;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

Route::delete('/logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware('guest')->prefix('coaftc-sys')->group(function () {
    Route::livewire('/', Login::class);
    Route::livewire('/login', Login::class)->name('login');
    Route::livewire('/register', Register::class)->name('register');
    Route::livewire('/verification', 'auth.register-confirm-email')->name('verification.verify');
    Route::livewire('/password-reset', 'auth.password-reset')->name('password.reset');
    Route::get('/session-expired', function () {
        return view('auth.session-expired');
    })->name('session.expired');
});

Route::middleware(['auth', 'role:admin,cashier,inventory_clerk'])->prefix('coaftc-sys/dashboard')->group(function () {
    Route::livewire('/sales', 'dashboard.sales.add-sales')->name('dashboard.sales');
    Route::livewire('/products/qr', 'dashboard.products.products-qr')->name('dashboard.products-qr');
    Route::livewire('/reports', 'dashboard.reports')->name('dashboard.reports');

});

Route::middleware(['auth', 'role:admin'])->prefix('coaftc-sys/dashboard')->group(function () {
    Route::livewire('/users', 'dashboard.users.create-users')->name('dashboard.users');
    Route::livewire('/employees', 'dashboard.employees')->name('dashboard.employees');
    Route::livewire('/archived-products', 'dashboard.archives.archive-products')->name('dashboard.archived-products');
    Route::livewire('/backup-and-restore', 'dashboard.backup-and-restore')->name('dashboard.backup-and-restore');
    Route::livewire('/logs', 'dashboard.logs')->name('dashboard.logs');
});

Route::middleware(['auth', 'role:admin,inventory_clerk'])->prefix('coaftc-sys/dashboard')->group(function () {
    Route::livewire('/products', Products::class)->name('dashboard.products');
});

Route::middleware(['auth', 'role:admin,cashier'])->prefix('coaftc-sys/dashboard')->group(function () {
    Route::livewire('/sales', 'dashboard.sales.add-sales')->name('dashboard.sales');
    Route::livewire('/reports', 'dashboard.reports')->name('dashboard.reports');
    Route::livewire('/lgu-support', 'dashboard.lgu.lgu-support')->name('dashboard.lgu-support');
});

Route::middleware(['auth', 'role:admin,inventory_clerk,cashier'])->prefix('coaftc-sys/dashboard')->group(function () {
    Route::livewire('/void-sales', VoidSales::class)->name('dashboard.void-sales');
    Route::livewire('/', Home::class)->name('dashboard.home');
    Route::livewire('/profile', 'dashboard.profile.edit-profile')->name('profile.edit');
});
