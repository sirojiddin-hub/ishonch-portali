<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppealController;
use App\Http\Controllers\AdminController;

// --- FOYDALANUVCHI QISMI ---
Route::get('/', function () {
    $categories = \App\Models\Category::where('is_active', true)->get();
    return view('welcome', compact('categories'));
})->name('home');

Route::post('/appeal/send', [AppealController::class, 'store'])->name('appeal.store');
Route::post('/appeal/check', [AppealController::class, 'checkStatus'])->name('appeal.check');


// --- ADMIN QISMI ---
Route::prefix('admin')->group(function () {
    
    // Mehmonlar uchun (Login sahifasi)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'loginPage'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'login'])->name('admin.auth');
    });

    // Faqat adminlar uchun (Himoyalangan)
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // 1. Asosiy sahifalar
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/appeals', [AdminController::class, 'appeals'])->name('admin.appeals'); // Arizalar
        
        // 2. Ariza ichi va tahrirlash
        Route::get('/appeal/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::post('/appeal/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::post('/appeal/{id}/assign', [AdminController::class, 'assign'])->name('admin.assign'); // Biriktirish
        Route::get('/appeal/{id}/print', [AdminController::class, 'printAppeal'])->name('admin.print');

        // 3. Kategoriyalar
        Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

        // 4. Statistika
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

        // 5. MAS'UL XODIMLAR (Siz so'ragan qism)
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });
    Route::get('/archive', [AdminController::class, 'archive'])->name('admin.archive');
Route::get('/archive/{year}', [AdminController::class, 'archiveShow'])->name('admin.archive.show');
});