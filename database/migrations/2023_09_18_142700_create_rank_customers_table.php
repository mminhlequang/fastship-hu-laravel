<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->string('name_en')->nullable()->default(NULL);
            $table->integer('point')->nullable()->default(1);
            $table->integer('arrange')->nullable()->default(1);
            $table->integer('active')->nullable()->default(1);
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
        Schema::dropIfExists('rank_customers');
    }
}
