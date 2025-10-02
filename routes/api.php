<?php

use App\Http\Controllers\Api\StockPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('stocks')->group(function () {
    Route::get('/{companyId}/historical', [StockPriceController::class, 'historical']);
    Route::get('/{companyId}/custom', [StockPriceController::class, 'custom']);
});