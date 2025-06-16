<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_method')->nullable()->after('total_amount');
        $table->string('payment_status')->nullable()->after('payment_method');
        $table->string('payment_id')->nullable()->after('payment_status');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['payment_method', 'payment_status', 'payment_id']);
    });
}

}
