<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;


Route::middleware([ApiKeyMiddleware::class])->group(function () {
    Route::get('/organizations/{id}', [OrganizationController::class, 'show'])
        ->where('id', '[0-9]+');
    Route::get('/organizations/building/{buildingId}', [OrganizationController::class, 'getByBuilding']);
    Route::get('/organizations/activity/{activityId}', [OrganizationController::class, 'getByActivity']);
    Route::get('/organizations/search', [OrganizationController::class, 'searchByName']);
    Route::get('/organizations/search/activity/{activityId}', [OrganizationController::class, 'searchByActivityTree']);
    Route::get('/organizations/near', [OrganizationController::class, 'near']);

});
