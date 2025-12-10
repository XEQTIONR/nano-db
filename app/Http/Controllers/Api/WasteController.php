<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WasteResource;
use App\Models\Waste;
use App\Services\FilterService;
use Illuminate\Http\Request;


class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'id';

        $sortDir = $request->input('sortDir') ?? 'asc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        $query = Waste::with('tyre');

        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }

            $query = $query->select();
        }

        $data = WasteResource::collection(
            $query->orderBy($sortBy, $sortDir)->paginate($perPage)->withQueryString()
        );

        return [
            'items' => $data,
            'filters' => $filters,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $wasteInput = collect($request->waste);
        $wasteInput->each(function($waste) {
            $w = new Waste([
                'BOL' => $waste['bol'],
                'Container_num' => $waste['containerNum'],
                'tyre_id' => $waste['tyreId'],
                'qty' => $waste['qtyReturned'],
            ]);

            $w->save();
        });

        $total = $wasteInput->reduce(fn($carry, $current) => $carry + $current['qtyReturned'], 0);
    
        return $total;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
