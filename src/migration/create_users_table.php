<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->index('name')->comment('用户名')->default('');
            $table->char('mobile', 11)->index('mobile')->comment('手机号')->default('');
            $table->unsignedTinyInteger('sex')->index('sex')->comment('性别:1男,2女,0未知')->default(0);
            $table->string('avatar')->comment('头像')->default('');
            $table->string('prov', 50)->comment('省')->default('');
            $table->string('city', 50)->comment('市')->default('');
            $table->string('area', 50)->comment('区')->default('');
            $table->date('birthday')->comment('生日')->nullable($value = true);
            $table->char('token', 32)->comment('token')->index('token')->default('');
            $table->char('password', 32)->comment('密码')->index('password')->default('');
            $table->timestamp('expired_at')->index('expired_at')->comment('token过期时间')->useCurrent();
            $table->unsignedTinyInteger('status')->index('status')->comment('0临时冻结,1正常,2永久冻结')->default(1);
            $table->date('froze_at')->comment('冻结时间')->nullable($value = true);
            $table->unsignedInteger('froze_days')->comment('冻结天数')->default(0);
            $table->string('openid', 32)->comment('openid')->index('openid')->default('');
            $table->string('unionid', 50)->comment('unionid')->index('unionid')->default('');
            $table->string('pushid', 50)->comment('pushid')->index('pushid')->default('');
            $table->ipAddress('last_login_ip')->comment('上次登录ip')->default('');
            $table->string('desc')->comment('描述')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
