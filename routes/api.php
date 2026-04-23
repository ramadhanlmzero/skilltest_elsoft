<?php

use Illuminate\Support\Facades\Route;

Route::prefix('portal/api')->group(function () {
    require __DIR__.'/portal/auth.php';
});

Route::prefix('admin/api')->group(function () {
    require __DIR__.'/admin/item.php';
    require __DIR__.'/admin/stockissue.php';
});
