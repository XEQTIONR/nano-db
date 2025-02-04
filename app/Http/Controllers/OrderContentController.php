<?php

namespace App\Http\Controllers;

use App\Models\Order_content;
use Illuminate\Http\Request;

class OrderContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        //

        $order_contents = Order_content::all();

        return $order_contents;
        //return view('order_contents'. compact('order_contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order_contents  $order_contents
     * @return \Illuminate\Http\Response
     */
    public function show(Order_contents $order_contents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order_contents  $order_contents
     * @return \Illuminate\Http\Response
     */
    public function edit(Order_contents $order_contents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order_contents  $order_contents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order_contents $order_contents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order_contents  $order_contents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order_contents $order_contents)
    {
        //
    }
}
