<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;

// Public home page
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
require __DIR__.'/auth.php';

// Smart redirect after login
Route::middleware('auth')->get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('staff.dashboard');
})->name('dashboard');

// ─── Customer routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    // Customer can cancel their own pending orders only
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::get('/my-activity', [AuditLogController::class, 'userHistory'])
        ->name('audit.user-history');
});

// ─── Staff routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:branch_manager,sales_rep,dispatcher,technician'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
        ->name('staff.dashboard');

    Route::resource('inventory', InventoryController::class);

    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    // Staff can force-delete any order regardless of status
    Route::delete('/orders/{order}/force', [OrderController::class, 'forceDestroy'])->name('orders.force-destroy');

    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit.export');
    Route::get('/audit-logs/statistics', [AuditLogController::class, 'statistics'])->name('audit.statistics');
    Route::get('/audit-logs/model/{modelType}/{modelId}', [AuditLogController::class, 'modelHistory'])->name('audit.model-history');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit.show');
});

// ─── Shared routes ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
});