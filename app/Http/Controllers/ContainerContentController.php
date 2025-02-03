<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Container_content;
use App\Models\Consignment_container;
use App\Models\Tyre;
use Illuminate\Http\Request;
use Validator;

class ContainerContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $contents = Container_content::all();

        return view('container_contents', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if ($request->ajax()) {
          $tyres = Tyre::paginate(7);
          return view('partials.tyres', compact('tyres'));
        }

        else
        {
          $bol="";
          $tyres = Tyre::paginate(7);
          return view('new_container_content', compact('tyres','bol'));
        }
    }

    public function createGivenBOL($bol)
    {
      $tyres = Tyre::paginate(7);
      return view('new_container_content', compact('tyres','bol'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator=Validator::make($request->all(),[
          'inputContainerNum' => 'required|string',
          'inputBOL' => 'required|string',

        ]);

        if($validator->fails())
        {
          return redirect('container_contents/create')
                  ->withErrors($validator)
                  ->withInput();
        }

        else
        {



        $container_content_records = array();
        //$container = Consignment_container::find($request->inputContainer);

        DB::beginTransaction();

        $container = new Consignment_container;

        $container->Container_num = $request->inputContainerNum;
        $container->BOL = $request->inputBOL;

        $container->save();
        //dd($container);
        //echo $container->Container_num;
        //echo $request->inputContainer;
        //echo $container->BOL;
      //  echo "inputBOL";
        //echo $request->inputBOL;

        for ($i=0; $i<$request->runningCount; $i++)
        {

          //echo "forloop<br>"
          //echo $request->inputBOL;
          $subdiv_value = 'subDiv'.$i;
          $removed = $request->removedDivs;

          if(strpos($removed,$subdiv_value)===FALSE) //Do Only for divs not removed
          {
            $item = new Container_content;

            $item->tyre_id = $request->tyre[$i];
            $item->qty = $request->qty[$i];
            $item->unit_price = $request->price[$i];
            $item->total_tax = $request->tax[$i];
            $item->total_weight = $request->weight[$i];
            $item->Container_num = $request->inputContainerNum;
            $item->BOL = $request->inputBOL;

            array_push($container_content_records, $item);
          }
        }

        if(is_null($container_content_records))
        {
          //return redirect('/tyres');
          echo "this is null";
          //echo $invoiceRecord[0];
        }
        else
        {
          $container->containerContents()->saveMany($container_content_records);

          DB::commit();
          $base = "/consignments/";
          $url = $base . $request->inputBOL;
          //dd($url);
          return redirect($url);
          //$this.index();
        }
        //$invoiceRecord->save();
        //ONLY WORKS FOR SINGLE RECORDS
    }
  }



    /**
     * Display the specified resource.
     *
     * @param  \App\Container_contents  $container_contents
     * @return \Illuminate\Http\Response
     */
    public function show($bol)
    {
        $contents = Container_content::where('BOL',$bol)
                                      ->get();
        return $contents;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Container_contents  $container_contents
     * @return \Illuminate\Http\Response
     */
    public function edit(Container_contents $container_contents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Container_contents  $container_contents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Container_contents $container_contents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Container_contents  $container_contents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Container_contents $container_contents)
    {
        //
    }
}
