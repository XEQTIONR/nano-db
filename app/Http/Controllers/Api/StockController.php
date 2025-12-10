<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StockResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FilterService;
use Illuminate\Support\Facades\Log;

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

        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if ($filters[$i][0] == '*') {
                    // $query->whereAny(Customer::$searchable, $filters[$i][1], $filters[$i][2]);
                        $query->where(function($q) use ($filters, $i) {
                            $q->whereRaw('tyres.tyre_id LIKE ?', '%'.$filters[$i][2].'%');
                            $q->OrWhereRaw('brand LIKE ?', '%'.$filters[$i][2].'%');
                            $q->OrWhereRaw('size LIKE ?', '%'.$filters[$i][2].'%');
                            $q->OrWhereRaw('pattern LIKE ?', '%'.$filters[$i][2].'%');
                            $q->OrWhereRaw('lisi LIKE ?', '%'.$filters[$i][2].'%');
                            $q->OrWhereRaw('IFNULL(supplied_qty,0) - IFNULL(ordered_qty,0) - IFNULL(wasted_qty,0) LIKE ?', '%'.$filters[$i][2].'%');
                        });
                    
                } else if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query->where(...$filters[$i]);
                }
            }
        }

        $query->whereRaw('(IFNULL(supplied_qty,0) - IFNULL(ordered_qty,0) - IFNULL(wasted_qty,0)) > 0');

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
