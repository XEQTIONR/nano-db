<?php

use Illuminate\Support\Facades\Route;


Route::post('/lcs/check', 'LcController@checkLCNumber')->name('lcs.check');

Route::get('order', 'OrderController@detailsRow');

Route::get('consignment_container', 'ConsignmentContainerController@detailsRow');