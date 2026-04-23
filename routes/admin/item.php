<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('item')
    ->controller(ItemController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', 'list');
        Route::post('/', 'create');
        Route::post('/save', 'save');
        Route::delete('/delete', 'delete');
    });
