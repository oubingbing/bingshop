<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * 商品子订单
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("order_id")->index()->comment('所属订单');
            $table->integer("sku_id")->index()->comment("所属sku");

            $table->decimal('amount',10,4)->default(0)->comment("商品总金额");
            $table->decimal('actual_amount',10,4)->default(0)->comment('商品实际总金额');
            $table->float("quantity",2)->default(0)->comment("商品数量");

            $table->tinyInteger('status')->default(0)->comment("状态，1=正常状态，2=发起退款(退款中)，3=同意退款，4=拒绝退款，5=退款完成");

            $table->jsonb('sku_snapshot')->comment("商品快照");

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
        Schema::dropIfExists('order_items');
    }
}
