<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsRatingReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_rating_reply', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content')->nullable(); // Nội dung phản hồi
            $table->integer('user_id')->nullable(); // Khóa ngoại tới bảng users
            $table->integer('rating_id')->nullable(); // Khóa ngoại tới bảng comments
            $table->timestamps(); // timestamps: created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_rating_reply');
    }
}
