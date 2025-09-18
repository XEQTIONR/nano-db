<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use App\Http\Controllers\Api\DashboardController as ApiController;

class DashboardController extends ApiController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return Inertia::render('dashboard', parent::__invoke());
    }
}
