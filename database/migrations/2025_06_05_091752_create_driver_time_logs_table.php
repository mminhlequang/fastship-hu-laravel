<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_time_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id')->nullable();
            $table->date('date')->nullable();
            $table->integer('online_minutes')->default(0);
            $table->integer('active_minutes')->default(0);
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
        Schema::dropIfExists('driver_time_logs');
    }
}
