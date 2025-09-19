<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $todaysDate = Carbon::now();
        $orders = Order::with('contents')->whereDate('created_at', $todaysDate)->get();
        $payments = Payment::whereDate('created_at', $todaysDate)->get();
        $expenses = Expense::whereDate('date', $todaysDate)->get();

        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty), 0);
        $revenue = $payments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $expenditure = $expenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);
        $sales = $orders->map(function($order){ 
            $subTotal =  $order->contents->reduce(
                fn($carry, $content) => $carry + $content->item_total, 
                0
            );
            $grandTotal = $subTotal 
                - $order->discount_amount 
                + $order->tax_amount 
                + ($subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100.0)));
            
            return $grandTotal;
        })->reduce(fn($carry, $item) => $carry + $item, 0);

        $yesterDaysDate = Carbon::now()->subDay();
        $yesterdaysOrders = Order::with('contents')->whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysPayments = Payment::whereDate('created_at', $yesterDaysDate)->get();
        $yesterdaysExpenses = Expense::where('date', $yesterDaysDate)->get();
        

        $yesterdaysCount = $yesterdaysOrders->count();
        $yesterdaysCount_items = $yesterdaysOrders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty), 0);
        $yesterdaysRevenue = $yesterdaysPayments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $yesterdaysExpenditure = $yesterdaysExpenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);
        $yesterdaysSales = $yesterdaysOrders->map(function($order){ 
            $subTotal =  $order->contents->reduce(
                fn($carry, $content) => $carry + $content->item_total, 
                0
            );
            $grandTotal = $subTotal 
                - $order->discount_amount 
                + $order->tax_amount 
                + ($subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100.0)));
            
            return $grandTotal;
        })->reduce(fn($carry, $item) => $carry + $item, 0);

        $count_percent = $yesterdaysCount ? (floatval($count - $yesterdaysCount)/floatval($yesterdaysCount)) * 100.0 : 0;
        $count_items_percent = $yesterdaysCount_items ? (floatval($count_items - $yesterdaysCount_items)/floatval($yesterdaysCount_items)) * 100.0 : 0;
        $revenue_percent = $yesterdaysRevenue ?(floatval($revenue - $yesterdaysRevenue)/floatval($yesterdaysRevenue)) * 100.0 : 0;
        $expenditure_percent = $yesterdaysExpenditure ? (floatval($expenditure - $yesterdaysExpenditure)/floatval($yesterdaysExpenditure)) * 100.0 : 0;
        $sales_percent = $yesterdaysSales ? (floatval($sales - $yesterdaysSales)/floatval($yesterdaysSales)) * 100.0 : 0;
        
        $intervals = $this->intervals();
        $classified = $intervals->map(function($interval) use ($orders, $payments, $expenses) {
            return [
                'interval' => $interval,
                'orders' => $orders->filter(function($item) use ($interval) {
                    return $item->created_at->isBetween(...$interval);
                })->map(function($order){ 
                    $subTotal =  $order->contents->reduce(
                        fn($carry, $content) => $carry + $content->item_total, 
                        0
                    );
                    $grandTotal = $subTotal 
                        - $order->discount_amount 
                        + $order->tax_amount 
                        + ($subTotal * (1 + (($order->tax_percentage - $order->discount_percent)/100.0)));
                    
                    return $grandTotal;
                })->reduce(fn($carry, $item) => $carry + $item, 0),
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

        $classified = $classified->map(function($cla) use (&$sum, &$hour, &$sumPayments, &$sumExpenses, $now) {
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
                'sumOrderGrandTotal' => $now->hour < $hour ? null : $sum,
                'sumPayments' => $now->hour < $hour ? null : $sumPayments,
                'sumExpenses' => $now->hour < $hour ? null : $sumExpenses,
            ];
        });
        $now = $now->hour;
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
            'classified',
            'now'
        );
    }

    protected function intervals($count = 24) 
    {
        $length = 24/$count;

        $start = Carbon::now()->startOfDay();

        $k = 0;

        $intervals = collect([]);

        while ($k < $count) {
            $end = $start->copy()->addHours($length)->subSecond();
            $intervals->push([$start->copy(), $end->copy()]);
            $start = $start->addHours($length);
            $k++;
        }

        return $intervals;
    }
}
