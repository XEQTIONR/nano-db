<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConsignmentResource;
use App\Models\Consignment;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        if ($sortBy == 'lc_num') {
            $sortBy = 'lc';
        }

        $data = ConsignmentResource::collection(
            Consignment::with(['letterOfCredit'])
                ->orderBy($sortBy, $sortDir)
                ->paginate($perPage)
                ->withQueryString()
        );

        if ($sortBy == 'lc') {
            $sortBy = 'lc_num';
        }

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('consignments.index'),
            'title' => 'Consignments',
            'type' => 'consignment',
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);  
    }
}
