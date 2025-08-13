<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Container;
use App\Http\Resources\ContainerResource;
use App\Services\FilterService;
use Illuminate\Support\Facades\DB;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'land_date';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        if ($sortBy == 'lc_num') {
            $sortBy = 'lc';
        }

        $query = Container::join('consignments', 'consignments.BOL', '=', 'consignment_containers.bol')
            ->select(
                'container_num',
                DB::raw('consignments.BOL AS bol'),
                'land_date',
                'consignments.lc',
                'consignment_containers.created_at'
            )
            ->orderBy($sortBy, $sortDir);

        if ($sortBy == 'lc') {
            $sortBy = 'lc_num';
        }
        
        if ($filters->count() > 0) {
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    if ($filters[$i][0] == 'bol') {
                        $query = $query->whereIn('consignment_containers.BOL', $filters[$i][2]);
                    } else if ($filters[$i][0] == 'lc_num') {
                        $query = $query->whereIn('consignments.lc', $filters[$i][2]);
                    } else {
                        $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                    }
                } else {
                    if ($filters[$i][0] == 'bol') {
                        $query = $query->where('consignment_containers.BOL', $filters[$i][1], $filters[$i][2]);
                    } else if ($filters[$i][0] == 'lc_num') {
                        $query = $query->where('consignments.lc', $filters[$i][1], $filters[$i][2]);
                    } else {
                        $query = $query->where(...$filters[$i]);
                    }
                }
            }
        }

        $data = ContainerResource::collection(
            $query->paginate($perPage)
                ->withQueryString()
            );

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Container $container)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Container $container)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Container $container)
    {
        //
    }
}
