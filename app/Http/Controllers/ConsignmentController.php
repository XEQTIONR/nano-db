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
        $consignment = parent::store($request);

        return redirect(route('consignments.index'))
            ->with('notification', [
                'message' => 'New Consignment ' . $consignment->BOL . ' created',
                'link' => route('consignments.show', ['consignment' => $consignment]),
                'selected_value' => $consignment->BOL,
                'selected_key' => 'bol'
            ]);
    }
}
