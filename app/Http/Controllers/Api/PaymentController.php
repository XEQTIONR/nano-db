<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\FilterService;

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

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        if ($sortBy == 'amount') {
            $query = Payment::with(['order.contents', 'order.customer', 'order.payments', 'bankAccount'])
                ->orderByRaw('payment_amount - refund_amount ' . $sortDir);
        } else {
            $query = Payment::with(['order.contents', 'order.customer', 'order.payments', 'bankAccount'])
                ->orderBy($sortBy, $sortDir);
        }

        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if ($filters[$i][0] == '*') {
                    $query->whereAny(Payment::$searchable, $filters[$i][1], $filters[$i][2]);
                } else if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    if ($filters[$i][0] === 'amount') {
                         $query = $query->whereRaw("(payment_amount - refund_amount) IN(" .  implode(", ", $filters[$i][2]) . ")");
                    } else {
                        $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                    }
                } else if ($filters[$i][0] === 'amount') {
                    $query = $query->whereRaw("(payment_amount - refund_amount) " . $filters[$i][1] .  $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }
            $query = $query->select();
        }

        $data = PaymentResource::collection(
            $query->paginate($perPage)
                ->withQueryString()
        );

        return [
            'filters' => $filters,
            'items' => $data,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
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
