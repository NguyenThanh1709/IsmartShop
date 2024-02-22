<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->comment('Mã đơn hàng');
            $table->foreign('order_id')->references('id')->on('Orders')->onDelete('cascade');
            $table->unsignedBigInteger('p_money')->nullable()->comment('Số tiền thanh toán');
            $table->string('p_note')->nullable()->comment('Nội dung thanh toán');
            $table->string('p_vpn_response_code', 255)->nullable()->comment('Mã phản hồi');
            $table->string('p_code_vnpay', 255)->nullable()->comment('Mã giao dịch vnpay');
            $table->string('p_code_bank', 255)->nullable()->comment('Mã giao dịch vnpay');
            $table->dateTime('p_time')->nullable()->comment('Thời gian thanh toán');
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
        Schema::dropIfExists('payments');
    }
}
