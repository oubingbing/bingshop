<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index()->comment('用户');
            $table->decimal('amount',10,4)->default(0)->comment("订单总金额");
            $table->decimal('actual_amount',10,4)->default(0)->comment('订单实际支付金额');

            $table->string('order_number',32)->unique()->index()->default("")->comment("订单号");
            $table->string('transaction_id',32)->nullable()->default("")->comment("微信支付订单号");
            $table->integer('user_address_id')->index()->comment("用户收货地址");

            $table->decimal('freight',10,4)->default(0)->comment("运费");
            $table->tinyInteger('free_shipping')->default(1)->comment('是否包邮，1=是，2=否');
            $table->tinyInteger('payment_type')->default(1)->comment("支付方式，1=微信支付，2=支付宝支付");
            $table->tinyInteger("status")->default(1)->comment("订单状态，1=未支付，2=已支付, 3=支付失败，4=代发货，5=配送中，6=退款中，7=已完成");
            $table->string('trade_state',32)->default("WAIT")->comment("WAIT-等待支付中,SUCCESS—支付成功,REFUND—转入退款,NOTPAY—未支付,CLOSED—已关闭");
            $table->tinyInteger('type')->default(1)->comment("订单类型，1=普通订单，2=优惠券订单，3=vip会员订单");

            $table->string('remark',255)->nullable()->comment("订单备注");
            $table->tinyInteger('user_type')->default(1)->comment("下单用户类型，1=微信小程序");

            $table->timestamp('paid_at')->nullable()->comment("支付时间");
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
