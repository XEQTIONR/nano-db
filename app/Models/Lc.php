<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Lc extends Model
{
    //

    public $primaryKey = 'lc_num';
    public $incrementing = false;
    //protected $casts = ['lc_num' => 'string'];

    public function performaInvoice()
    {
      return $this->hasMany(Performa_invoice::class,'lc_num');
    }

    public function consignment()
    {
      return $this->hasMany(Consignment::class, 'lc');
    }


    //HELPER FUNCTIONS
    public static function lcsInMonth($month, $year)
    {
      $lcs = DB::table('lcs')
                ->whereMonth('date_issued', '=', $month)
                ->whereYear('date_issued', '=', $year)
                ->get();
      return $lcs;
    }

    public static function lcsInQuarter($quarter, $year)
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
      //$end_date = $end_date->addHours(23);
      //$end_date = $end_date->addMinutes(59);
      //$end_date = $end_date->addSeconds(59);

      $lcs = DB::table('lcs')
              ->whereBetween('date_issued',[$start_date, $end_date])
              ->get();
      return $lcs;
    }


    public static function lcsInYear($year)
    {
      $lcs = DB::table('lcs')
                ->whereYear('date_issued', '=', $year)
                ->get();
      return $lcs;
      //return $year;
    }
}
?>
