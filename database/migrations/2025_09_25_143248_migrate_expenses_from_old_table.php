<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $old = DB::table('consignment_expenses')
                    ->join('consignments', 'consignments.BOL', '=', 'consignment_expenses.BOL')
                    ->join('lcs', 'lc_num', '=', 'consignments.lc')
                    ->select([
                        'consignments.BOL', 
                        'expense_foreign', 
                        'expense_local', 
                        'lcs.exchange_rate', 
                        'expense_notes', 
                        'consignment_expenses.created_at', 
                        'lcs.currency_code'
                    ])
                    ->get();
        $mapped = [];

        $old->each(function($consignment_expense) use (&$mapped) {
            $arr = [];
            $default_fields = [
                'expensable_type' => 'App\Models\Consignment',
                'expensable_id' => $consignment_expense->BOL,
                'date' => explode(' ', $consignment_expense->created_at)[0],
                'note' => $consignment_expense->expense_notes,
                'created_at' => $consignment_expense->created_at,
                'updated_at' => Carbon::now()
                
            ];

            if ($consignment_expense->expense_foreign > 0) {
                $arr [] = [
                    ...$default_fields,
                    'amount' => (floatval($consignment_expense->expense_foreign)),
                    'currency_code' => $consignment_expense->currency_code,
                    'rate' => $consignment_expense->exchange_rate,
                ];
            }

            if ($consignment_expense->expense_local > 0) {
                $arr [] = [
                    ...$default_fields, 
                    'amount' => '' . floatval($consignment_expense->expense_local),
                    'currency_code' => 'BDT',
                    'rate' => 1
                ];
            }

            array_push($mapped, ...$arr);
        });

        DB::table('expenses')->insert(($mapped));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
