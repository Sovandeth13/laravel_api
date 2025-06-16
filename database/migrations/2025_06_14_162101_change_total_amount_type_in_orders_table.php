<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalAmountTypeInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

   public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('total_amount')->change();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('total_amount', 10, 2)->change();
    });
}
}
