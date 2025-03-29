<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(); // Cột tên kênh
            $table->string('type', 50)->nullable(); // Cột loại kênh
            $table->string('url', 500)->nullable(); // Cột URL (nullable)
            $table->string('phone_number', 20)->nullable(); // Cột số hotline (nullable)
            $table->string('icon')->nullable(); // Cột icon (nullable)
            $table->integer('is_for_driver')->default(0); // Cột icon (nullable)
            $table->integer('is_for_partner')->default(0); // Cột icon (nullable)
            $table->integer('is_for_customer')->default(0); // Cột icon (nullable)
            $table->integer('arrange')->nullable(); // Cột icon (nullable)
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
        Schema::dropIfExists('support_channels');
    }
}
