<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
          $table->collation = 'utf8_unicode_ci';

          $table->increments('id');
          $table->bigInteger('order_num');
          $table->integer('tyre_id');
          $table->string('container_num', 20);
          $table->string('bol', 30);
          $table->decimal('unit_price', 7, 2);
          $table->integer('qty');
          $table->timestamps();


          $table->foreign(['order_num', 'tyre_id', 'container_num', 'bol', 'unit_price'])
            ->references(['Order_num', 'tyre_id', 'container_num', 'bol', 'unit_price'])
            ->on('order_contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returns');
    }
};
