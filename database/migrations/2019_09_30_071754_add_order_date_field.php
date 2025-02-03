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
      Schema::table('orders', function(Blueprint $table){

        $table->date('order_on')->nullable();
      });

      DB::statement('UPDATE `orders`
                      SET order_on = date(created_at)
                      WHERE ISNULL(order_on)');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
      Schema::table('orders', function(Blueprint $table){

        $table->dropColumn('order_on');
      });
    }
};
