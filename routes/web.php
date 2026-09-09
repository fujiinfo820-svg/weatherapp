<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

// トップページにアクセスしたら WeatherController の index メソッドを実行
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');