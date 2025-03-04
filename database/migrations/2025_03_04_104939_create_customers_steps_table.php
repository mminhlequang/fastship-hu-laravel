<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('step_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->longText('comment')->nullable();
            $table->text('image')->nullable();
            $table->text('link')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->integer('review_id')->nullable();
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
        Schema::dropIfExists('customers_steps');
    }
}
