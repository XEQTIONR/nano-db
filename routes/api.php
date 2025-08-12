<?php

use App\Http\Controllers\Api\ConsignmentController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LetterOfCreditController;
use App\Http\Controllers\Api\OrderController;
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
    
    Route::get('/consignments', [ConsignmentController::class, 'index'])
        ->name('consignments.index');
});


