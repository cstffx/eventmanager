<?php

use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;

// Public routes.
Route::get('/events/daterange', [EventsController::class, "daterange"])
    ->name('events.get.daterange');

Route::get('/events', [EventsController::class, "index"])
    ->name('events.index');

Route::get('/events/{event}', [EventsController::class, "show"])
    ->name('events.show');

Route::post('/events/{id}/reservation', [EventsController::class, "storeReservation"])
    ->name('events.post.reservation');

Route::get('/events/{id}/reservations', [EventsController::class, "reservations"])
    ->name('events.get.reservations');

Route::get('/events/{id}/availability', [EventsController::class, "availability"])
    ->name('events.get.reservations');

// Admin-only routes.
Route::patch('/events/{event}', [EventsController::class, "update"])
    ->name('events.update')
    ->middleware(['auth:sanctum', 'abilities:is-admin']);

Route::post('/event', [EventsController::class, "store"])
    ->name('events.store')
    ->middleware(['auth:sanctum', 'abilities:is-admin']);

Route::delete('/events/{id}', [EventsController::class, "destroy"])
    ->name('events.destroy')
    ->middleware(['auth:sanctum', 'abilities:is-admin']);
