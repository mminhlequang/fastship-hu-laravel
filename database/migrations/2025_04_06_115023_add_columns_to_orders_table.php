<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('ship_distance')->nullable();         // Trường ship_distance kiểu integer
            $table->text('ship_estimate_time')->nullable();    // Trường ship_estimate_time kiểu string
            $table->text('ship_polyline')->nullable();         // Trường ship_polyline kiểu string
            $table->json('ship_here_raw')->nullable();           // Trường ship_here_raw kiểu json
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ship_distance', 'ship_estimate_time', 'ship_polyline', 'ship_here_raw']);
        });
    }
}
