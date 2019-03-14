<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger("goods_id")->index()->comment("所属商品");
            $table->decimal("price",10,4)->default(0)->comment("商品价格");
            $table->decimal("vip_price",10,4)->default(0)->comment("会员价格");
            $table->decimal("chalk_line_price",10,4)->default(0)->comment("划线价格");
            $table->decimal("cost_price",10,4)->default(0)->comment("商品的成本价格");
            $table->float("stock",10,4)->default(0)->comment("商品库存");

            $table->jsonb("attachments")->nullable()->comment("商品图片信息");

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
        Schema::dropIfExists('skus');
    }
}
