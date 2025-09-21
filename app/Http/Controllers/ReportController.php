<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Order;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public static $intervals = [
        'hour',
        'day',
        'month',
        'year'
    ];

    public static $types = [
        'daily',
        'monthly',
        'yearly'
    ];

    public static $intervalMap = [
        'daily' => 'hour',
        'monthly' => 'day',
        'yearly' => 'month'
    ];

    public function sales(Request $request)
    {
        $type = $request->type ?? 'daily';
        $interval = self::$intervalMap[$type];
        
        $date = Carbon::now();

        $orders = Order::with(['contents'])
            ->whereDate('order_on', $date)
            ->get();


        return Inertia::render('reports/sales', [
            'type' => $type,
            'date' => $date->toDateString(),
            'other' => ReportService::dashboard()
        ]);
    }
}
