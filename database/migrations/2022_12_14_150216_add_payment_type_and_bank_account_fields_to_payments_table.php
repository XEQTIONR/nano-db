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
      Schema::table('payments', function( Blueprint $table ) {
        $table->string('type')->default('unknown');
        $table->unsignedInteger('account')->nullable()->default(null);
        $table->foreign('account')->references('id')->on('bank_accounts')
            ->onDelete('restrict');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('payments', function( Blueprint $table ) {
        $table->dropForeign('payments_account_foreign');
      });
      Schema::table('payments', function( Blueprint $table ) {
        $table->dropColumn([ 'type', 'account' ]);
      });
    }
};
