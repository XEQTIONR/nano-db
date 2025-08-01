<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContainerResource;
use App\Models\Container;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        if ($sortBy == 'lc_num') {
            $sortBy = 'lc';
        }

        $data = ContainerResource::collection(
            Container::join('consignments', 'consignments.BOL', '=', 'consignment_containers.BOL')
                ->select(
                    'container_num',
                    'consignments.bol',
                    'land_date',
                    'consignments.lc',
                    'consignment_containers.created_at'
                )
                ->orderBy($sortBy, $sortDir)
                ->paginate($perPage)
                ->withQueryString()
        );

        if ($sortBy == 'lc') {
            $sortBy = 'lc_num';
        }

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('containers.index'),
            'title' => 'Containers',
            'type' => 'container',
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
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
