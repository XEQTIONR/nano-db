<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Payment extends Model
{
    //

    protected $appends = ['amount'];

    public $primaryKey = 'transaction_id';

    public static $paymentTypes = [
      'unknown' => 'Unknown',
      'cash'    => 'Cash',
      'check'   => 'Check',
      'deposit' => 'Bank Deposit',
      'commission' => 'Commission',
    ];

    public function order()
    {
      return $this->belongsTo(Order::class,'Order_num');
    }

    public function bankAccount()
    {
      return $this->belongsTo(BankAccount::class,'account');
    }

    public function getAmountAttribute()
    {
      return $this->payment_amount - $this->refund_amount;
    }

    public function getPaymentOnAttribute()
    {
      return $this->created_at->format('d/m/Y');
    }

    public static function paymentsInMonth($month, $year)
    {
      $payments = DB::table('payments')
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->get();
      return $payments;
    }

    public static function paymentsInYear($year)
    {
      $payments = DB::table('payments')
                ->whereYear('created_at', '=', $year)
                ->get();
      return $payments;
    }

    public static function PaymentsInQuarter($quarter, $year)
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

      $payments = DB::table('payments')
              ->whereBetween('created_at',[$start_date, $end_date])
              ->get();
      return $payments;
    }
}
