<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContainerResource;
use App\Models\Container;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\ContainerController as ApiController;

class ContainerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'breadcrumbsLinks' => [
                ['title' => 'Consignments', 'href' => route('consignments.index')],
                ['title' => 'Containers', 'href' => route('containers.index')],
            ],
            'link' => route('containers.index'),
            'title' => 'Containers',
            'type' => 'container',
        ]);  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(Container $container)
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
