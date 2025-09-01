<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\OrderReturnController as ApiController;
use App\Models\Order;
use Inertia\Inertia;
use App\Http\Resources\OrderResource;

class OrderReturnController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Order $order)
    {
        $order->load(['customer', 'contents.tyre', 'payments']);

        return Inertia::render('orders/returns', [
            'order' => new OrderResource($order)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Order $order, Request $request)
    {
        $response = parent::store($order, $request);

        if ($response['success']) {
            return redirect(route('orders.show', ['order' => $order]))->with('notification', [
                'message' => 'Items returned and refunded.'
            ]);
        }

        return redirect(route('orders.index'))->with('notification', [
            'message' => 'Something went wrong'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
