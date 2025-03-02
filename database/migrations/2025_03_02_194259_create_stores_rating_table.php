<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_id')->default(NULl);
            $table->integer('user_id')->default(NULl);
            $table->integer('star')->default(0)->comment('SỐ sao đánh giá');
            $table->longText('content')->default(NULl);
            $table->boolean('active')->default(0)->comment('0:Chua duyệt, 1:Đã duyệt');
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
        Schema::dropIfExists('stores_rating');
    }
}
