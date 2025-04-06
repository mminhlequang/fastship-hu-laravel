<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_id')->nullable(); // ID cửa hàng
            $table->decimal('balance', 15, 2)->default(0); // Số dư ví cửa hàng
            $table->decimal('frozen_balance', 15, 2)->default(0); // Số dư ví cửa hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_wallets');
    }
}
