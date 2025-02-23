<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreenStartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screen_starts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_en')->nullable()->default(NULL);
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable()->default(NULL);
            $table->integer('arrange')->nullable()->default(0);
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
        Schema::dropIfExists('screen_starts');
    }
}
