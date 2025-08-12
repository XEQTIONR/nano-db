<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\LetterOfCredit;
use App\Http\Resources\LetterOfCreditResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FilterService;

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

        $filterStr = $request->input('filters') ?? "";

        $filters = FilterService::parse($filterStr);

        if ($sortBy == 'local_amount') {
            $query = LetterOfCredit::orderByRaw('foreign_amount * exchange_rate '. $sortDir);
        } elseif ($sortBy == 'total_expense') {
            $query = LetterOfCredit::orderByRaw('(foreign_expense * exchange_rate) + domestic_expense '. $sortDir);
        } else {
            $query = LetterOfCredit::orderBy($sortBy, $sortDir);
        }

        if ($filters->count() > 0) {
            $query = DB::connection(config('database.default'))
                ->query()
                ->fromSub($query, 'lcs');
            
            for ($i=0; $i<$filters->count(); $i++) {
                if (  strtoupper($filters[$i][1]) === 'IN' ) {
                    $query = $query->whereIn($filters[$i][0], $filters[$i][2]);
                } else {
                    $query = $query->where(...$filters[$i]);
                }
            }

            $query = $query->select();
        }

        $data = LetterOfCreditResource::collection(
            $query->orderBy($sortBy, $sortDir)->paginate($perPage)->withQueryString()
        );

        return [
            'items' => $data,
            'filters' => $filters,
        ];
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
