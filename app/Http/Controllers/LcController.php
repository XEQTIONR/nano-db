<?php

namespace App\Http\Controllers;

use App\Models\Tyre;
use App\Models\Lc;
use App\Models\Performa_invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Carbon\Carbon;

class LcController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $LCs = Lc::all();
        return view('lcs', compact('LCs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $tyres = Tyre::all(); //non-ajax request

        foreach($tyres as $tyre)
        {
          $tyre->qty = 0;
          $tyre->unit_price = 0;
        }

        //dd($tyres);
        return view('new_lc', compact('tyres'));
    }

    public function createProformaInvoice()
    {
      $lcs = DB::select('
          SELECT K.* 
          FROM lcs K
          WHERE K.lc_num NOT IN
            (SELECT L.lc_num
            FROM lcs L, performa_invoices P
            WHERE L.lc_num = P.lc_num
            GROUP BY lc_num)
      ');

      $tyres = Tyre::all();

      foreach ($tyres as $tyre)
      {
        $tyre->qty = 0;
        $tyre->unit_price = 0;
      }

      return view('new_proforma_invoice', compact('lcs', 'tyres'));
    }

    public function storeProformaInvoice(Request $request)
    {
      $response = array();
      $lc = Lc::find($request->lc_num);
      $this->_storeProformaInvoiceRecords($lc, $request->proforma_invoice,$request->invoice_num);

      $response['status'] = 'success';

      return $response;

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
//        $validator=Validator::make($request->all(),[
//          'LcNumber' => 'required|numeric|digits:12',
//          'DateIssued' => 'required|date',
//          'DateExpiry' => 'required|date|after:DateIssued',
//          'Applicant' => 'required|string',
//          'Beneficiary' => 'required|string',
//          'PortDepart' => 'required|string',
//          'PortArrive' => 'required|string',
//          'Invoice' => 'required',
//          'CurrencyCode' => 'required|alpha|size:3',
//          'Value' => 'required|numeric|min:0.0000000001',
//          'ForeignExpense' => 'required|numeric|min:0',
//          'LocalExpense' => 'required|numeric|min:0',
//          'ExchangeRate' => 'required|numeric|min:0.0000000001',
//        ]);
//
//      if ($validator->fails())
//      {
//        return redirect('lcs/create')
//                ->withErrors($validator)
//                ->withInput();
//      }
//      else
//      {
        //ALLOCATE LC
        $lc = new Lc;

        $d1 = explode( "/",$request->date_issued);
        $d2 = explode("/",$request->date_expired);

        $date1 = Carbon::create($d1[2],$d1[1],$d1[0]);
        $date2 = Carbon::create($d2[2],$d2[1],$d2[0]);
        //INITIALIZE LC
        $lc->lc_num = $request->lc_num; //LC#
        $lc->date_issued = $date1->toDateString();
        $lc->date_expiry = $date2->toDateString();
        $lc->applicant = $request->applicant;
        $lc->beneficiary = $request->beneficiary;
        $lc->port_depart = $request->departing_port;
        $lc->port_arrive = $request->arriving_port;
        $lc->invoice_no= $request->invoice_num;
        $lc->currency_code = $request->currency_code;
        $lc->foreign_amount = $request->lc_value;
        $lc->foreign_expense = $request->expense_foreign;
        $lc->domestic_expense = $request->expense_local;
        $lc->exchange_rate = $request->exchange_rate;
        $lc->notes = $request->notes;

        //STORE LC
        $lc->save();

        //ALLOCATE Performa_invoice
        $contents = array();
        if(isset($request->proforma_invoice) && count($request->proforma_invoice))
        {
          $this->_storeProformaInvoiceRecords($lc, $request->proforma_invoice);
        }
        $response = array();

        $response['status'] = 'success';

        return $response;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lc  $lc
     * @return \Illuminate\Http\Response
     */
    public function show(Lc $lc)
    {

        $performa = $lc->performaInvoice()
                      ->with('tyre')
                      ->get();

        $consignments = $lc->consignment()
                          ->get();

        $totalqty = 0;
        $total = 0;
        foreach($performa as $record)
        {
          $totalqty += intval($record->qty);
          $total += (floatval($record->unit_price) * floatval($record->qty));

        }

        $lc->total = $total;
        $lc->totalqty = $totalqty;
        //return $performa;
        return view('profiles.lc', compact('lc', 'performa', 'consignments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lc  $lc
     * @return \Illuminate\Http\Response
     */
    public function edit(Lc $lc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lc  $lc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lc $lc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lc  $lc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lc $lc)
    {
        //
    }

    //api functions
    public function checkLCNumber(Request $request)
    {

      $lc = Lc::find($request->lc_num);

      $response = array();

      if($lc)
      {
        $response[ 'status' ]='error';
        $response[ 'error' ]='duplicate';
      }
      else
        $response['status'] = 'success';

      return $response;

    }


    //helper functions

    private function _storeProformaInvoiceRecords(Lc $lc, $proforma_invoice, $invoice_num = null)
    {
      $contents = array();
      if(isset($proforma_invoice) && count($proforma_invoice))
      {

        if(!is_null($invoice_num))
        {
          $lc->invoice_no = $invoice_num;
          $lc->save();
        }

        foreach ($proforma_invoice as $record)
        {
          $item = new Performa_invoice;

          $item->lc_num=$lc->lc_num;
          $item->tyre_id=$record[ 'tyre_id' ];
          $item->qty=$record[ 'qty' ];
          $item->unit_price=$record[ 'unit_price' ];

          //push
          $contents[]=$item;
        }

        //STORE Performa_invoice
        $lc->performaInvoice()->saveMany($contents);
      }
    }
}
