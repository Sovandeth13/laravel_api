<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAmountToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
// php artisan make:migration change_total_amount_type_in_orders_table --table=orders

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
