<?php

use App\Foundation\Route;
use App\Middleware\Authorization;
use App\Controllers\API\V1\OrderController;

Route::get('/api/v1/orders', OrderController::class, 'all', [Authorization::class]);
Route::post('/api/v1/orders', OrderController::class, 'store');
Route::get('/api/v1/orders/{id:\d+}', OrderController::class, 'show');
Route::post('/api/v1/orders/{id:\d+}/items', OrderController::class, 'push');
Route::post('/api/v1/orders/{id:\d+}/done', OrderController::class, 'isDone', [Authorization::class]);