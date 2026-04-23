<?php

use App\Http\Controllers\StockIssueController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/stockissue')
    ->controller(StockIssueController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/list', 'list');
        Route::post('/', 'create');
        Route::get('/{oid}', 'get')->whereUuid('oid');
        Route::post('/{oid}', 'save')->whereUuid('oid');
        Route::delete('/{oid}', 'delete')->whereUuid('oid');

        Route::prefix('/detail')
            ->group(function () {
                Route::get('/{oid}', 'getDetail')->whereUuid('oid');
                Route::post('/', 'createDetail');
                Route::post('/{oid}', 'saveDetail')->whereUuid('oid');
                Route::delete('/{oid}', 'deleteDetail')->whereUuid('oid');
            });
    });
