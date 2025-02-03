<?php

namespace App\Http\Controllers;

use App\Models\Hscode;
use Illuminate\Http\Request;

class HscodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hscodes = Hscode::all();
        return view('hscodes', compact('hscodes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('new_hscode');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hscode = new Hscode;

        $hscode->id = $request->inputHscode;

        $hscode->save();

        return redirect('/hscodes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hscode  $hscode
     * @return \Illuminate\Http\Response
     */
    public function show(Hscode $hscode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hscode  $hscode
     * @return \Illuminate\Http\Response
     */
    public function edit(Hscode $hscode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hscode  $hscode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hscode $hscode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hscode  $hscode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hscode $hscode)
    {
        //
    }
}
