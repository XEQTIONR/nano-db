<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItemReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            $order->load(['contents', 'payments']);
            $contents = $order->contents;

            $returns = collect($request->returns);
            $returnedItems = collect([]);

            $returns->each(function($return) use (&$contents, &$returnedItems) {
                $id = $return['id'];
                $qty = $return['qty'];
                $price = $return['unit_price'];

                $contents = $contents->map(function($content) use ($id, &$qty, $price, &$returnedItems) {
                    if ($content->tyre_id == $id && intval($content->unit_price * 100) == intval($price * 100)) {
                        if ($qty > 0) {
                            if ($content->qty >= $qty) {
                                $newQty = $content->qty -  $qty;
                                $content->qty = $qty;
                                $returnedItems->push([
                                    'tyre_id' => $content->tyre_id,
                                    'qty' => $content->qty,
                                    'unit_price' => $content->unit_price,
                                    'container_num' => $content->container_num,
                                    'bol' => $content->bol,
                                ]);
                                $content->qty = $newQty;
                                $qty = 0;
                            } else {
                                $qty = $qty - $content->qty;
                                $returnedItems->push([
                                    'tyre_id' => $content->tyre_id,
                                    'qty' => $content->qty,
                                    'unit_price' => $content->unit_price,
                                    'container_num' => $content->container_num,
                                    'bol' => $content->bol,
                                ]);
                                $content->qty = 0;
                            }
                        }
                    }
                    return $content;
                });
            });

            $returnedItems = $returnedItems->map(fn($ret) => new OrderItemReturn($ret));

            $order->contents()->saveMany($contents);
            $order->returns()->saveMany($returnedItems);

            $order->discount_percent = $request->discount_percent;
            $order->discount_amount = $request->discount_amount;
            $order->tax_percentage = $request->tax_percentage;
            $order->tax_amount = $request->tax_amount;

            $order->save();

            $order->load(['contents', 'payments']);

            $contents = $order->contents;
            $payments = $order->payments;
            $editedPayments = collect([]);
            $subTotal = $contents->reduce(function($carry, $item) {
                return $carry + ($item->qty * $item->unit_price);
            }, 0);

            $amountOwed = $subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100)) + $order->tax_amount - $order->discount_amount;
            $paid = false; 

            foreach ($payments as $payment) {
                if (!$paid) {
                    if ($payment->amount <= $amountOwed) {
                        $amountOwed = $amountOwed - $payment->amount;
                    } else { // $payment->amount > $amountOwed
                        $payment->refund_amount = $payment->payment_amount - $amountOwed;
                        $editedPayments->push($payment);
                        $paid = true;
                    }
                } else {
                    $payment->refund_amount = $payment->payment_amount;
                    $editedPayments->push($payment);
                }
                
            }

            // save all edited payments
            $editedPayments->each(function($payment) {
                $payment->save();
            });

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();

            return [
                'success' => 'false',
                'error' => $e->getMessage()
            ];
        }

        return [
            'success' => 'true`',
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
