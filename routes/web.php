<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LetterOfCreditController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReceiptController;
use App\Http\Controllers\OrderReturnController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TyreController;
use App\Http\Controllers\WasteController;
use App\Http\Resources\DetailedStockResource;
use App\Http\Resources\StockResource;
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

    Route::get('/bank-accounts', [BankAccountController::class, 'index'])->name('bank_accounts.index');

    Route::get('/lcs/create', [LetterOfCreditController::class, 'create'])->name('lcs.create');
    Route::get('/lcs', [LetterOfCreditController::class, 'index'])->name('lcs.index');
    Route::get('/lcs/{lc}', [LetterOfCreditController::class, 'show'])->name('lcs.show');
    Route::post('/lcs', [LetterOfCreditController::class, 'store'])->name('lcs.store');
    
    Route::get('/tyres', [TyreController::class, 'index'])->name('tyres.index');
    Route::post('/tyres', [TyreController::class, 'store'])->name('tyres.store');

    Route::get('/consignments/create', [ConsignmentController::class, 'create'])->name('consignments.create');
    Route::get('/consignments', [ConsignmentController::class, 'index'])->name('consignments.index');
    Route::post('/consignments', [ConsignmentController::class, 'store'])->name('consignments.store');
    Route::get('/consignments/{consignment}', [ConsignmentController::class, 'show'])->name('consignments.show');
    
    Route::get('/containers', [ContainerController::class, 'index'])->name('containers.index');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/returns/create', [OrderReturnController::class, 'create'])->name('orders.returns.create');
    Route::post('/orders/{order}/returns', [OrderReturnController::class, 'store'])->name('orders.returns.store');
    
    
    Route::get('/orders/{order}/receipt', OrderReceiptController::class)->name('orders.receipt');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/stock', StockController::class)->name('stock.index');

    Route::get('/waste/create', [WasteController::class, 'create'])->name('waste.create');
    Route::get('/waste', [WasteController::class, 'index'])->name('waste.index');
    Route::post('/waste', [WasteController::class, 'store'])->name('waste.store');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
