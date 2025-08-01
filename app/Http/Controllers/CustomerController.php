<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\OrderContent;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'balance';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $contents = OrderContent::select('Order_num', DB::raw('SUM(qty * unit_price) AS subtotal'))->groupBy('Order_num');

        $payments = Payment::select('Order_num', DB::raw('SUM(payment_amount - refund_amount) AS payment'))->groupBy('Order_num');

        $customers = DB::table('customers');
        
        $data = Order::leftJoinSub($contents, 'order_contents', function($join) {
            $join->on('order_contents.Order_num', '=', 'orders.Order_num');
        })->leftJoinSub($payments, 'payments', function($join) {
            $join->on('payments.Order_num', '=', 'orders.Order_num');
        })->rightJoinSub($customers, 'customers', function( $join ) {
            $join->on('orders.customer_id', '=', 'customers.id');
        })->groupBy('customers.id')->select(
            'customers.id',
            'customers.name',
            'customers.address',
            'customers.phone',
            'customers.notes',
            'customers.created_at',
            DB::raw('COUNT(orders.Order_num) AS num_orders'),
            DB::raw('SUM((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) AS grand_total'),
            DB::raw('SUM(IFNULL(payment, 0)) AS payment_total'),
            DB::raw('SUM(((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) - IFNULL(payment, 0)) AS balance')
        )->orderBy($sortBy, $sortDir)
        ->paginate($perPage)->withQueryString();

        return Inertia::render('common/index', [
            'items' => CustomerResource::collection($data),
            'link' => route('customers.index'),
            'title' => 'Customers',
            'type' => 'customer',
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
