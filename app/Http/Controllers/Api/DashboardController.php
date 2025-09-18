<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $todaysDate = Carbon::now()->toDateString();
        $orders = Order::with('contents')->where('order_on', $todaysDate)->get();
        $payments = Payment::whereDate('created_at', $todaysDate)->get();
        $expenses = Expense::where('date', $todaysDate)->get();
        

        $count = $orders->count();
        $count_items = $orders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty), 0);
        $revenue = $payments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $expenditure = $expenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);

        $todaysDate = Carbon::now()->subDay()->toDateString();
        $yesterdaysOrders = Order::with('contents')->where('order_on', $todaysDate)->get();
        $yesterdaysPayments = Payment::whereDate('created_at', $todaysDate)->get();
        $yesterdaysExpenses = Expense::where('date', $todaysDate)->get();

        $yesterdaysCount = $yesterdaysOrders->count();
        $yesterdaysCount_items = $yesterdaysOrders->reduce(fn($carry, $order) => $carry + $order->contents->reduce(fn($carry, $content) => $carry + $content->qty), 0);
        $yesterdaysRevenue = $yesterdaysPayments->reduce(fn($carry, $payment) => $carry + $payment->amount, 0);
        $yesterdaysExpenditure = $yesterdaysExpenses->reduce(fn($carry, $expense) => $carry + $expense->amount_local, 0);

        $count_percent = $yesterdaysCount ? (floatval($count - $yesterdaysCount)/floatval($yesterdaysCount)) * 100.0 : 0;
        $count_items_percent = $yesterdaysCount_items ? (floatval($count_items - $yesterdaysCount_items)/floatval($yesterdaysCount_items)) * 100.0 : 0;
        $revenue_percent = $yesterdaysRevenue ?(floatval($revenue - $yesterdaysRevenue)/floatval($yesterdaysRevenue)) * 100.0 : 0;
        $expenditure_percent = $yesterdaysExpenditure ? (floatval($expenditure - $yesterdaysExpenditure)/floatval($yesterdaysExpenditure)) * 100.0 : 0;

        return compact('count', 'count_items', 'revenue', 'expenditure', 'count_percent', 'count_items_percent', 'revenue_percent', 'expenditure_percent');
    }
}
