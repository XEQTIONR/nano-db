<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends Model
{
    //
    public $primaryKey = 'Order_num';


    public function orderContents()
    {
      return $this->hasMany(Order_content::class, 'Order_num');
    }
    public function payments()
    {
      return $this->hasMany(Payment::class,'Order_num');
    }

    public function orderReturns()
    {
      return $this->hasMany(Order_return::class, 'order_num');
    }

    public function customer()
    {
      return $this->belongsTo(Customer::class,'customer_id');
    }

    public function totalDiscount()
    {
      $discount_percent = ($this->totalValueBeforeDiscountAndTax() * ($this->discount_percent/100.0));
      return $discount_percent + $this->discount_amount;
    }

    public function totalTax()
    {
      $tax_percent_amt = ($this->totalValueBeforeDiscountAndTax() * ($this->tax_percentage/100.0));
      return $tax_percent_amt + $this->tax_amount;

    }

    //Make sure to query Order::with('payments')
    // Inefficiant query
    public function calculatePayable()
    {
        $total = ($this->totalValueBeforeDiscountAndTax()+$this->totalTax()-$this->totalDiscount());
        $payments = $this->payments; // because ALWAYS expected to query with payments.
        foreach ($payments as $payment)
        {
          $total -= ($payment->payment_amount - $payment->return_amount);
        }

        return $total;
    }

    /* Total value before tax and discount */
    public function totalValueBeforeDiscountAndTax()
    {
        $total_value=0;
        $contents = $this->orderContents()
                        ->get();
        foreach ($contents as $content)
        {

          $total_value+= ($content->qty * $content->unit_price);
        }

        return $total_value;
    }



//HELPER FUNCTIONS

    public static function ordersInMonth($month, $year)
    {
      $orders = DB::table('orders')
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->get();
      return $orders;
    }

    public static function ordersInQuarter($quarter, $year)
    {
      $startmonth="";
      $endmonth="";
      switch ($quarter) {
        case 1:
          $startmonth="January";
          $endmonth="March";
        break;

        case 2:
          $startmonth="April";
          $endmonth="June";
        break;

        case 3:
          $startmonth="July";
          $endmonth="September";
        break;

        case 4:
        $startmonth="October";
        $endmonth="December";
        break;
      }

      $start_date = new Carbon("First day of ". $startmonth. " ".$year);
      $end_date = new Carbon ("Last day of ". $endmonth. " ".$year);
      $end_date = $end_date->addHours(23);
      $end_date = $end_date->addMinutes(59);
      $end_date = $end_date->addSeconds(59);
    /*
                ->whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '>', 3*$quarter-1);

      $orders = $orders->whereMonth('created_at', '<=', 3*$quarter)
                ->get();*/

      $orders = DB::table('orders')
              ->whereBetween('created_at',[$start_date, $end_date])
              ->get();
      return $orders;
    }


    public static function ordersInYear($year)
    {
      $orders = DB::table('orders')
                ->whereYear('created_at', '=', $year)
                ->get();
      return $orders;
    }
}
