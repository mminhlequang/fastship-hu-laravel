<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(NULl)->comment('Người được đánh giá');
            $table->integer('creator_id')->default(NULl)->comment('Người gửi đánh giá');
            $table->integer('star')->default(0)->comment('SỐ sao đánh giá');
            $table->longText('content')->default(NULl);
            $table->boolean('active')->default(0)->comment('0:Chua duyệt, 1:Đã duyệt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_rating');
    }
}
