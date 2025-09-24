<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\StockController as ApiController;

class StockController extends ApiController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = parent::__invoke($request);

        return Inertia::render('common/index', [
            ...$data,
            'link' => route('stocks.index'),
            'title' => 'Inventory',
            'type' => 'stock',
            'breadcrumbsLinks' => [
                ['title' => 'Products', 'href' => route('tyres.index')],
                ['title' => 'Inventory', 'href' => route('stocks.index')],
            ],
        ]);
    }
}
