<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Http\Resources\ExpenseResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage') ?? 50);

        $sortBy = $request->input('sortBy') ?? 'created_at';

        $sortDir = $request->input('sortDir') ?? 'desc';

        $filterStr = $request->input('filters') ?? "";


        if ($sortBy == 'amount_local') {
            $expenses = Expense::orderByRaw('(rate * amount) ' . $sortDir);
        } else {
            $expenses = Expense::orderBy($sortBy, $sortDir);
        }

        $data = ExpenseResource::collection($expenses->paginate($perPage)->withQueryString());

        return [
            'filters' => [],
            'items' => $data,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'breadcrumbsLinks' => [
                ['title' => 'Expenses', 'href' => route('expenses.index')],
            ],
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expensable_type' => [
                'required',
                Rule::in(Expense::EXPENSE_TYPES)
            ],
            'expensable_id' => 'nullable',
            'date' => 'required|date',
            'amount' => 'required|numeric|gt:0',
            'note' => 'required|min:3',
            'redacted' => 'required|boolean'
            
        ]);

        if ($validated['expensable_type'] === Expense::EXPENSABLE_DAILY) {
            $validated['expensable_id'] = (new Carbon($validated['date']))->toDateString();
        } 

        $expense = new Expense([
            'expensable_type' => $validated['expensable_type'],
            'expensable_id' => $validated['expensable_id'],
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'note' => $validated['note'],
            'redacted' => $validated['redacted'],
            'rate' => 1
        ]);

        $expense->save();

        return new ExpenseResource($expense);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
