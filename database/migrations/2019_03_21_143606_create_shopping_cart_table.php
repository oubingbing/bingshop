<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index()->comment("用户ID");
            $table->integer('sku_id')->index()->comment('商品规格');
            $table->integer('purchase_num')->default(0)->comment("购买数量");

            $table->tinyInteger('status')->default(1)->comment("购物车状态，1=正常状态，2=已加入订单，3=被用户清出购物车");

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
        Schema::dropIfExists('shopping_carts');
    }
}
