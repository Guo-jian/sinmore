<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('mobile', 11)->index('mobile')->comment('手机号')->default('');
            $table->char('code', 4)->index('code')->comment('验证码')->default('');
            $table->unsignedTinyInteger('status')->index('status')->comment('状态:1为未使用,0为已使用')->default(1);
            $table->timestamp('overdued_at')->comment('失效时间');
            $table->unsignedTinyInteger('type')->comment('类型:1注册登录,2找回密码,3绑定手机')->default(1);
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `codes` comment'验证码表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
}
