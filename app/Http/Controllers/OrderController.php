<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderIndexResource;
use App\Models\Order;
use App\Models\OrderContent;
use App\Models\Payment;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        $contentsQuery = OrderContent::select(
            'Order_num',
            DB::raw('SUM(qty * unit_price) AS subtotal'),
            DB::raw('SUM(qty) AS num_items'),
        )->groupBy('Order_num');

        $paymentsQuery = Payment::select(
            'Order_num', 
            DB::raw('SUM(payment_amount - refund_amount) AS payment'),
            DB::raw('COUNT(*) AS count_payments')
        )->groupBy('Order_num');

        $customersQuery = DB::table('customers');

        $query = 
            Order::leftJoinSub($contentsQuery, 'order_contents', function($join) {
                $join->on('order_contents.Order_num', '=', 'orders.Order_num');
            })->leftJoinSub($paymentsQuery, 'payments', function($join) {
                $join->on('payments.Order_num', '=', 'orders.Order_num');
            })->leftJoinSub($customersQuery, 'customers', function( $join ) {
                $join->on('orders.customer_id', '=', 'customers.id');
            })->groupBy('orders.Order_num')
            
            ->select(
                'orders.Order_num',
                'orders.order_on',
                'orders.customer_id',
                DB::raw('customers.name AS customer_name'),
                'orders.discount_percent',
                'orders.discount_amount',
                'orders.tax_percentage',
                'orders.tax_amount',
                'orders.commission',
                'orders.created_at',
                DB::raw('IFNULL(num_items, 0) AS count'),
                DB::raw('IFNULL(count_payments, 0) AS count_payments'),
                DB::raw('SUM((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) AS grand_total'),
                DB::raw('SUM(IFNULL(payment, 0)) AS payments_total'),
                DB::raw('SUM(((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) - IFNULL(payment, 0) - commission) AS balance')
            );

        if ($filters->count() > 0) {
            $query = DB::connection(config('database.default'))
                ->query()
                ->fromSub($query, 'orders')
                ->where([...$filters])
                ->select();
        }

        $data = OrderIndexResource::collection(
            $query->orderBy($sortBy, $sortDir)->paginate($perPage)
        );

        $ret = [
            'items' => $data,
            'link' => route('orders.index'),
            'title' => 'Orders',
            'type' => 'order',
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];

        if ($filters != "") {
            $ret['filters'] = $filters;
        }

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('orders.index'),
            'title' => 'Orders',
            'type' => 'order',
            'filters' => $filters,
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
