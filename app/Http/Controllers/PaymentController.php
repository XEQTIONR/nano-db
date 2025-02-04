<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $payments = Payment::with('bankAccount')->orderBy('created_at', 'desc')->get();
        $paymentTypes = Payment::$paymentTypes;

        return view('payments', compact('payments', 'paymentTypes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
      $paymentTypes = array_filter( Payment::$paymentTypes, function($val) { return $val !== 'Unknown'; });
      $bankAccounts = BankAccount::all();
      $orders = Order::with(['customer:id,name,address,phone','payments', 'orderContents.tyre'])->get();

      foreach($orders as $order)
      {
        $order->customer->address =  str_replace("\n", "", nl2br($order->customer->address));
        //$order->customer->notes =  str_replace("\n", "", nl2br($order->customer->notes));
      }


      return view('new_payment', compact('orders', 'paymentTypes', 'bankAccounts'));
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
        $amount = $request->amount;
        $order = Order::find(intval($request->order));
        $payable = floatval($order->calculatePayable());
        $duplicate = Payment::where('random', $request->random)->first();

        if($amount > $payable)
        {
          $response = [];

          $response['status'] = 'failed';
          $response['message'] = "Amount paid is greater than payable amount.";

          return $response;
        }

        if($duplicate != null){

          $response = [];

          $response['status'] = 'failed';
          $response['message'] = "Duplicate Request. Your payment may have been already added.".
                                  " Check and then try again if required.";

          return $response;
        }

        //ALLOCATE
        $payment = new Payment;

        //INITIALIZE
        $payment->Order_num = $request->order;
        $payment->payment_amount = $request->amount;
        $payment->refund_amount = 0;
        $payment->random = $request->random;
        $payment->type = $request->paymentType;
        $payment->account = $request->accountId;


        //STORE
        if ($request->paymentType == 'commission') { // payment is not created if it is a commission 
          $order->commission = $request->amount;
          $order->save();
          $order->refresh();
          //fake payment details
          $payment->created_at = $order->updated_at;
          $payment->updated_at = $order->updated_at;
        } else {
          $payment->save();
          $payment->refresh(); // otherwise $payment->refund_amount is not hydrated.
        }

        $payment->new = true;

        //REDIRECT
        $response = [];

        $response['status'] = 'success';
        $response['payment'] = $payment;

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
