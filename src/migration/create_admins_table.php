<?php

/*
 * This file is part of the mquery/sinmore.
 *
 * (c) guojian <n6878088@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account', 20)->index('account')->comment('帐号')->default('');
            $table->string('name', 20)->index('name')->comment('用户名')->default('');
            $table->string('password', 32)->comment('登录密码')->default('');
            $table->char('mobile', 11)->index('mobile')->comment('电话')->default('');
            $table->string('token', 32)->index('token')->comment('token')->default('');
            $table->ipAddress('last_login_ip')->comment('上次登录ip')->default('');
            $table->unsignedTinyInteger('status')->comment('状态:1为正常,0为冻结')->default(1);
            $table->unsignedInteger('group_id')->index('group_id')->comment('管理组id')->default(0);
            $table->timestamp('expired_at')->comment('失效时间')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
