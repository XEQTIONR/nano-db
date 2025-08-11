<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Controllers\Api\CustomerController as ApiController;

class CustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'link' => route('customers.index'),
            'title' => 'Customers',
            'type' => 'customer',
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
