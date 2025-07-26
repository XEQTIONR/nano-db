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
      DB::statement('ALTER TABLE `lcs` MODIFY notes VARCHAR(500) DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
      DB::statement('ALTER TABLE `lcs` MODIFY notes VARCHAR(500) NOT NULL');
    }
};
