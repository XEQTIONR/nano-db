<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\OrderIndexResource;
use App\Models\Order;
use App\Models\OrderContent;
use App\Models\Payment;
use App\Services\FilterService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DetailedStockResource;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
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
                ->fromSub($query, 'orders');
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }

            $query = $query->select();
        }

        $data = OrderIndexResource::collection(
            $query->orderBy($sortBy, $sortDir)->paginate($perPage)->withQueryString()
        );

        return [
            'items' => $data,
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }

    public function store(Request $request) {
        DB::beginTransaction();

        $customer_id = $request->customer_id;
        $order_on = $request->order_on;
        $tax_percentage = $request->tax_percentage;
        $tax_amount = $request->tax_amount;
        $discount_percent = $request->discount_percent;
        $discount_amount = $request->discount_amount;
        $items = collect($request->items);

        $order = new Order([
            ...compact([
                'customer_id', 
                'order_on', 
                'tax_percentage',
                'tax_amount',
                'discount_percent',
                'discount_amount'
            ]),
            'random' => substr(
                substr(uniqid(), 7) . substr(uniqid(), 7)
                    . substr(uniqid(), 7) . substr(uniqid(), 7),
                2
            )
        ]);
        $order->save();

        $stock = resolve(DetailedStockResource::class)
            ->whereRaw('(IFNULL(container_contents.supplied_qty, 0) - IFNULL(order_contents.ordered_qty, 0) - IFNULL(waste.wasted_qty, 0)) > 0')
            ->orderBy('container_contents.created_at')
            ->get();

        $orderContents = collect([]);

        $items->each(function($item) use (&$stock, &$orderContents) {
            $qty = $item['qty'];

            $indexes = collect([]);
            $selected = collect([]);
            
            $stock->each(function($stockItem, $index) use (&$indexes, &$selected,  $item) {
                if ($stockItem['tyre_id']  == $item['tyre_id']) {
                    $indexes->push($index);
                    $selected->push($stockItem);
                }
            });

            $selected->each(function($stockItem, $i) use (&$qty, &$orderContents, &$stock, $indexes, $item) {
                $index = $indexes[$i];

                if ($stockItem->in_stock >= $qty) {
                    $orderContents->push(new OrderContent([
                        'container_num' => $stockItem->Container_num,
                        'bol' => $stockItem->BOL,
                        'qty' => $qty,
                        'tyre_id' => $stockItem->tyre_id,
                        'unit_price' => $item['unit_price'],
                    ]));

                    $stock[$index]->qty = $stock[$index]->qty - $qty;

                    return false;
                } else {
                    $orderContents->push(new OrderContent([
                        'container_num' => $stockItem->Container_num,
                        'bol' => $stockItem->BOL,
                        'qty' => $stockItem->in_stock,
                        'tyre_id' => $stockItem->tyre_id,
                        'unit_price' => $item['unit_price'],
                    ]));

                    $qty = $qty - $stockItem->in_stock;
                    $stock[$index]->qty = 0;
                }
                
            });
        });

        $order->contents()->saveMany($orderContents);

        DB::commit();
        
        $order = $order->fresh();
        $order->load(['contents.tyre', 'customer']);

        return new OrderResource($order);
    }

    public function show(Order $order) {
        $order->load(['contents.tyre', 'payments', 'customer']);

        return new OrderResource($order);
    }
}
