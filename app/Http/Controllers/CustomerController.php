<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Controllers\Api\CustomerController as ApiController;
use App\Http\Resources\CustomerResource;

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
            'addLink' => 'drawer',
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
        $customer = parent::store($request);

        return redirect(route('customers.index', [
            'sortBy' => 'created_at',
            'sortDir' => 'desc',
        ]))
            ->with('notification', [
                'message' => 'New Customer ID:: '. $customer->id . ' created.',
                'selected_value' => $customer->id,
                'selected_key' => 'id'
            ]);
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
    public function edit(Customer $customer, Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => 'drawer',
            'link' => route('customers.index'),
            'title' => 'Customers',
            'type' => 'customer',
            'edit' => new CustomerResource($customer)
        ]);   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $customer = parent::update($request, $customer);

        return redirect(route('customers.index'))->with([
            'notification' => [
                'message' => 'Customer ID: ' . $customer->id . " updated."
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
