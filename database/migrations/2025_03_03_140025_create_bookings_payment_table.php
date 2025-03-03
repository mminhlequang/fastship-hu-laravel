<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable(); // Người thanh toán
            $table->integer('order_id')->nullable(); // Đơn hàng liên kết (nếu có)
            $table->string('stripe_payment_id')->nullable(); // Mã giao dịch của Stripe
            $table->decimal('amount', 15, 2); // Số tiền thanh toán
            $table->string('currency')->default('usd'); // Đơn vị tiền tệ
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Trạng thái
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
        Schema::dropIfExists('bookings_payment');
    }
}
