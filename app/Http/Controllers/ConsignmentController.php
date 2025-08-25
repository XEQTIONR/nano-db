<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\ConsignmentController as ApiController;
use App\Models\Consignment;
use App\Models\Container;
use App\Models\ContainerContent;

class ConsignmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        if ($sortBy == 'lc_num') {
            $sortBy = 'lc';
        }

        $data = parent::index($request);

        if ($sortBy == 'lc') {
            $sortBy = 'lc_num';
        }

        return Inertia::render('common/index', [
            ...$data,
            'link' => route('consignments.index'),
            'title' => 'Consignments',
            'type' => 'consignment',
            'addLink' => route('consignments.create'),
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);  
    }

    public function create()
    {
        return Inertia::render('consignments/create');
    }

    public function store(Request $request)
    {
        $consignmentData = $request->consignment;
        $containerNums = collect($request->containers);
        $items = collect($request->items);

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

        return redirect(route('consignments.index'))
            ->with('notification', [
                'message' => 'New Consignment ' . $consignment->BOL . ' created',
                'link' => route('consignments.show', ['consignment' => $consignment]),
                'selected_value' => $consignment->BOL,
                'selected_key' => 'bol'
            ]);
    }
}
