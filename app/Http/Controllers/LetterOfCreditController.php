<?php

namespace App\Http\Controllers;

use App\Models\LetterOfCredit;
use App\Http\Resources\LetterOfCreditResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Api\LetterOfCreditController as ApiController;

class LetterOfCreditController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        
        $data = parent::index($request);
        
        return Inertia::render('common/index', [
            ...$data,
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
    public function show(LetterOfCredit $lc)
    {
        $data = parent::show($lc);
        //return $data;
        return Inertia::render('lcs/show', [
            'letterOfCredit' => $data
        ]);
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
