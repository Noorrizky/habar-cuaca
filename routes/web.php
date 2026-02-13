<?php
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

// Route tunggal untuk handle tampilan dan search
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');
