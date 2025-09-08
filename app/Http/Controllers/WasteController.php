<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetailedStockResource;
use App\Http\Resources\TyreResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tyre;
use App\Models\Waste;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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

        return redirect(route('consignments.index'));
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
