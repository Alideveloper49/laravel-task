<?php

use App\Http\Controllers\StockImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StockImportController::class,'index']);
Route::post('/import', [StockImportController::class,'store'])->name('stocks.import');
