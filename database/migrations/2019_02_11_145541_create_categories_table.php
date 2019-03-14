<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string("admin_id")->index()->comment("新建人");

            $table->string("name")->default("")->comment("分类名称");
            $table->jsonb("attachments")->nullable()->comment("分类图片");

            $table->tinyInteger("status")->default(1)->comment("状态");
            $table->tinyInteger('type')->default(1)->comment("类型");
            $table->tinyInteger("sort")->default(1)->comment("排序");

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
        Schema::dropIfExists('categories');
    }
}
