<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ServicesController as AdminServicesController;
use App\Http\Controllers\Api\Customer\ServicesController;
use App\Http\Controllers\Api\Customer\BookingController;

use App\Http\Controllers\Api\Admin\BookingsController as BackendBookingsController;


use App\Http\Controllers\Api\Admin\BusinessHoursController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/services', [ServicesController::class, 'getAllServices']);
Route::get('/popular-services', [ServicesController::class, 'getPopularServices']);
Route::get('services/{service}', [ServicesController::class, 'show']); // Fetch 

Route::get('/available-slots', [BookingController::class, 'getAvailableSlots']);
Route::post('/book', [BookingController::class, 'createBooking']);


Route::prefix('admin')->group(function () {

    Route::apiResource('business-hours', BusinessHoursController::class);

        Route::apiResource('services', AdminServicesController::class);

         Route::put('/services/{serviceId}/toggle-featured', [AdminServicesController::class, 'toggleFeatured']);


Route::apiResource('bookings', BackendBookingsController::class);


});