<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->text('name')->nullable();
            $table->integer('sex')->default(1)->comment('1:Nam, 2:Nữ');
            $table->date('birthday')->nullable();
            $table->text('code_introduce')->nullable();
            $table->text('address')->nullable();
            $table->text('cccd')->nullable();
            $table->date('cccd_date')->nullable();
            $table->text('image_cccd_before')->nullable();
            $table->text('image_cccd_after')->nullable();
            $table->text('address_temp')->nullable();
            $table->integer('is_tax_code')->nullable();
            $table->text('tax_code')->nullable();
            $table->integer('payment_method')->nullable();
            $table->text('card_number')->nullable();
            $table->text('card_expires')->nullable();
            $table->text('card_cvv')->nullable();
            $table->longText('contacts')->nullable();
            $table->integer('car_id')->nullable();
            $table->text('license')->nullable()->comment('Biển số xe');
            $table->text('image_license_before')->nullable()->comment('Biển số xe');
            $table->text('image_license_after')->nullable()->comment('Biển số xe');
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
        Schema::dropIfExists('customers_profile');
    }
}
