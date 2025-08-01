<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderIndexResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\OrderContent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'balance';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $contents = OrderContent::select(
            'Order_num',
            DB::raw('SUM(qty * unit_price) AS subtotal'),
            DB::raw('SUM(qty) AS num_items'),
        )->groupBy('Order_num');

        $payments = Payment::select(
            'Order_num', 
            DB::raw('SUM(payment_amount - refund_amount) AS payment'),
            DB::raw('COUNT(*) AS count_payments')
        )->groupBy('Order_num');

        $customers = DB::table('customers');

        if ($sortBy == 'customer_name') {
            $sortBy = 'customers.name';
        }

        if ($sortBy == 'count') {
            $sortBy = 'num_items';
        }

        $data = 
        OrderIndexResource::collection(
            Order::leftJoinSub($contents, 'order_contents', function($join) {
                $join->on('order_contents.Order_num', '=', 'orders.Order_num');
            })->leftJoinSub($payments, 'payments', function($join) {
                $join->on('payments.Order_num', '=', 'orders.Order_num');
            })->leftJoinSub($customers, 'customers', function( $join ) {
                $join->on('orders.customer_id', '=', 'customers.id');
            })->groupBy('orders.Order_num')->select(
                'orders.Order_num',
                'orders.order_on',
                'orders.customer_id',
                'customers.name',
                'orders.discount_percent',
                'orders.discount_amount',
                'orders.tax_percentage',
                'orders.tax_amount',
                'orders.commission',
                'orders.created_at',
                DB::raw('IFNULL(num_items, 0) AS num_items'),
                DB::raw('IFNULL(count_payments, 0) AS count_payments'),
                DB::raw('SUM((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) AS grand_total'),
                DB::raw('SUM(IFNULL(payment, 0)) AS payments_total'),
                DB::raw('SUM(((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) - IFNULL(payment, 0)) AS balance')
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
        )
        ;
        if ($sortBy == 'customers.name') {
            $sortBy = 'customer_name';
        }

        if ($sortBy == 'num_items') {
            $sortBy = 'count';
        }

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('orders.index'),
            'title' => 'Orders',
            'type' => 'order',
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
