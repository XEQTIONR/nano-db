<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\OrderController as ApiController;
use App\Http\Resources\BankAccountResource;
use App\Http\Resources\OrderResource;
use App\Models\BankAccount;
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
            'breadcrumbsLinks' => [
                ['title' => 'Orders', 'href' => route('orders.index')],
            ],
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
        return Inertia::render('orders/show', [
            'order' => parent::show($order),
            'accounts' => BankAccountResource::collection(BankAccount::all())
        ]);
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
