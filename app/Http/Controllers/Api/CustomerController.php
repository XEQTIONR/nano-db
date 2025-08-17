<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\OrderContent;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FilterService;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'balance';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        $contents = OrderContent::select('Order_num', DB::raw('SUM(qty * unit_price) AS subtotal'))->groupBy('Order_num');

        $payments = Payment::select('Order_num', DB::raw('SUM(payment_amount - refund_amount) AS payment'))->groupBy('Order_num');

        $customers = DB::table('customers');
        
        $query = Order::leftJoinSub($contents, 'order_contents', function($join) {
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
            DB::raw('SUM(orders.commission) AS total_commission'),
            DB::raw('SUM((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) AS grand_total'),
            DB::raw('SUM(IFNULL(payment, 0)) AS payment_total'),
            DB::raw('SUM(((subtotal * (1+((tax_percentage - discount_percent)/100))) - discount_amount + tax_amount) - IFNULL(payment, 0) - commission ) AS balance')
        );

        if ($filters->count() > 0) {
            $query = DB::connection(config('database.default'))
                ->query()
                ->fromSub($query, 'customers');
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }

            $query = $query->select();
        }


        $data = $query->orderBy($sortBy, $sortDir)
        ->paginate($perPage)->withQueryString();

        return [
            'items' => CustomerResource::collection($data),
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }
}
