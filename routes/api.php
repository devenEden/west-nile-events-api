<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketPurchaseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'events'], function () {
    Route::get('my-events', [EventController::class, 'myEvents']);
    Route::post('my-events/upload-image/{event_id}', [EventController::class, 'uploadImage']);
    Route::get('my-events/purchases/{event_id}', [EventController::class, 'getEventTicketPurchases'])->name('my_events.purchases');
    Route::get('my-events/statistics/{event_id}', [EventController::class, 'getEventTicketStatistics'])->name('my_events.purchases');

    Route::apiResource('', EventController::class)->parameters(['' => 'event']);

    Route::group(['prefix' => 'tickets'], function () {
        Route::apiResource('', TicketController::class)->parameters(['' => 'ticket']);
    });
});

Route::group(['prefix' => 'tickets'], function () {
    Route::post('/purchase', [TicketPurchaseController::class, 'store'])->name('tickets.purchase');
    Route::get('/verify/{ticket_reference}', [TicketPurchaseController::class, 'verifyTicket'])->name('tickets.verify');
});

Route::group(['prefix' => 'public-events'], function () {
    Route::get('', [EventController::class, 'getPassedEvents'])->name('events.public');
    Route::get('/passed', [EventController::class, 'index'])->name('events.public');
    Route::get('/{id}', [EventController::class, 'show'])->name('events.public.show');
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/user', [AuthController::class, 'getUser'])->name('auth.user')->middleware('auth:sanctum');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
});
