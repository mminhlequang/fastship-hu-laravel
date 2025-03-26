<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToProductsToppingsToppingsGroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Thêm cột 'name' vào bảng 'products'
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable();  // Hoặc có thể thay thế `nullable()` bằng `default('')` nếu cần
        });

        // Thêm cột 'name' vào bảng 'toppings'
        Schema::table('toppings', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        // Thêm cột 'name' vào bảng 'toppings_group'
        Schema::table('toppings_group', function (Blueprint $table) {
            $table->string('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa cột 'name' khỏi bảng 'products'
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // Xóa cột 'name' khỏi bảng 'toppings'
        Schema::table('toppings', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // Xóa cột 'name' khỏi bảng 'toppings_group'
        Schema::table('toppings_group', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
