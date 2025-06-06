<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toppings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name_vi')->nullable();
            $table->text('name_en')->nullable();
            $table->text('name_zh')->nullable();
            $table->text('name_hu')->nullable();
            $table->text('image')->nullable();
            $table->double('price')->default(0);
            $table->integer('store_id')->nullable();
            $table->integer('creator_id')->nullable()->comment('Người tạo');
            $table->integer('status')->default(0)->comment('1:Còn món, 0:Hết món');
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('toppings');
    }
}
