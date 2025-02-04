<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\Container_content;
use App\Models\Tyre;
use App\Models\Consignment_container;
use App\Models\Lc;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConsignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consignments = DB::select('
          SELECT D.*, C.total_bought, C.total_sold, ((C.total_bought - C.total_sold)/C.total_bought * 100) AS percentage 
          FROM consignments D, (SELECT A.BOL, total_bought, IFNULL(total_sold, 0) AS total_sold 
                                FROM (SELECT BOL, SUM(qty) AS total_bought FROM container_contents GROUP BY BOL) AS A 
                                      LEFT JOIN 
                                     (SELECT bol, SUM(qty) AS total_sold FROM order_contents GROUP BY bol) AS B 
                                     ON A.BOL = B.bol
                                ) AS C 
          WHERE C.BOL = D.BOL

        ');
        
        return view('consignments', compact('consignments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Pass a blank lc_num so we cant re-use the same code as below.
        $lc_num="";

        $lcs = Lc::orderBy('created_at', 'desc')->get();
        $tyres = Tyre::all();

        foreach($tyres as $tyre)
        {
          $tyre->qty = 0;
          $tyre->unit_price = 0;
          $tyre->total_tax = 0;
          $tyre->total_weight = 0;
        }

        return view ('new_consignment', compact('lc_num', 'tyres', 'lcs'));
    }

    public function createGivenLC($lc_num)
    {
      return view('new_consignment', compact('lc_num'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      //VALIDATE
//      $validator=Validator::make($request->all(),[
//        'inputBOL' => 'required|alpha_num',
//        'inputLCnum' => 'required|numeric|digits:12',
//        'inputValue' => 'required|numeric|min:0',
//        'inputExchangeRate' => 'required|numeric|min:0',
//        'inputTax' => 'required|numeric|min:0',
//        'inputLandDate' => 'required|date',
//      ]);
//
//      if($validator->fails())
//      {
//        return redirect('consignments/create')
//                ->withErrors($validator)
//                ->withInput();
//      }
//
//      else
//      {
        //ALLOCATE
        $consignment = new Consignment;


        //INITIALIZE
        $d1 = explode( "/",$request->land_date);
        $date1 = Carbon::create($d1[2],$d1[1],$d1[0]);

        $consignment->BOL = $request->bol;
        $consignment->lc = $request->lc_num;
        $consignment->value = $request->value;
        $consignment->exchange_rate = $request->exchange_rate;
        $consignment->tax = $request->tax;
        $consignment->land_date = $date1;


        //STORE
        $consignment->save();
        $containers = array();
        foreach($request->containers  as $container)
        {
          $acontainer = new Consignment_container;
          //$acontainer[] = ['Container_num' => $container['container_num']];

          $acontainer->Container_num = $container['container_num'];
          array_push($containers, $acontainer);
//            $containers[] = ['Container_num' => $container->container_num];
        }

        $consignment->containers()->saveMany($containers);



        $containers = $consignment->containers()->get();

        foreach($containers as $container)
        {


          foreach($request->containers as $acontainer)
          {
            if( $acontainer['container_num'] == $container->Container_num )
            {

              $contents = array();

              foreach($acontainer['contents'] as $acontent)
              {
                $content_model = new Container_content;
                $content_model->BOL = $consignment->BOL;
                $content_model->tyre_id = $acontent['tyre_id'];
                $content_model->qty = $acontent['qty'];
                $content_model->unit_price = $acontent['unit_price'];
                $content_model->total_tax = $acontent['total_tax'];
                $content_model->total_weight = $acontent['total_weight'];

                array_push($contents, $content_model);
              }

              $container->contents()->saveMany($contents);
            }
          }
        }

      $response = array();
        $response['containers'] = $containers;
        $response['status'] = 'success';

        return $response;

//      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Consignment  $consignment
     * @return \Illuminate\Http\Response
     */
    public function show(Consignment $consignment)
    {


          $containers = $consignment->containers()
                                    ->with('contents.tyre')
                                    ->get();
          //array_push($containers, $somecontainers);

          $expenses = $consignment->expenses()
                                  ->get();

          $total = 0;
          $foreign_total = 0;
          $local_total = 0;
          foreach($expenses as $expense)
          {
            $foreign_total+= floatval($expense->expense_foreign);
            $local_total+= floatval($expense->expense_local);
            $total+= (floatval($expense->expense_foreign)* floatval($consignment->exchange_rate)+ floatval($expense->expense_local));

          }


          $consignment->expense_grand_total = $total;
          $consignment->expense_foreign_total = $foreign_total;
          $consignment->expense_local_total = $local_total;

          $currency = $consignment->letterOfCredit->currency_code;

          $sold = $this->soldCount($consignment);


          foreach($containers as $container)
          {
            foreach($sold as $sold_container_num => $sold_contents)
            {
              if($sold_container_num == $container->Container_num){
                foreach($sold_contents  as $sold_content)
                {
                  $total_pcs = intval($sold_content->total_pcs);

                  foreach($container->contents as $content)
                  {
                    if($sold_content->tyre_id == $content->tyre_id)
                    {
                      if($total_pcs >= $content->qty)
                      {
                        $content->sold = $content->qty;
                        $total_pcs-= $content->qty;
                        $container->sold = true;
                      }
                      else{
                        $content->sold = $total_pcs;
                        $total_pcs=0;
                        $container->sold = true;
                      }

                      if($total_pcs==0)
                        break;
                    }
                  }
                }
              }
            }
          }

          foreach($containers as $container)
            foreach($container->contents as $content)
            {
              if(!isset($content->sold))
                $content->sold = 0;

              $content->qty_minus_sold = $content->qty - $content->sold;
            }



          //foreach()

          $waste = $consignment->waste;

          foreach($waste as $awaste)
          {
            $waste_qty = $awaste->qty;
            foreach($containers as $container)
            {
              if($awaste->Container_num == $container->Container_num)
                foreach($container->contents as $content)
                  if($awaste->tyre_id == $content->tyre_id)
                  {
                    if(isset($content->remain))
                    {
                      if($waste_qty > $content->remain && $content->remain!=0)
                      {
                        $waste_qty-= $content->remain;
                        $content->remain = 0;
                        $container->waste = true;
                      }
                      elseif($content->remain!=0){
                        $content->remain = $content->remain - $waste_qty;
                        $waste_qty = 0;
                        $container->waste = true;
                      }
                    }
                    else {
                      if ($waste_qty > $content->qty_minus_sold) {
                        $content->remain=0;
                        $waste_qty-=$content->qty_minus_sold;
                        $container->waste = true;
                      } else {
                        $content->remain=$content->qty_minus_sold-$waste_qty;
                        $waste_qty=0;
                        $container->waste = true;
                      }
                    }

                    if($waste_qty == 0) // optimization
                      break;
                  }


              if($waste_qty == 0) //optimization
                break;
            }
          }

          foreach($containers as $container)
            foreach($container->contents as $content)
              if(!isset($content->remain))
                $content->remain = $content->qty_minus_sold;

          return view('profiles.consignment', compact('consignment', 'containers', 'expenses', 'currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Consignment  $consignment
     * @return \Illuminate\Http\Response
     */
    public function edit(Consignment $consignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Consignment  $consignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consignment $consignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Consignment  $consignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consignment $consignment)
    {
        //
    }

    public function soldCount(Consignment $consignment){

      $ret = DB::table('order_contents')
              ->where('bol', $consignment->BOL)
              ->join('tyres', 'tyres.tyre_id','=','order_contents.tyre_id')
              ->select('tyres.tyre_id','brand','size','pattern','lisi' ,'container_num', 'bol', DB::raw('SUM(qty) AS total_pcs'))
              ->groupBy('tyres.tyre_id', 'container_num', 'bol')
              ->get();

      return $ret->groupBy('container_num');
      
    }
}
