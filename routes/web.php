<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController; // ★WeatherControllerを読み込む
use Illuminate\Support\Facades\Route;

Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/favorites', [WeatherController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [WeatherController::class, 'destroy'])->name('favorites.destroy');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';