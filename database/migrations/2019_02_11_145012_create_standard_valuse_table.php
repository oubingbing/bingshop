<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardValuseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_values', function (Blueprint $table) {
            $table->increments('id');

            //有可能有一些是没有规格的名称，只有规格值的情况，在手机端新建的时候可能没有规格名称，只有规格值
            $table->integer("standard_id")->index()->comment("所属规格");
            $table->string("value")->default("")->comment("规格值，如红色，绿色，32G，64G");

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
        Schema::dropIfExists('standard_values');
    }
}
