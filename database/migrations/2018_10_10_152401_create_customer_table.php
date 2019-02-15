<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string("nickname",64)->default("")->unique()->comment("昵称");
            $table->string("avatar")->default("")->comment("头像");
            $table->string("email",128)->default("")->unique()->comment("手机号");
            $table->string("password",1024)->default("")->comment("密码");

            $table->string("salt",12)->default("")->comment("盐");
            $table->string("remember_token")->default()->comment("记住密码token");
            $table->timestamp("remember_token_expire")->nullable()->comment("token过期时间");

            $table->tinyInteger("type")->default(0)->comment("管理员类型，1=超级管理员，2=普通管理员");

            $table->timestamp('created_at')->nullable()->index();
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
        Schema::dropIfExists('customers');
    }
}
