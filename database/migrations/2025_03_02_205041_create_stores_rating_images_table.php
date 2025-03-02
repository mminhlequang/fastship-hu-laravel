<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresRatingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_rating_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating_id')->default(NULl);
            $table->string('image')->default(NULl);
            $table->integer('type')->default(1)->comment('1:Image, 2:Video');
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
        Schema::dropIfExists('stores_rating_images');
    }
}
