<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use App\Http\Controllers\Api\OrderReceiptController as ApiController;
use App\Http\Resources\BankAccountResource;
use App\Models\BankAccount;

class OrderReceiptController extends ApiController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Order $order)
    {
        return Inertia::render('orders/receipt', [
            'order' => parent::__invoke($order),
            'accounts' => BankAccountResource::collection(BankAccount::all())
        ]);
    }
}
