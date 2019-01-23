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
            $table->string("phone",18)->default("")->unique()->comment("手机号");
            $table->string("password",1024)->default("")->comment("密码");

            $table->string("salt",12)->default("")->comment("盐");
            $table->string("remember_token")->default()->comment("记住密码token");

            $table->string("donation_qr_code")->default("")->comment("赞赏码");

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
