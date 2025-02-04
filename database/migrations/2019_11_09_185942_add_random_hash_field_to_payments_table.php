<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
      Schema::table('payments', function(Blueprint $table){

        $table->string('random', 30)->nullable()->default(null)->after('payment_amount');

      });

      DB::statement(' UPDATE payments
                        SET random = (SELECT substring(MD5(RAND()), -22))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
      Schema::table('payments', function(Blueprint $table){

        $table->dropColumn('random');
      });
    }
};
