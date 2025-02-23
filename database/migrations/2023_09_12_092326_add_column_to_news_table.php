<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            //
            $table->integer('type_news')->nullable()->default(0)->comment('0: chung, 1: khách hàng, 2: tài xế');
            $table->string('title_en')->nullable()->default(NULL);
            $table->longText('content_en')->nullable()->default(NULL);
            $table->longText('description_en')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            //
            $table->integer('type_news')->nullable()->default(0)->comment('0: chung, 1: khách hàng, 2: tài xế');
            $table->string('title_en')->nullable()->default(NULL);
            $table->longText('content_en')->nullable()->default(NULL);
            $table->longText('description_en')->nullable()->default(NULL);
        });
    }
}
