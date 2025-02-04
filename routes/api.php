<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])
    ->name('api.')
    ->prefix('api')
    ->group(function() {
    Route::post('/lcs/check', 'LcController@checkLCNumber')->name('lcs.check');
    Route::post('/customers', 'CustomerController@apiShow')->name('customers');
    Route::post('/customer-update', 'CustomerController@apiUpdate')->name('customers.update');
});