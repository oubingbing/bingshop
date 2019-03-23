<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundConsultLogsTable extends Migration
{
    /**
     * 退款协商日志，退款只能退订单中的某个商品
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_consult_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('refund_info_id')->index()->comment("所属退款操作");
            $table->integer('operate_id')->index()->comment("操作人");
            $table->tinyInteger('type')->default(1)->comment('1=商家，2=买家');
            $table->tinyInteger('status')->default(1)->comment("1=申请退款，2=统一退款，3=拒绝退款，4=协商完成");

            $table->string('refund_remark',225)->nullable()->comment("退款备注");
            $table->jsonb('attachments')->nullable()->comment("上传凭证");

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
        Schema::dropIfExists('refund_consult_logs');
    }
}
