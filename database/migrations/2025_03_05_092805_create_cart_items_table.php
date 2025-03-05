<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cart_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->double('price')->default(0);
            $table->integer('quantity')->default(1); // Thêm trường quantity với giá trị mặc định là 1
            $table->json('product')->nullable(); // JSON lưu các variation_value_id được chọn
            $table->json('variations')->nullable(); // JSON lưu các variation_value_id được chọn
            $table->json('toppings')->nullable(); // JSON lưu topping_ids được chọn
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
        Schema::dropIfExists('cart_items');
    }
}
