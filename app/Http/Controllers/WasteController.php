<?php

namespace App\Http\Controllers;

use App\Models\Tyre;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WasteController extends Controller
{
    //

  public function create(){
    $order = resolve('TyresRemainingInContainers')->where("in_stock", ">", 0)->values();
    $tyres = Tyre::all('tyre_id', 'brand', 'size', 'lisi', 'pattern');

    foreach($order as $o)
      $o->tyre = $tyres->where('tyre_id', $o->tyre_id)->first();
    //return $order;
    $ret = $order->groupBy('BOL');

    $remain = collect();

    foreach($ret as $key => $val){
      $array = array( $key => $val->groupBy('Container_num') );
      $remain->push($array);
    }

   //return $remain;

    return view('waste', compact('remain'));
  }

  public function store(Request $request){
      //$array = array();
      $response = array();
      DB::beginTransaction();
      try {
        foreach ($request->data as $consignment)
          foreach ($consignment as $bol=>$containers)
            foreach ($containers as $container_num=>$contents)
              foreach ($contents as $content)
                if (intval($content[ 'ret_amt' ])>0) {
                  $waste=new Waste();
                  $waste->Container_num=$content[ 'Container_num' ];
                  $waste->BOL=$content[ 'BOL' ];
                  $waste->tyre_id=$content[ 'tyre_id' ];
                  $waste->qty=intval($content[ 'ret_amt' ]);

                  $waste->save();
                }
        DB::commit();
        $response['status'] = 'success';
        return $response;
      }
      catch(\Exception $e){

        $response['status'] = 'failed';
        $response['error'] = $e->getMessage();
        return $response;
      }


  }

}
