<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');

            $table->string("name",128)->default("")->comment("商品名称");
            $table->string('describe',225)->nullable()->comment("商品描述");
            $table->string('share_describe',225)->nullable()->comment("分享描述");
            $table->jsonb("images_attachments")->nullable()->comment("商品图片");
            $table->jsonb("video_attachments")->nullable()->comment("商品视频信息");

            $table->tinyInteger("sku_type")->default(1)->comment("1=是否是多个sku,如果没有添加sku就是单个且sku表没有规格关联数据，2=多个sku,sku有关联规格数据");

            $table->tinyInteger("status")->default(0)->comment("商品状态，0=下架，1=在售");

            $table->tinyInteger("start_sale_type")->default(1)->comment("上架类型，1=立即上架销售，2=自定义上架时间，3=暂不售卖，放入仓库");
            $table->timestamp('start_selling_at')->nullable()->index()->comment("定时上架销售时间");

            $table->tinyInteger("stop_sale_type")->default(1)->comment("下架类型，1=售完即可下架，2=自定义下架时间，3=售完不下架");
            $table->timestamp('stop_selling_at')->nullable()->index()->comment("定时下架停止销售时间");

            $table->float("limit_purchase_num",8,4)->default(0)->comment("限制购买数量，0=无限制");

            $table->tinyInteger('distribution_mode')->default(1)->comment("配送方式，1=快递发货，2=同城配送，3=到店自提");
            $table->decimal("postage_cost",10,4)->default(0)->comment("邮费");

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
        Schema::dropIfExists('goods');
    }
}
