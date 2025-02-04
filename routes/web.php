<?php

use App\Http\Controllers\ConsignmentContainerController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\ConsignmentExpenseController;
use App\Http\Controllers\ContainerContentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HscodeController;
use App\Http\Controllers\LcController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PerformaInvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\TyreController;
use App\Http\Controllers\WasteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/', [MiscController::class, 'welcome'])->middleware('auth')->name('home');

Route::get('/admin-test', function(){

  return view('test');
});

Route::get('/users', function(){

  $users = \App\Models\User::all();

  return view('users', compact('users'));
})->name('users.index')->middleware(['auth', 'admin']);

Route::resource('tyres', TyreController::class);

Route::resource('lcs', LcController::class);

Route::get('/proforma_invoice/create', [LcController::class, 'createProformaInvoice'])->name('proforma_invoice.create');

Route::post('/proforma_invoice/store', [LcController::class, 'storeProformaInvoice'])->name('proforma_invoice.store');

//To pass the lc_num
Route::get('/consignments/create/{lc}', [LcController::class, 'createGivenLC']);

Route::resource('consignments', ConsignmentController::class);



Route::resource('customers', CustomerController::class);

Route::resource('consignment_expenses', ConsignmentExpenseController::class);

Route::resource('consignment_containers', ConsignmentContainerController::class);
Route::get('/consignment_containers/{consignment}/{container}', [ConsignmentContainerController::class, 'show']);

Route::resource('performa_invoices', PerformaInvoiceController::class);


//raw json order info
Route::get('/orders/json/{order_num}', [OrderController::class, 'showJSON']);

Route::resource('orders', OrderController::class);

Route::get('orders/{order}/receipt', [OrderController::class, 'viewReceipt'])->name('orders.receipt');;



Route::resource('returns', ReturnController::class);


Route::resource('payments', PaymentController::class);

Route::resource('hscodes', HscodeController::class);



Route::get('/container_contents/create/{bol}', [ContainerContentController::class, 'createGivenBOL']);

Route::resource('container_contents', ContainerContentController::class);



Route::resource('order_contents', OrderController::class);


Route::get('stock', function()
{
  $in_stock = collect(DB::select(resolve('TyresRemainingSQL')));
  return view('stock', compact('in_stock'));
})->name('stock');


Route::get('reports/expense', [ReportController::class, 'defaultExpenseReport']);

Route::get('reports/expense/{time_frame}/{year}', [ReportController::class, 'showExpenseReport']);

Route::get('reports/order/', [ReportController::class, 'defaultOrderReport']);

Route::get('reports/order/{time_frame}/{year}', [ReportController::class, 'showOrderReport']);


Route::get('reports/payment/', [ReportController::class, 'defaultPaymentReport']);

Route::get('reports/payment/{time_frame}/{year}', [ReportController::class, 'showPaymentReport']);


Route::get('reports/outstanding_balance', [ReportController::class, 'showOutstandingBalanceReport']);

Route::get('reports/profit', [ReportController::class, 'showProfitReport']);


Route::resource('waste', WasteController::class);

Route::get('title', function()
{
  return view('title');
});

Auth::routes();

Route::get('/home', function(){
  return redirect('/');
});

// Ajax api calls.
Route::name('api.')
  ->prefix('/api')
  ->group(function() {
    Route::post('/lcs/check', [LcController::class, 'checkLCNumber'])->name('lcs.check');
    Route::post('/customers', [CustomerController::class, 'apiShow'])->name('customers');
    Route::post('/customer-update', [CustomerController::class, 'apiUpdate'])->name('customers.update');
  });
