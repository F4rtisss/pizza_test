<?php

use App\Foundation\Route;
use App\Middleware\Authorization;
use App\Controllers\API\V1\OrderController;

Route::get('/api/v1/orders', OrderController::class, 'all', [Authorization::class]);
Route::post('/api/v1/orders', OrderController::class, 'store');
Route::get('/api/v1/orders/{id}', OrderController::class, 'show');
Route::post('/api/v1/orders/{id}/items', OrderController::class, 'push');
Route::post('/api/v1/orders/{id}/done', OrderController::class, 'isDone', [Authorization::class]);