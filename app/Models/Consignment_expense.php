<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Consignment_expense extends Model
{

    public $primaryKey = 'expenseid';

    //HELPER FUNCTIONS
    public static function expensesInMonth($month, $year)
    {
      $expenses = DB::table('consignment_expenses')
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->get();
      return $expenses;
    }

    public static function expensesInQuarter($quarter, $year)
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

      $expenses = DB::table('consignment_expenses')
              ->whereBetween('created_at',[$start_date, $end_date])
              ->get();
      return $expenses;
    }


    public static function expensesInYear($year)
    {
      $expenses = DB::table('consignment_expenses')
                ->whereYear('created_at', '=', $year)
                ->get();
      return $expenses;
    }
}
