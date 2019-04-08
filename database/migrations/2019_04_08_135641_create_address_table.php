<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index()->comment("用户");
            $table->string('receiver',32)->comment("收货人");
            $table->string('phone',32)->index()->comment("手机号码");
            $table->integer('province_id')->comment("省份");
            $table->integer('city_id')->comment("城市");
            $table->integer('country_id')->comment("县区");
            $table->string('detail_address',225)->nullable()->comment("详细地址");
            $table->tinyInteger('type')->default(1)->comment("1=默认，2=非默认");

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
        Schema::dropIfExists('address');
    }
}
