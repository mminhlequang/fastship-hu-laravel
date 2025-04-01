<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('payment_id')->nullable();
            $table->decimal('price_tip', 15, 2)->default(0);
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->float('lat', 8, 6)->nullable();
            $table->float('lng', 8, 6)->nullable();
            $table->text('street')->nullable();
            $table->text('zip')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('country')->nullable();
            $table->text('country_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
