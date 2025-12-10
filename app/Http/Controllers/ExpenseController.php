<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\ExpenseController as ApiController;

class ExpenseController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = parent::index($request);

        return Inertia::render('common/index', [
            ...$data,
            'addLink' => route('expenses.create'),
            'link' => route('expenses.index'),
            'title' => 'Expenses',
            'type' => 'expense',
            'types' => Expense::EXPENSABLE_LABELS,
            'breadcrumbsLinks' => [
                ['title' => 'Expenses', 'href' => route('expenses.index')],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('expenses/create', [
            'types' => Expense::EXPENSABLE_LABELS
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $expense = parent::store($request);

        return redirect(route('expenses.index'))->with('notification', [
            'message' => 'New expense ID:: '. $expense->id . ' of ৳'. $expense->amount_local .' created.',
            'selected_value' => $expense->id,
            'selected_key' => 'id'
        ]);
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
