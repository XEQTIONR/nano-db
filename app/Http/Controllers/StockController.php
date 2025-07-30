<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Models\ContainerContent;
use App\Models\OrderContent;
use App\Models\Tyre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StockController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $orderBy = 'in_stock';
        
        $query = resolve(StockResource::class);

        $supply = $query->whereRaw('(supplied_qty - ordered_qty) > 0')
        ->orderByDesc($orderBy)
        ->paginate(50);

        return Inertia::render('common/index', [
            'items' => StockResource::collection($supply),
            'link' => route('stock.index'),
            'title' => 'Inventory',
            'type' => 'stock',
        ]);
    }
}
