<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use Illuminate\Support\Carbon;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;

class ReportService {

    public static $types = [
        'daily',
        'monthly',
        'yearly'
    ];

    public static $typeItemNames = [
        'daily' => 'day',
        'monthly' => 'month',
        'yearly' => 'year'
    ];

    protected static $units = [
        "daily" => "hour",
        "monthly" => "day",
        "yearly" => "month",
    ];

    protected static $counts = [
        "daily" => 24,
        "monthly" => 31,
        "yearly" => 12,
    ];

    public static function dashboard()
    {
        $func = function($order){ 
                $subTotal =  $order->contents->reduce(
                    fn($carry, $content) => $carry + $content->item_total, 
                    0
                );
                $grandTotal = ($subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100.0)))
                    - $order->discount_amount 
                    + $order->tax_amount;
                
                return $grandTotal;
        };

        $todaysDate = Carbon::now()->startOfDay();
        $orders = Order::with('contents')->whereDate('created_at', $todaysDate)->get();
        $payments = Payment::whereDate('created_at', $todaysDate)->get();
        $expenses = Expense::whereDate('date', $todaysDate)->get();

        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $revenue = $payments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $expenditure = $expenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);
        $sales = $orders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $yesterDaysDate = Carbon::now()->subDay();
        $yesterdaysOrders = Order::with('contents')->whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysPayments = Payment::whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysExpenses = Expense::where('date', $yesterDaysDate)->get();
        

        $yesterdaysCount = $yesterdaysOrders->count();
        $yesterdaysCount_items = $yesterdaysOrders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $yesterdaysRevenue = $yesterdaysPayments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $yesterdaysExpenditure = $yesterdaysExpenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);
        $yesterdaysSales = $yesterdaysOrders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $count_percent = $yesterdaysCount ? (floatval($count - $yesterdaysCount)/floatval($yesterdaysCount)) * 100.0 : 0;
        $count_items_percent = $yesterdaysCount_items ? (floatval($count_items - $yesterdaysCount_items)/floatval($yesterdaysCount_items)) * 100.0 : 0;
        $revenue_percent = $yesterdaysRevenue ?(floatval($revenue - $yesterdaysRevenue)/floatval($yesterdaysRevenue)) * 100.0 : 0;
        $expenditure_percent = $yesterdaysExpenditure ? (floatval($expenditure - $yesterdaysExpenditure)/floatval($yesterdaysExpenditure)) * 100.0 : 0;
        $sales_percent = $yesterdaysSales ? (floatval($sales - $yesterdaysSales)/floatval($yesterdaysSales)) * 100.0 : 0;
        
        $intervals = self::intervals($todaysDate);
        $chart_data = $intervals->map(function($interval) use ($orders, $payments, $expenses, $func) {
            return [
                'interval' => $interval,
                'orders' => $orders->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0),
                'payments' => $payments->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->reduce(fn($carry, $item) => $carry + ($item->amount), 0),
                'expenses' => $expenses->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->reduce(fn($carry, $item) => $carry + ($item->amount), 0),
            ];
        });

        $sum = 0;
        $sumPayments = 0;
        $sumExpenses = 0;
        $hour = 0;
        $now = Carbon::now();

        $chart_data = $chart_data->map(function($cla) use (&$sum, &$hour, &$sumPayments, &$sumExpenses, $now) {
            $sum = $sum + $cla['orders'];
            $sumPayments = $sumPayments + $cla['payments'];
            $sumExpenses = $sumExpenses + $cla['expenses'];
            if ($hour == 0) {
                $hr = "12 am";
            } else if ($hour == 12) {
                $hr = "12 pm";
            } else if ($hour > 12) {
                $hr = (($hour % 12)) . " pm";
            } else {
                $hr = $hour." am";
            }
            $hour++;

            return [
                'hours' => $hr,
                'sumOrderGrandTotal' => $now->hour < $hour - 1 ? null : $sum,
                'sumPayments' => $now->hour < $hour - 1 ? null : $sumPayments,
                'sumExpenses' => $now->hour < $hour - 1 ? null : $sumExpenses,
            ];
        });

        return compact(
            'count', 
            'count_items', 
            'revenue', 
            'expenditure', 
            'sales' , 
            'count_percent', 
            'count_items_percent', 
            'revenue_percent', 
            'expenditure_percent', 
            'sales_percent', 
            'chart_data',
        );
    }

    public static function sales(string $type, string $date)
    {
        $func = function($order){ 
            $subTotal =  $order->contents->reduce(
                fn($carry, $content) => $carry + $content->item_total, 
                0
            );
            $grandTotal = ($subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100.0)))
                - $order->discount_amount 
                + $order->tax_amount;
            
            return $grandTotal;
        };
        
        $today = new Carbon($date);
        $now = Carbon::now();

        switch($type) {
            case "monthly":
                $orders = Order::with('contents')
                    ->whereMonth('created_at', '' . $today->month)
                    ->whereYear('created_at', '' . $today->year)
                    ->get();

                $yesterday = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $yesterdaysOrders = Order::with('contents')
                    ->whereMonth('created_at', '' . $yesterday->month)
                    ->whereYear('created_at', '' . $yesterday->year)
                    ->get();
                break;
            case "daily":
            default:
                $orders = Order::with('contents')->whereDate('created_at', $today)->get();
                $yesterday = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $yesterdaysOrders = Order::with('contents')->whereDate('created_at', $yesterday)->get();
        }

        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $sales = $orders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $yesterdaysCount = $yesterdaysOrders->count();
        $yesterdaysCount_items = $yesterdaysOrders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $yesterdaysSales = $yesterdaysOrders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $count_percent = $yesterdaysCount ? (floatval($count - $yesterdaysCount)/floatval($yesterdaysCount)) * 100.0 : 0;
        $count_items_percent = $yesterdaysCount_items ? (floatval($count_items - $yesterdaysCount_items)/floatval($yesterdaysCount_items)) * 100.0 : 0;
        $sales_percent = $yesterdaysSales ? (floatval($sales - $yesterdaysSales)/floatval($yesterdaysSales)) * 100.0 : 0;

        $intervals = self::intervals($today->copy()->startOf(self::$typeItemNames[$type]), $type);
        $chart_data = $intervals->map(function($interval) use ($orders, $func) {
            return [
                'interval' => $interval,
                'orders' => $orders->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0),
            ];
        });

        $intervals = self::intervals($yesterday->copy(), $type);
        $chart_data = $chart_data->map(function($chart_row, $index) use ($intervals, $yesterdaysOrders, $func) {
            $row = $chart_row;
            $row['lastOrders'] =  $yesterdaysOrders->filter(function($item) use ($intervals, $index) {
                return $item->created_at->isBetween(...$intervals[$index]);
            })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

            return $row;

        });

        $sum = 0;
        $sum2 = 0;
        $hour = 0;

        $chart_data = $chart_data->map(function($data) use (&$sum, &$sum2, &$hour, $now, $today, $type) {
            $sum = $sum + $data['orders'];
            $sum2 = $sum2 + $data['lastOrders'];

            return [
                'hours' => self::intervalLabel($hour++, $type),
                'sumOrderGrandTotal' => $today->isSameDay($now) ? ($hour > ($now->hour + 1) ? null : $sum) : $sum, //$hour > $now->hour ? null : $sum, // $to
                'sumLastOrderGrandTotal' => $sum2,
            ];
        });

        $orders = OrderResource::collection($orders);
            
        return compact(
            'count', 
            'count_items', 
            'sales', 
            'count_percent', 
            'count_items_percent', 
            'sales_percent', 
            'chart_data', 
            'orders'
        );
    }

    protected static function intervals(Carbon $start, $type = "daily")
    {
        $count = self::$counts[$type];

        $k = 0;

        $intervals = collect([]);

        while ($k < $count) {
            $end = $start->copy()->add(1, self::$units[$type])->subSecond();
            $intervals->push([$start->copy(), $end->copy()]);
            $start = $start->add(1, self::$units[$type]);
            $k++;
        }

        return $intervals;
    }

    protected static function intervalLabel($n, $type = "daily")
    {
        switch($type) {
            case "monthly":
                return strval($n+1);
            case "daily":
            default:
                if ($n == 0) {
                    return "12 am";
                } else if ($n == 12) {
                    return "12 pm";
                } else if ($n > 12) {
                    return (($n % 12)) . " pm";
                } else {
                    return $n." am";
                }
                
        }
    }
}