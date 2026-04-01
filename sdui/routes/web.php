<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UiBlockController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [UiBlockController::class, 'welcome']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/blocks', [UiBlockController::class, 'index'])->name('blocks.index');
    Route::post('/blocks', [UiBlockController::class, 'store'])->name('blocks.store');
    Route::patch('/blocks/{block}', [UiBlockController::class, 'update'])->name('blocks.update');
    Route::delete('/blocks/{block}', [UiBlockController::class, 'destroy'])->name('blocks.destroy');
    Route::post('/blocks/reorder', [UiBlockController::class, 'reorder'])->name('blocks.reorder');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
