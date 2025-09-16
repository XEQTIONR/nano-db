<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StockResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FilterService;

class StockController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'in_stock';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        $query = resolve(StockResource::class);

        $query = $query->whereRaw('(IFNULL(supplied_qty,0) - IFNULL(ordered_qty,0) - IFNULL(wasted_qty,0)) > 0');

        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }
        }

        $supply = $query->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        return [
            'items' => StockResource::collection($supply),
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }
}
