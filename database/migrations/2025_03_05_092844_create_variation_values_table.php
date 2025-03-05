<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('variation_id')->nullable();
            $table->text('value'); // Ví dụ: 'S', 'L', '0%', '50%'
            $table->decimal('price', 8, 2)->default(0); // Mỗi giá trị có thể có giá trị riêng
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
        Schema::dropIfExists('variation_values');
    }
}
