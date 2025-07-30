<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Http\Resources\TyreResource;
use App\Models\Tyre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TyreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = resolve(StockResource::class)
        ->orderByDesc('tyre_id')
        ->paginate(50);

        return Inertia::render('common/index', [
            'items' => TyreResource::collection($data),
            'link' => route('tyres.index'),
            'title' => 'Tyres',
            'type' => 'tyre',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return Inertia::render('lcs/create');
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
