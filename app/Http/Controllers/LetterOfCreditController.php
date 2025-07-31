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

        $data = LetterOfCreditResource::collection(
            LetterOfCredit::paginate($perPage)
                ->withQueryString()
        );
        
        return Inertia::render('common/index', [
            'items' => $data,
            'link' => route('lcs.index'),
            'title' => 'Letters of Credit',
            'type' => 'lc',
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
