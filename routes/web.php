<?php

use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LetterOfCreditController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TyreController;
use App\Http\Resources\StockResource;
use App\Models\ContainerContent;
use App\Models\Order;
use App\Models\OrderContent;
use App\Models\Payment;
use App\Models\Tyre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/lcs', [LetterOfCreditController::class, 'index'])->name('lcs.index');
    Route::get('/lcs/create', [LetterOfCreditController::class, 'create'])->name('lcs.create');
    Route::get('/lcs/{lc}', [LetterOfCreditController::class, 'show'])->name('lcs.show');
    Route::post('/lcs', [LetterOfCreditController::class, 'store'])->name('lcs.store');
    
    Route::get('/tyres', [TyreController::class, 'index'])->name('tyres.index');

    Route::get('/consignments', [ConsignmentController::class, 'index'])->name('consignments.index');
    
    Route::get('/containers', [ContainerController::class, 'index'])->name('containers.index');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    Route::get('/stock', StockController::class)->name('stock.index');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
