<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable(); // ID người dùng thực hiện giao dịch
            $table->integer('type')->nullable()->comment('1:Nạp tiền, 2:Mua hàng'); // Loại giao dịch (nạp tiền hoặc mua hàng)
            $table->double('price')->default(0)->comment('Số tiền giao dịch'); // Số tiền giao dịch
            $table->string('payment_method')->nullable(); // Phương thức thanh toán (nếu có)
            $table->integer('order_id')->nullable(); // Mã đơn hàng (nếu là giao dịch mua hàng)
            $table->text('description')->nullable(); // Mô tả chi tiết giao dịch
            $table->timestamp('transaction_date')->nullable(); // Thời gian giao dịch
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending'); // Trạng thái giao dịch
            $table->dateTime('deleted_at')->nullable(); // Các trường created_at, updated_at
            $table->timestamps(); // Các trường created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
