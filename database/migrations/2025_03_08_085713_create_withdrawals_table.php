<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wallet_id')->nullable(); // Foreign key to wallets table
            $table->integer('user_id')->nullable(); // Foreign key to drivers table
            $table->decimal('amount', 10, 2); // Amount with 2 decimal places
            $table->string('currency')->default('usd'); // Amount with 2 decimal places
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending'); // Enum for status
            $table->timestamp('request_date')->useCurrent(); // Default current timestamp for request date
            $table->timestamp('processed_date')->nullable(); // Nullable processed date
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
        Schema::dropIfExists('withdrawals');
    }
}
