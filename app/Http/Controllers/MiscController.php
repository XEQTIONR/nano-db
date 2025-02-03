<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use DB;

class MiscController extends Controller
{
    //

    public function welcome()
    {
      $highest_owing_customers = collect(DB::select(resolve('CustomersOwingSQL'). 'LIMIT 5'));

      $all_orders = collect(DB::select(resolve('OrdersSummarySQL')));

      $highest_no_payments =  $all_orders
        ->filter(function($item) { return floatval($item->payments_total) == 0; })
        ->sortByDesc(function($item) { return floatval($item->grand_total);})
        ->values()
        ->take(5);

      $newest_owing_orders = $all_orders
        ->filter(function($item) { return (floatval($item->grand_total) - floatval($item->payments_total)) > 0; })
        ->sortByDesc('created_at')
        ->values()
        ->take(5);


      return view('welcome',
        ['now' => \Carbon\Carbon::now(),
          'highest_owing_customers' => $highest_owing_customers,
          'highest_no_payments' => $highest_no_payments,
          'newest_owing_orders' => $newest_owing_orders]);
    }
}
