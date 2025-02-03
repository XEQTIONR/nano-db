<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\Consignment_expense;
use Illuminate\Http\Request;
use Validator;
class ConsignmentExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $expenses = Consignment_expense::all();

        return view('consignment_expenses', compact('expenses'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $consignments =  Consignment::with(['expenses', 'letterOfCredit:lc_num,currency_code'])->get();

        return view('new_expense', compact('consignments'));
    }

    public function createGivenBOL($bol)
    {

      return view('new_expense', compact('bol'));

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
//        'inputBOL' => 'required|string',
//        'inputExpenseLocal' => 'required|numeric|min:0',
//        'inputExpenseForeign' => 'required|numeric|min:0',
//        'inputNote' => 'required|string',
//      ]);

//      if ($validator->fails())
//      {
//        return redirect('/consignment_expenses/create/')
//              ->withErrors($validator)
//              ->withInput();
//      }
//      else
//      {
        //ALLOCATE

        $consignment = Consignment::find($request->input('bol'));

        $expenses = array();

        foreach($request->input('expenses') as $expense)
        {
          $an_expense = new Consignment_expense;

          $an_expense->expense_foreign = $expense['expense_foreign'];
          $an_expense->expense_local = $expense['expense_local'];

          if(strlen($expense['expense_notes']))
            $an_expense->expense_notes = $expense['expense_notes'];

          $expenses[] = $an_expense;
        }

        if(count($expense))
          $consignment->expenses()->saveMany($expenses);

        $response = array();
        $response['status'] = 'success';
        $response['expenses'] =  $consignment->expenses()->get();

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Consignment_expense  $consignment_expense
     * @return \Illuminate\Http\Response
     */
    public function show(Consignment_expense $consignment_expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Consignment_expense  $consignment_expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Consignment_expense $consignment_expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Consignment_expense  $consignment_expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consignment_expense $consignment_expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Consignment_expense  $consignment_expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consignment_expense $consignment_expense)
    {
        //
    }
}
