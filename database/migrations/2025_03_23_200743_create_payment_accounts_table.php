<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id')->nullable()->comment('ID của đối tác, tài xế hoặc khách hàng'); // ID của đối tác, tài xế hoặc khách hàng
            $table->enum('account_type', ['bank', 'wallet'])->nullable()->comment('Loại tài khoản: Ngân hàng hoặc ví điện tử'); // Loại tài khoản
            $table->string('account_number')->nullable()->comment('Số tài khoản ngân hàng hoặc ví điện tử'); // Số tài khoản
            $table->string('account_name')->nullable()->comment('Tên chủ tài khoản'); // Tên chủ tài khoản
            $table->string('bank_name')->nullable()->comment('Tên ngân hàng (nếu là tài khoản ngân hàng)'); // Tên ngân hàng
            $table->integer('payment_wallet_provider_id')->nullable()->comment('Nhà cung cấp ví'); // Nhà cung cấp ví (PayPal, Revolut, Wise…)
            $table->string('currency', 10)->default('eur')->comment('Loại tiền tệ'); // Loại tiền tệ
            $table->integer('is_verified')->default(0)->comment('Trạng thái xác minh tài khoản'); // Trạng thái xác minh tài khoản
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
        Schema::dropIfExists('payment_accounts');
    }
}
