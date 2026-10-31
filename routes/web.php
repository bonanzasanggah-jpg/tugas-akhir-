<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProfileController;

// 1. Rute Login & Logout
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk menangani pengiriman form Lupa Password (OTP) & Reset
Route::post('/forgot-password/send-otp', [LoginController::class, 'sendOtp'])->name('password.email');
Route::post('/forgot-password/reset', [LoginController::class, 'resetWithOtp'])->name('password.update.otp'); // <-- TAMBAHKAN INI

// 2. Rute Utama
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 3. Rute Fitur (Wajib Login)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Kasir
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/store', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/get-product', [KasirController::class, 'getProductData'])->name('kasir.getProduct');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/print/{kode}', [TransaksiController::class, 'print'])->name('transaksi.print')->where('kode', '.*');
    Route::get('/transaksi/{kode}', [TransaksiController::class, 'show'])->name('transaksi.show')->where('kode', '.*');
    
    // Laporan Keuangan
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Rute Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        
        Route::get('/dashboard/karyawan', [DashboardController::class, 'karyawan'])->name('dashboard.karyawan');
        
        // Admin: Kelola Profil User
        Route::get('/admin/profile/{id}', [ProfileController::class, 'editUser'])->name('admin.profile.edit');
        Route::put('/admin/profile/{id}', [ProfileController::class, 'update'])->name('admin.profile.update');

        // Transaksi (Admin only)
        Route::delete('/transaksi/{kode}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy')->where('kode', '.*');

        // Barang
        Route::get('/barang/find-sku', [ProductController::class, 'findBySku'])->name('barang.findBySku');
        Route::post('/product/add-stock/{id}', [ProductController::class, 'addStock'])->name('product.addStock');
        Route::resource('barang', ProductController::class);

        // Supplier, Kategori, Stok
        Route::resource('supplier', SupplierController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('stok', StokController::class);

        // --- MANAJEMEN USER ---
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});