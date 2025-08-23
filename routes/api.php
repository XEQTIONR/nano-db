<?php

use App\Http\Controllers\Api\ConsignmentController;
use App\Http\Controllers\Api\ContainerController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LetterOfCreditController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\TyreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/lcs', [LetterOfCreditController::class, 'index'])
        ->name('lcs.index');

    Route::post('/lcs', [LetterOfCreditController::class, 'store'])
        ->name('lcs.store');
    
    Route::get('/consignments', [ConsignmentController::class, 'index'])
        ->name('consignments.index');

    Route::get('/containers', [ContainerController::class, 'index'])
        ->name('containers.index');

    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payments.index');

    Route::get('/tyres', [TyreController::class, 'index'])
        ->name('tyres.index');

    Route::get('/stock', StockController::class)
        ->name('stock.index');
});


