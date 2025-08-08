<?php

use App\Http\Controllers\TakeHomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/reset', [TakeHomeController::class, 'reset'])->name('reset');
Route::get('/balance', [TakeHomeController::class, 'getBalance'])->name('balance');
Route::post('/event', [TakeHomeController::class, 'postEvent'])->name('event');
