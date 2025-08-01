<?php

namespace App\Http\Controllers;

use App\Models\LetterOfCredit;
use App\Http\Resources\LetterOfCreditResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LetterOfCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        if ($sortBy == 'local_amount') {
            $data = LetterOfCreditResource::collection(
            LetterOfCredit::orderByRaw('foreign_amount * exchange_rate '. $sortDir)
                ->paginate($perPage)
                ->withQueryString()
            );
        } elseif ($sortBy == 'total_expense') {
            $data = LetterOfCreditResource::collection(
            LetterOfCredit::orderByRaw('(foreign_expense * exchange_rate) + domestic_expense '. $sortDir)
                ->paginate($perPage)
                ->withQueryString()
            );
        } else {
            $data = LetterOfCreditResource::collection(
            LetterOfCredit::orderBy($sortBy, $sortDir)
                ->paginate($perPage)
                ->withQueryString()
            );
        }
        
        
        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('lcs.index'),
            'title' => 'Letters of Credit',
            'type' => 'lc',
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('lcs/create');
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
