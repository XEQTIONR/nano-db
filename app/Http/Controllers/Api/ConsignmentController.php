<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FilterService;
use App\Models\Consignment;
use App\Models\Container;
use App\Models\ContainerContent;
use App\Http\Resources\ConsignmentResource;
use Illuminate\Support\Facades\DB;

class ConsignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        $query = Consignment::with('letterOfCredit')
        ->orderBy($sortBy == 'lc_num' ? 'lc' : $sortBy, $sortDir);

        if ($filters->count() > 0) {
            
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0] == 'lc_num' ? 'lc' : $filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where($filters[$i][0] == 'lc_num' ? 'lc' : $filters[$i][0], $filters[$i][1], $filters[$i][2]);
                }
            }
        }

        $data = ConsignmentResource::collection((
            $query->paginate($perPage)->withQueryString()
        ));

        return [
            'items' => $data,
            'filters' => $filters,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $consignmentData = $request->consignment;
        $containerNums = collect($request->containers);
        $items = collect($request->items);

        DB::beginTransaction();
        $consignment = new Consignment([
            'BOL' => $consignmentData['bol'],
            'lc' => $consignmentData['lc'],
            'value' => $consignmentData['value'],
            'exchange_rate' => $consignmentData['exchange_rate'],
            'tax' => $consignmentData['tax'],
            'land_date' => $consignmentData['land_date'],
        ]);

        $consignment->save();

        $containers = $containerNums->map(fn($container) => new Container([
            'Container_num' => $container
        ]));

        $containers = $consignment->containers()->saveMany($containers);

        $containerContents = $items->map(fn($item) => new ContainerContent([
            'Container_num' => $item['container_num'],
            'BOL' => $item['bol'],
            'tyre_id' => $item['id'],
            'qty' => $item['qty'],
            'unit_price' => $item['unit_price'],
            'total_tax' => $item['total_tax'],
            'total_weight' => $item['total_weight']
        ]));

        $containers->each(function($container) use ($containerContents) {
            $contents = $containerContents->filter(fn($c) => $c->Container_num == $container->Container_num);
            $container->contents()->saveMany($contents);
        });

        DB::commit();

        return (new ConsignmentResource($consignment));
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignment $consignment)
    {
        $consignment->load('letterOfCredit', 'containers.contents.tyre');
        return new ConsignmentResource($consignment);
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
