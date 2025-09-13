<?php


use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;

Route::middleware([ApiKeyMiddleware::class])->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
});

