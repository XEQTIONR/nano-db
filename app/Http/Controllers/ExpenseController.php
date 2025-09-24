<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

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

        return Inertia::render('common/index', [
            'filters' => [],
            'items' => $data,
            'addLink' => route('expenses.create'),
            'link' => route('expenses.index'),
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
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
