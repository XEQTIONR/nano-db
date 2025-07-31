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

        $data = ConsignmentResource::collection(
            Consignment::with(['letterOfCredit'])
            ->paginate($perPage)
            ->withQueryString()
        );

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('consignments.index'),
            'title' => 'Consignments',
            'type' => 'consignment',
        ]);  
    }
}
