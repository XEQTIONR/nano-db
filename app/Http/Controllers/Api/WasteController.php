<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Waste;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
