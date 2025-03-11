<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('/cars', [CarController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::put('/bookings/{id}/status', [BookingController::class, 'updateBookingStatus']);
