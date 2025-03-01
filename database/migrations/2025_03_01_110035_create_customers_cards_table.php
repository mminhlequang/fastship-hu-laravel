<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_holder_name')->nullable();
            $table->string('card_brand')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('gateway')->nullable();
            $table->integer('card_exp_month')->nullable();
            $table->integer('card_exp_year')->nullable();
            $table->string('card_last4')->nullable();
            $table->boolean('set_as_default')->default(0); // `setAsDefault` là boolean
            $table->string('fingerprint')->nullable();
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
        Schema::dropIfExists('customers_cards');
    }
}
