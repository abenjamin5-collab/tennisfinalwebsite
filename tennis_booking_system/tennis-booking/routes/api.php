

<?php

use App\Http\Controllers\BookLessonController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StripeCheckoutController;
use App\Models\BookLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('/coaches', [CoachController::class, 'getCoaches'])->middleware('auth:sanctum');
Route::post('/booking', [BookLessonController::class, 'store'])->middleware('auth:sanctum');
Route::get('/booked-lessons', [BookLessonController::class, 'getBookedSessions'])->middleware('auth:sanctum');
Route::get('/coach-past-students', [CoachController::class, 'getCoachPastStudents'])->middleware('auth:sanctum');
Route::get('/coach/booked-lessons', [BookLessonController::class, 'getBookedCoachSessions'])->middleware('auth:sanctum');

Route::post('/checkout/lesson', [StripeCheckoutController::class, 'stripeCheckout'])->middleware('auth:sanctum');



Route::post('/payment-success', [StripeCheckoutController::class, 'handleSuccess'])->middleware('auth:sanctum');
