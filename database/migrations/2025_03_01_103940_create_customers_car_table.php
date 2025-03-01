<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_car', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name_vi')->nullable();
            $table->text('name_en')->nullable();
            $table->text('name_zh')->nullable();
            $table->text('name_hu')->nullable();
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
        Schema::dropIfExists('customers_car');
    }
}
