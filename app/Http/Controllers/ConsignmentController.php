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
    public function index()
    {
        $data = ConsignmentResource::collection(Consignment::with(['letterOfCredit'])->paginate(50));

        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('consignments.index'),
            'title' => 'Consignments',
            'type' => 'consignment',
        ]);  
    }
}
