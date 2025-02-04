<?php

namespace App\Http\Controllers;

use App\Models\Performa_invoice;
use App\Lc;
use App\Tyre;
use Illuminate\Http\Request;

//$invoiceRecord;
//$i;

class PerformaInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $invoiceRecords = Performa_invoice::all();
        //return view('performa_invoices', compact('invoiceRecords'));
        return $invoiceRecords;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if ($request->ajax()) //the request is an ajax request
        {
          $tyres = Tyre::paginate(5);
          return view('partials.tyres', compact('tyres'));

        }
        else
        {
          $tyres = Tyre::paginate(5); //non-ajax request
          return view('new_performa_invoice', compact('tyres'));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //validate request : NOT DONE

        global $invoiceRecord, $i;
        $lc = Lc::find($request->inputLC);


        for ($i=0; $i<$request->numItems; $i++)
        {

          $invoiceRecord[$i] = new Performa_invoice;


          $invoiceRecord[$i]->tyre_id = $request->tyre[$i];
          $invoiceRecord[$i]->qty = $request->qty[$i];
          $invoiceRecord[$i]->unit_price = $request->price[$i];
        }

        if(is_null($invoiceRecord))
        {
          //return redirect('/tyres');
          echo "this is null";
          //echo $invoiceRecord[0];
        }
        else
        {
          $lc->performaInvoice()->saveMany($invoiceRecord);
          return redirect('/lcs/'.$request->inputLC);
        }
        //$invoiceRecord->save();
        //ONLY WORKS FOR SINGLE RECORDS
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Performa_invoice  $performa_invoice
     * @return \Illuminate\Http\Response
     */
    public function show($lc_num) //performa_invoice for lc
    {
        //
        $records = Performa_invoice::where('lc_num', $lc_num)
                                    ->get();
        //return $records;
        return view('profiles.performa_invoice', compact('records','lc_num'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Performa_invoice  $performa_invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Performa_invoice $performa_invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Performa_invoice  $performa_invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Performa_invoice $performa_invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Performa_invoice  $performa_invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Performa_invoice $performa_invoice)
    {
        //
    }
}
