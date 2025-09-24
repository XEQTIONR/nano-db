<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetailedStockResource;
use App\Http\Resources\TyreResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tyre;
use App\Models\Waste;
use App\Http\Controllers\Api\WasteController as ApiController;

class WasteController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => route('waste.create'),
            'link' => route('waste.index'),
            'title' => 'Waste',
            'type' => 'waste',
            'breadcrumbsLinks' => [
                ['title' => 'Consignments', 'href' => route('consignments.index')],
                ['title' => 'Waste', 'href' => route('waste.index')],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groupedConsignments = resolve(DetailedStockResource::class)
            ->whereRaw('(IFNULL(container_contents.supplied_qty, 0) - IFNULL(order_contents.ordered_qty, 0) - IFNULL(waste.wasted_qty, 0)) > 0')
            ->get()
            ->groupBy('BOL')
            ->map(function($each) {
                return $each->groupBy('Container_num');
            });

        $gc = resolve(DetailedStockResource::class)
            ->whereRaw('(IFNULL(container_contents.supplied_qty, 0) - IFNULL(order_contents.ordered_qty, 0) - IFNULL(waste.wasted_qty, 0)) > 0')
            ->get();

        $tyres =  TyreResource::collection(Tyre::whereIn('tyre_id', $gc->map(fn($each) => $each->tyre_id))->get());

        return Inertia::render('waste/create', compact('groupedConsignments', 'tyres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $total = parent::store($request);

        return redirect(route('waste.index'))->with('notification', [
            'message' => "Removed $total items from stock and added to waste."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
