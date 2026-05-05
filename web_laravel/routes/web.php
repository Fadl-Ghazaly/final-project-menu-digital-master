<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');

// Shared Admin & Cashier Routes
Route::middleware(['auth', 'role:admin,kasir'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    
    // Allow cashier to see products/categories but maybe not edit? 
    // For now, let's allow access but we'll hide buttons in UI.
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('admin.products.toggle'); 
});

// Admin ONLY Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Full CRUD for Admin
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    
    // Tables & QR
    Route::resource('tables', TableController::class);
    Route::post('/tables/{table}/toggle-status', [TableController::class, 'toggleStatus'])->name('admin.tables.toggle');
    // Promos
    Route::resource('promos', PromoController::class);
    Route::post('/promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus'])->name('admin.promos.toggle');
    
    // Reports
    Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('admin.reports.export.csv');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export.pdf');
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');

    // Branding & Settings
    Route::get('/branding', [SettingController::class, 'branding'])->name('admin.branding');
    Route::post('/branding', [SettingController::class, 'updateBranding'])->name('admin.branding.update');
    Route::get('/settings', [SettingController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [SettingController::class, 'updateSettings'])->name('admin.settings.update');
});
