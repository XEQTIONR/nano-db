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

        if ($type == 'commission') {
            if ($order->commission == 0) {
                $order->load(['contents', 'payments']);
                $paymentsTotal = $order->payments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
                $subTotal = $order->contents->reduce(fn($carry, $content) => $carry + ($content->qty * $content->unit_price) ,0);
                $grandTotal = $subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100)) + $order->tax_amount - $order->discount_amount;
                
                if (($grandTotal - $paymentsTotal - $payment_amount) >= 0) {
                    $order->commission = $payment_amount;
                    $order->save();

                    return redirect(route('orders.show', [ 'order' => $order ]))->with('notification', [
                        'message' => 'Commission ' 
                            . ' of TK ' 
                            . $payment_amount 
                            . ' saved for Order #'
                            . $order_num,
                    ]);
                } // commission overflows
                return redirect(route('orders.show', [ 'order' => $order ]))->with('notification', [
                    'message' => 'Commission ' 
                        . ' of TK ' 
                        . $payment_amount 
                        . ' is greater than order balance for Order #'
                        . $order_num,
                ]);
                
            } // commision already exists
            return redirect(route('orders.show', [ 'order' => $order ]))->with('notification', [
                'message' => 'Commission already added for Order #' . $order_num
            ]);
        } // real payment (not commission)
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

        return redirect(route('orders.show', [ 'order' => $order ]))->with('notification', [
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
