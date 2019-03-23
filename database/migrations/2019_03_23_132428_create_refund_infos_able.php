<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundInfosAble extends Migration
{
    /**
     * 退款信息
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_infos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('operate_id')->index()->comment("发起退款人");
            $table->integer('order_items')->index()->comment("退款商品");
            $table->decimal('amount',10,4)->default(0)->comment("退款金额");
            $table->string('phone',18)->comment("退款联系人手机号码");

            $table->tinyInteger("refund_reason")->default(1)->comment("1=未按时发货，1=拍错了、多拍，不喜欢，3=协商不一致退款，4其他");
            $table->string('refund_remark',225)->nullable()->comment("退款备注");
            $table->jsonb('attachments')->nullable()->comment("上传凭证");

            $table->tinyInteger('refund_type')->default(0)->comment("退款类型，1=买家发起的退款，2=商家发起的退款");

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
        Schema::dropIfExists('refund_infos');
    }
}
