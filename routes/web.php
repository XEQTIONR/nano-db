<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'MiscController@welcome')->middleware('auth')->name('home');

Route::get('/admin-test', function(){

  return view('test');
});

Route::get('/users', function(){

  $users = \App\Models\User::all();

  return view('users', compact('users'));
})->name('users.index')->middleware(['auth', 'admin']);

Route::resource('tyres','TyreController');

Route::resource('lcs','LcController');

Route::get('/proforma_invoice/create', 'LcController@createProformaInvoice')->name('proforma_invoice.create');

Route::post('/proforma_invoice/store', 'LcController@storeProformaInvoice')->name('proforma_invoice.store');

//To pass the lc_num
Route::get('/consignments/create/{lc}', 'ConsignmentController@createGivenLC');

Route::resource('consignments','ConsignmentController');



Route::resource('customers','CustomerController');

Route::resource('consignment_expenses','ConsignmentExpenseController');

Route::resource('consignment_containers','ConsignmentContainerController');
Route::get('/consignment_containers/{consignment}/{container}', 'ConsignmentContainerController@show');

Route::resource('performa_invoices','PerformaInvoiceController');


//raw json order info
Route::get('/orders/json/{order_num}', 'OrderController@showJSON');

Route::resource('orders','OrderController');

Route::get('orders/{order}/receipt', 'OrderController@viewReceipt')->name('orders.receipt');;



Route::resource('returns', 'ReturnController');


Route::resource('payments','PaymentController');

Route::resource('hscodes','HscodeController');



Route::get('/container_contents/create/{bol}', 'ContainerContentController@createGivenBOL');

Route::resource('container_contents', 'ContainerContentController');



Route::resource('order_contents', 'OrderContentController');


Route::get('stock', function()
{
  $in_stock = collect(DB::select(resolve('TyresRemainingSQL')));
  return view('stock', compact('in_stock'));
})->name('stock');


Route::get('reports/expense', 'ReportController@defaultExpenseReport');

Route::get('reports/expense/{time_frame}/{year}', 'ReportController@showExpenseReport');

Route::get('reports/order/', 'ReportController@defaultOrderReport');

Route::get('reports/order/{time_frame}/{year}', 'ReportController@showOrderReport');


Route::get('reports/payment/', 'ReportController@defaultPaymentReport');

Route::get('reports/payment/{time_frame}/{year}', 'ReportController@showPaymentReport');


Route::get('reports/outstanding_balance', 'ReportController@showOutstandingBalanceReport');

Route::get('reports/profit', 'ReportController@showProfitReport');


Route::resource('waste', 'WasteController');


Route::get('test2', 'OrderController@btest');
Route::get('test3', 'OrderController@ctest');

//Route::get('test', function()
//{
//  $orders = App\Order::all();
//  $customers = collect();
//  //$num_customers=0;
//  $num_orders=0;
//  $total_owed=0;
//  $total_value=0;
//
//  foreach ($orders as $order)
//  {
//    $order->totalValueBeforeDiscountAndTax();
//    $order->calculateAndSetDiscount();
//    $order->calculateAndSetTax();
//    $order->calculatePayable();
//    $order->final_value = $order->subtotal + $order->totalTax - $order->totalDiscount;
//
//    if ($order->payable>0)
//    {
//
//      $customers->push($order->customer_id);
//      $num_orders++;
//      $total_owed+= $order->payable;
//      $total_value+= $order->final_value;
//    }
//    //$customer = $order->customer()->get();
//    //$order->customer_id = $order->customer()->id;
//  }
//
//  $unique = $customers->unique();
//  $num_customers = count($unique);
//  return [$orders, $customers, $unique, $total_owed, $total_value, $num_orders, $num_customers];
//});




Route::get('title', function()
{
  return view('title');
});

//Route::get('/', 'WelcomeController@show');

Auth::routes();

Route::get('/home', function(){

  return redirect('/');
});
