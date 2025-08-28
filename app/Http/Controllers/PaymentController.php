<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\PaymentController as ApiController;
use App\Models\Order;

class PaymentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'link' => route('payments.index'),
            'title' => 'Payments',
            'type' => 'payment',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order_num = $request->order_num;
        $payment_amount = $request->payment_amount;
        $type = $request->payment_type;

        $order = Order::find($order_num);

        $payment = new Payment([
            'payment_amount' => $payment_amount,
            'type' => $type,
            'random' => substr(
                substr(uniqid(), 7) . substr(uniqid(), 7)
                    . substr(uniqid(), 7) . substr(uniqid(), 7),
                2
            )
        ]);

        $order->payments()->save($payment);

        $payment->fresh();

        return redirect(route('orders.index'))->with('notification', [
            'message' => 'New payment (ID: ' 
                . $payment->transaction_id 
                . ') of TK ' 
                . $payment_amount 
                . ' created for Order #'
                . $order_num,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
