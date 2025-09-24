<?php

namespace App\Services;

use App\Http\Resources\ExpenseResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Carbon;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

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
        //@TODO: optimize queries
        $orders = Order::with('contents')->whereDate('created_at', $todaysDate)->get(); 
        $payments = Payment::whereDate('created_at', $todaysDate)->get();
        $expenses = Expense::whereDate('date', $todaysDate)->get();

        //@TODO: optimize queries
        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $revenue = $payments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $expenditure = $expenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);
        $sales = $orders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $yesterDaysDate = Carbon::now()->subDay();
        //@TODO: optimize queries
        $yesterdaysOrders = Order::with('contents')->whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysPayments = Payment::whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysExpenses = Expense::where('created_at', $yesterDaysDate)->get();
        
        //@TODO: optimize queries
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

    public static function expense(string $type, string $date)
    {
        
        $thisPeriod = new Carbon($date);
        $now = Carbon::now();

        //@TODO: optimize queries, getting too many results in single trip
        switch($type) {
            case "yearly":
                $expenses = Expense::whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastExpenses = Expense::whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "monthly":
                $expenses = Expense::whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();

                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastExpenses = Expense::whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "daily":
            default:
                $expenses = Expense::whereDate('created_at', $thisPeriod)->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastExpenses = Expense::whereDate('created_at', $lastPeriod)->get();
        }

        $count = $expenses->count();
        $sumExpenses = $expenses->reduce(fn($carry, $item) => $carry + $item->amount_local, 0);

        $lastCount = $lastExpenses->count();
        $lastSumExpenses = $lastExpenses->reduce(fn($carry, $item) => $carry + $item->amount_local, 0);

        $count_percent = $lastCount ? (floatval($count - $lastCount)/floatval($lastCount)) * 100.0 : 0;
        $expense_percent = $lastSumExpenses ? (floatval($sumExpenses - $lastSumExpenses)/floatval($lastSumExpenses)) * 100.0 : 0;

        $intervals = self::intervals($thisPeriod->copy()->startOf(self::$typeItemNames[$type]), $type);
        $chart_data = $intervals->map(function($interval) use ($expenses) {
            return [
                'interval' => $interval,
                'expenses' => $expenses->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->reduce(fn($carry, $item) => $carry + $item->amount_local, 0),
            ];
        });

        $intervals = self::intervals($lastPeriod->copy(), $type);
        $chart_data = $chart_data->map(function($chart_row, $index) use ($intervals, $lastExpenses) {
            $row = $chart_row;
            $row['lastExpenses'] =  $lastExpenses->filter(function($item) use ($intervals, $index) {
                return $item->created_at->isBetween(...$intervals[$index]);
            })->reduce(fn($carry, $item) => $carry + $item->amount_local, 0);

            return $row;

        });

        $sum = 0;
        $sum2 = 0;
        $hour = 0;

        $chart_data = $chart_data->map(function($data) use (&$sum, &$sum2, &$hour, $now, $thisPeriod, $type) {
            $sum = $sum + $data['expenses'];
            $sum2 = $sum2 + $data['lastExpenses'];
            return [
                'hours' => self::intervalLabel($hour++, $type),
                'sumExpenses' => ($type == "yearly"
                    ? (($now->year <= $thisPeriod->year) && ($hour > ($now->month)) ? null : $sum ) 
                    : ($type == "monthly"
                        ? (($now->year <= $thisPeriod->year) && ($hour > ($now->day)) ? null : $sum)
                        : ($thisPeriod->isSameDay($now) 
                            ? ($hour > ($now->hour + 1) ? null : $sum) 
                            : $sum))),
                'sumLastExpenses' => $sum2,
            ];
        });

        $expenses = ExpenseResource::collection($expenses);
        $expense = $sumExpenses;
            
        return compact(
            'count', 
            'count_percent', 
            'expense', 
            'expense_percent', 
            'chart_data', 
            'expenses'
        );
    }


    public static function revenue(string $type, string $date)
    {
        
        $thisPeriod = new Carbon($date);
        $now = Carbon::now();

        //@TODO: optimize queries, getting too many results in single trip
        switch($type) {
            case "yearly":
                $payments = Payment::whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastPayments = Payment::whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "monthly":
                $payments = Payment::whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();

                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastPayments = Payment::whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "daily":
            default:
                $payments = Payment::whereDate('created_at', $thisPeriod)->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastPayments = Payment::whereDate('created_at', $lastPeriod)->get();
        }

        $count = $payments->count();
        $revenue = $payments->reduce(fn($carry, $item) => $carry + $item->amount, 0);

        $lastCount = $lastPayments->count();
        $lastRevenue = $lastPayments->reduce(fn($carry, $item) => $carry + $item->amount, 0);

        $count_percent = $lastCount ? (floatval($count - $lastCount)/floatval($lastCount)) * 100.0 : 0;
        $revenue_percent = $lastRevenue ? (floatval($revenue - $lastRevenue)/floatval($lastRevenue)) * 100.0 : 0;

        $intervals = self::intervals($thisPeriod->copy()->startOf(self::$typeItemNames[$type]), $type);
        $chart_data = $intervals->map(function($interval) use ($payments) {
            return [
                'interval' => $interval,
                'payments' => $payments->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->reduce(fn($carry, $item) => $carry + $item->amount, 0),
            ];
        });

        $intervals = self::intervals($lastPeriod->copy(), $type);
        $chart_data = $chart_data->map(function($chart_row, $index) use ($intervals, $lastPayments) {
            $row = $chart_row;
            $row['lastPayments'] =  $lastPayments->filter(function($item) use ($intervals, $index) {
                return $item->created_at->isBetween(...$intervals[$index]);
            })->reduce(fn($carry, $item) => $carry + $item->amount, 0);

            return $row;

        });

        $sum = 0;
        $sum2 = 0;
        $hour = 0;

        $chart_data = $chart_data->map(function($data) use (&$sum, &$sum2, &$hour, $now, $thisPeriod, $type) {
            $sum = $sum + $data['payments'];
            $sum2 = $sum2 + $data['lastPayments'];
            return [
                'hours' => self::intervalLabel($hour++, $type),
                'sumPayments' => ($type == "yearly"
                    ? (($now->year <= $thisPeriod->year) && ($hour > ($now->month)) ? null : $sum ) 
                    :($thisPeriod->isSameDay($now) 
                        ? ($hour > ($now->hour + 1) ? null : $sum) 
                        : $sum)),
                'sumLastPayments' => $sum2,
            ];
        });

        $payments = PaymentResource::collection($payments);
            
        return compact(
            'count', 
            'count_percent', 
            'revenue', 
            'revenue_percent', 
            'chart_data', 
            'payments'
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
        
        $thisPeriod = new Carbon($date);
        $now = Carbon::now();

        //@TODO: optimize queries, getting too many results in single trip
        switch($type) {
            case "yearly":
                $orders = Order::with('contents')
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "monthly":
                $orders = Order::with('contents')
                    ->whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();

                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')
                    ->whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "daily":
            default:
                $orders = Order::with('contents')->whereDate('created_at', $thisPeriod)->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')->whereDate('created_at', $lastPeriod)->get();
        }

        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $sales = $orders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $lastCount = $lastOrders->count();
        $lastCount_items = $lastOrders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty, 0), 0);
        $lastSales = $lastOrders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $count_percent = $lastCount ? (floatval($count - $lastCount)/floatval($lastCount)) * 100.0 : 0;
        $count_items_percent = $lastCount_items ? (floatval($count_items - $lastCount_items)/floatval($lastCount_items)) * 100.0 : 0;
        $sales_percent = $lastSales ? (floatval($sales - $lastSales)/floatval($lastSales)) * 100.0 : 0;

        $intervals = self::intervals($thisPeriod->copy()->startOf(self::$typeItemNames[$type]), $type);
        $chart_data = $intervals->map(function($interval) use ($orders, $func) {
            return [
                'interval' => $interval,
                'orders' => $orders->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0),
            ];
        });

        $intervals = self::intervals($lastPeriod->copy(), $type);
        $chart_data = $chart_data->map(function($chart_row, $index) use ($intervals, $lastOrders, $func) {
            $row = $chart_row;
            $row['lastOrders'] =  $lastOrders->filter(function($item) use ($intervals, $index) {
                return $item->created_at->isBetween(...$intervals[$index]);
            })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

            return $row;

        });

        $sum = 0;
        $sum2 = 0;
        $hour = 0;

        $chart_data = $chart_data->map(function($data) use (&$sum, &$sum2, &$hour, $now, $thisPeriod, $type) {
            $sum = $sum + $data['orders'];
            $sum2 = $sum2 + $data['lastOrders'];
            return [
                'hours' => self::intervalLabel($hour++, $type),
                'sumOrderGrandTotal' => ($type == "yearly"
                    ? (($now->year <= $thisPeriod->year) && ($hour > ($now->month)) ? null : $sum) 
                    :($thisPeriod->isSameDay($now) 
                        ? ($hour > ($now->hour + 1) ? null : $sum) 
                        : $sum)),
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

    public static function summary(string $type, string $date)
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
        
        $thisPeriod = new Carbon($date);
        $now = Carbon::now();

        //@TODO: optimize queries, getting too many results in single trip
        switch($type) {
            case "yearly":
                $orders = Order::with('contents')
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $expenses = Expense::whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $payments = Payment::whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                $lastExpenses = Expense::whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                $lastPayments = Payment::whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "monthly":
                $orders = Order::with('contents')->whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $expenses = Expense::whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();
                $payments = Payment::whereMonth('created_at', '' . $thisPeriod->month)
                    ->whereYear('created_at', '' . $thisPeriod->year)
                    ->get();

                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')->whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                $lastExpenses = Expense::whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                $lastPayments = Payment::whereMonth('created_at', '' . $lastPeriod->month)
                    ->whereYear('created_at', '' . $lastPeriod->year)
                    ->get();
                break;
            case "daily":
            default:
                $orders = Order::with('contents')->whereDate('created_at', $thisPeriod)->get();
                $expenses = Expense::whereDate('created_at', $thisPeriod)->get();
                $payments = Payment::whereDate('created_at', $thisPeriod)->get();
                
                $lastPeriod = new Carbon($date)->sub(self::$typeItemNames[$type], 1)->startOf(self::$typeItemNames[$type]);
                $lastOrders = Order::with('contents')->whereDate('created_at', $lastPeriod)->get();
                $lastExpenses = Expense::whereDate('created_at', $lastPeriod)->get();
                $lastPayments = Payment::whereDate('created_at', $lastPeriod)->get();
        }

        $ordersCount = $orders->count();
        $expensesCount = $expenses->count();
        $paymentsCount = $payments->count();

        $revenue = $payments->reduce(fn($carry, $item) => $carry + $item->amount, 0);
        $totalExpenses = $expenses->reduce(fn($carry, $item) => $carry + $item->amount, 0);
        $totalSales = $orders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);

        $lastOrdersCount = $lastOrders->count();
        $lastExpensesCount = $lastExpenses->count();
        $lastPaymentsCount = $lastPayments->count();

        $lastRevenue = $lastPayments->reduce(fn($carry, $item) => $carry + $item->amount, 0);
        $lastTotalExpenses = $lastExpenses->reduce(fn($carry, $item) => $carry + $item->amount, 0);
        $lastTotalSales = $lastOrders->map($func)->reduce(fn($carry, $item) => $carry + $item, 0);
        
        
        $ordersCountPercent = $lastOrdersCount ? (floatval($ordersCount - $lastOrdersCount)/floatval($lastOrdersCount)) * 100.0 : 0;
        $expensesCountPercent = $lastExpensesCount ? (floatval($expensesCount - $lastExpensesCount)/floatval($lastExpensesCount)) * 100.0 : 0;
        $paymentsCountPercent = $lastPaymentsCount ? (floatval($paymentsCount - $lastPaymentsCount)/floatval($lastPaymentsCount)) * 100.0 : 0;
        
        $revenue_percent = $lastRevenue ? (floatval($revenue - $lastRevenue)/floatval($lastRevenue)) * 100.0 : 0;
        $sales_percent = $lastTotalSales ? (floatval($totalSales - $lastTotalSales)/floatval($lastTotalSales)) * 100.0 : 0;
        $expense_percent = $lastTotalExpenses ? (floatval($totalExpenses - $lastTotalExpenses)/floatval($lastTotalExpenses)) * 100.0 : 0;
        

        $intervals = self::intervals($thisPeriod->copy()->startOf(self::$typeItemNames[$type]), $type);
        // return [
        //         'orders' => $orders,
        //         'payments' => $payments,
        //         'expenses' => $expenses,
        //         'intervals' => $intervals
        // ];
        $chart_data = $intervals->map(function($interval) use ($payments, $orders, $expenses, $func) {
            
            
            
            return [
                'interval' => $interval,
                'orders' => $orders->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map($func)->reduce(fn($carry, $item) => $carry + $item, 0),
                'payments' => $payments->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map(fn($payment) => $payment->amount)->reduce(fn($carry, $item) => $carry + $item, 0),
                'expenses' => $expenses->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map(fn($expense) => $expense->amount_local)->reduce(fn($carry, $item) => $carry + $item, 0),
            ];
        });

        $sum = 0;
        $sum2 = 0;
        $sum3 = 0;
        $hour = 0;

        $chart_data = $chart_data->map(function($data) use (&$sum, &$sum2, &$sum3, &$hour, $now, $thisPeriod, $type) {
            $sum = $sum + $data['orders'];
            $sum2 = $sum2 + $data['payments'];
            $sum3 = $sum3 + $data['expenses'];
            return [
                'hours' => self::intervalLabel($hour++, $type),
                'periodSales' => $data['orders'],
                'periodRevenue' => $data['payments'],
                'periodExpense' => $data['expenses'],
                'sumSales' => $sum,
                'sumRevenue' => $sum2,
                'sumExpenses' => $sum3
            ];
        });

        $orders = OrderResource::collection($orders);
        $payments = PaymentResource::collection($payments);
        $expenses = ExpenseResource::collection($expenses);

            
        return compact(
            'orders',
            'payments',
            'expenses',
            'ordersCount',
            'expensesCount',
            'paymentsCount',

            'ordersCountPercent',
            'expensesCountPercent',
            'paymentsCountPercent',

            'revenue',
            'totalSales',
            'totalExpenses',

            'revenue_percent',
            'sales_percent',
            'expense_percent',

            'chart_data', 
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
            case "yearly":
                $months = [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ];

                return $months[$n];
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