<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        //
        $customers = DB::select(resolve('CustomersOwingSQL'));

        return view('customers',['customers'=>$customers]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

          return view('new_customer');
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
//          'Name' => 'required|string',
//          'Address' => 'required|string',
//          'Phone' => 'required|numeric|digits_between:7,12',
//          'Notes' => 'string|nullable',
//        ]);
//
//        if($validator->fails())
//        {
//          return redirect('customers/create')
//                  ->withErrors($validator)
//                  ->withInput();
//        }
        //ALLOCATE
        $customer = new Customer;


        //INITIAZE
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->notes = $request->notes;

        //STORE
        $customer->save();

        $response = array();
        $response['status'] = 'success';
        $response['customer_id'] = $customer->id;

        return $response;
        //return redirect('/customers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
      $orders = $customer->orders()->with('payments', 'orderContents')->get();

      $totals = new \stdClass();
      $totals->total = 0;
      $totals->payments = 0;
      $totals->commission = 0;
      $totals->balance = 0;

      $orders->each(function($order) use ($totals) {
          $total = $order->payments->reduce(function($carry, $payment){
            return $carry + $payment->amount;
          }, 0);
          $order->payments_total = $total;

          $total = $order->orderContents->reduce(function($carry, $item){
              return $carry + ($item->qty * $item->unit_price);
          }, 0);
          $order->sub_total = $total;

          $order->discount_total = ($order->sub_total* $order->discount_percent/100.0) + $order->discount_amount;
          $order->tax_total = ($order->sub_total* $order->tax_percentage) + $order->tax_amount;
          $order->grand_total = $order->sub_total + $order->tax_total - $order->discount_total;
          $order->balance = $order->grand_total - $order->commission - $order->payments_total;


          $totals->total += $order->grand_total;
          $totals->payments+= $order->payments_total;
          $totals->commission += $order->commission;
          $totals->balance += $order->balance;
      });

      $payments = $customer->payments()->orderBy('created_at', 'desc')->limit(10)->get();
      
      return view('partials.rows.customer', compact('customer', 'orders', 'payments', 'totals'));
    }

    public function apiShow(Request $request){

      $id = $request->customer;
      $customer = Customer::find($id);
      return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //

        $customer->name = $request->inputCustomerName;
        $customer->address = $request->inputAddress;
        $customer->phone = $request->inputPhone;
        $customer->notes = $request->inputNotes;

        $customer->save();


        return redirect ("/customers/".$customer->id);
    }

  public function apiUpdate(Request $request)
  {
    //
    $customer = Customer::find($request->customer);
    $customer->name = $request->name;
    $customer->address = $request->address;
    $customer->phone = $request->phone;
    $customer->notes = $request->notes;

    $customer->save();


    return array('status' => 'success');
    //return redirect ("/customers/".$customer->id);
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
