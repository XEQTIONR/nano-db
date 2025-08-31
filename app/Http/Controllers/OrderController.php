<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\OrderController as ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItemReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => route('orders.create'),
            'link' => route('orders.index'),
            'title' => 'Orders',
            'type' => 'order',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('orders/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = parent::store($request);

        return redirect(route('orders.receipt', compact('order')))
            ->with('notification', [
                'message' => 'New order #' . $order->Order_num . ' created',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Inertia::render('orders/show', ['order' => parent::show($order)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function returns(Order $order)
    {
        $order->load(['customer', 'contents.tyre', 'payments']);

        return Inertia::render('orders/returns', [
            'order' => new OrderResource($order)
        ]);
    }

    public function storeReturn(Order $order, Request $request)
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

            return redirect(route('orders.index'))->with('notification', [
                'message' => 'Something went wrong'
            ]);
        }

        return redirect(route('orders.show', ['order' => $order]))->with('notification', [
            'message' => 'Items returned and refunded.'
        ]);
    }
}
