<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'transaction_id';

        $sortDir = $request->input('sortDir') ?? 'desc';

        if ($sortBy == 'amount') {
            $data = 
            PaymentResource::collection(
                Payment::with(['order.contents', 'order.customer', 'order.payments'])
                    ->orderByRaw('payment_amount - refund_amount ' . $sortDir)
                    ->paginate($perPage)
                    ->withQueryString()
            );
        } else {
            $data = PaymentResource::collection(
                Payment::orderBy($sortBy, $sortDir)
                    ->paginate($perPage)
                    ->withQueryString()
            );
        }


        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('payments.index'),
            'title' => 'Payments',
            'type' => 'payment',
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
