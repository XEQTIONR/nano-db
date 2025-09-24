<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Http\Resources\TyreResource;
use App\Models\Tyre;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\TyreController as ApiController;

class TyreController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => 'drawer',
            'link' => route('tyres.index'),
            'title' => 'Products',
            'type' => 'tyre',
            'breadcrumbsLinks' => [
                ['title' => 'Products', 'href' => route('tyres.index')],
            ],
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
        $tyre = parent::store($request);

        return redirect(route('tyres.index'))
            ->with('notification', [
                'message' => 'Tyre ID: '. $tyre->tyre_id . ' created.',
                'selected_value' => $tyre->tyre_id,
                'selected_key' => 'id'
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
    public function edit(Request $request, Tyre $tyre)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => 'drawer',
            'link' => route('tyres.index'),
            'title' => 'Products',
            'type' => 'tyre',
            'edit' => new TyreResource($tyre), 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tyre $tyre)
    {
        $tyre = parent::update($request, $tyre);

        return redirect(route('tyres.index'))->with([
            'notification' => [
                'message' => 'Tyre ID:' . $tyre->tyre_id . " updated."
            ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
