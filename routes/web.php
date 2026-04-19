<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dj', [DashboardController::class, 'dj'])->middleware('auth');

Route::get('/play/{id}', [\App\Http\Controllers\DashboardController::class, 'play'])
    ->middleware('auth');

Route::get('/playlist', [\App\Http\Controllers\PlaylistController::class, 'index'])
    ->middleware('auth');

Route::post('/playlist', [\App\Http\Controllers\PlaylistController::class, 'store'])
    ->middleware('auth');

Route::get('/playlist/{id}', [\App\Http\Controllers\PlaylistController::class, 'show'])
    ->middleware('auth');

Route::post('/playlist/add-song', [\App\Http\Controllers\PlaylistController::class, 'addSong'])
    ->middleware('auth');
Route::get('/audio/{path}', [App\Http\Controllers\AudioController::class, 'stream'])
    ->where('path', '.*')
    ->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';