<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\StockResource;
use App\Http\Resources\TyreResource;
use App\Services\FilterService;
use App\Models\Tyre;

class TyreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accept = $request->header('Accept');

        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        if ($sortBy == 'id') {
            $sortBy = 'tyres.tyre_id';
        }

        $query = resolve(StockResource::class)
            ->orderBy($sortBy, $sortDir);

        if ($sortBy == 'tyres.tyre_id') {
            $sortBy = 'id';
        }


        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn(
                        $filters[$i][0] == 'id' ? "tyres.tyre_id" : $filters[$i][0], 
                        $filters[$i][2]
                    );
                } else {
                    $query = $query->where(
                        $filters[$i][0] == 'id' ? "tyres.tyre_id" : $filters[$i][0], 
                        $filters[$i][1],
                        $filters[$i][2]
                    );
                }
            }
        }

        $data =  TyreResource::collection(
            $query->paginate($perPage)->withQueryString()
        );

        if ($accept == 'application/json') {
            return $data;
        }

        return [
            'filters' => $filters,
            'items' => $data,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $brand = $request->brand;
        $size = $request->size;
        $pattern = $request->pattern;
        $lisi = $request->lisi;

        $tyre = new Tyre(compact('brand', 'size', 'pattern', 'lisi'));
        $tyre->save();

        return new TyreResource($tyre);
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
