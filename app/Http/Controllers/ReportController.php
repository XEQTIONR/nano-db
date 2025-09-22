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
    public static $types = [
        'daily',
        'monthly',
        'yearly'
    ];

    public function sales(Request $request)
    {
        $type = $request->type ?? 'daily';
        $date = $request->date ? new Carbon($request->date) : Carbon::now();
        
        $data = ReportService::sales('daily', $date->toDateString());

        return Inertia::render('reports/sales', [
            'routeName' => 'reports.sales',
            'type' => $type,
            'date' => $date->toDateString(),
            ...$data
        ]);
    }
}
