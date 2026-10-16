<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// Authentication Routes
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products (Admin & Partner)
    Route::resource('products', ProductController::class);
    Route::get('products/{id}/details-json', [ProductController::class, 'detailsJson'])->name('products.detailsJson');

    // Customers (All)
    Route::resource('customers', CustomerController::class);

    // Inventory
    Route::get('inventory/intake', [InventoryController::class, 'intake'])->name('inventory.intake');
    Route::post('inventory/intake', [InventoryController::class, 'storeIntake'])->name('inventory.storeIntake');
    Route::get('inventory/check', [InventoryController::class, 'check'])->name('inventory.check');
    Route::get('inventory/intake/{id}/json', [InventoryController::class, 'getIntakeJson'])->name('inventory.getIntakeJson');
    Route::put('inventory/intake/{id}', [InventoryController::class, 'updateIntake'])->name('inventory.updateIntake');

    // Sales
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/history', [SaleController::class, 'history'])->name('sales.history');
    Route::get('sales/{id}/details-json', [SaleController::class, 'detailsJson'])->name('sales.detailsJson');
    Route::get('sales/{id}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.generateInvoice');
    Route::get('sales/{id}/email', [SaleController::class, 'sendInvoiceEmail'])->name('sales.sendInvoiceEmail');

    // Reports (Admin)
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');

    // Users (Admin)
    Route::resource('users', UserController::class)->except(['show']);
});
