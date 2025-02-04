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
      DB::statement('ALTER TABLE `payments` CHANGE `invoice_num` `transaction_id`  INT( 10 ) NOT NULL AUTO_INCREMENT ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
      DB::statement('ALTER TABLE `payments` CHANGE `transaction_id` `invoice_num`  INT( 10 ) NOT NULL AUTO_INCREMENT ');
    }
};
