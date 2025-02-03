<?php

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
      DB::statement('ALTER TABLE `order_contents`
        DROP PRIMARY KEY, ADD PRIMARY KEY(Order_num, tyre_id, container_num, bol, unit_price)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
      DB::statement('ALTER TABLE `order_contents`
        DROP PRIMARY KEY, ADD PRIMARY KEY(Order_num, tyre_id, container_num, bol)');
    }
};
