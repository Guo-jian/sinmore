<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFreezesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_freezes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('user_id')->comment('用户id')->default(0);
            $table->unsignedInteger('days')->comment('冻结天数')->default(0);
            $table->unsignedTinyInteger('status')->comment('0临时冻结,1解冻,2永久冻结')->default(0);
            $table->string('remark')->comment('备注')->default('');
            $table->unsignedInteger('admin_id')->comment('管理员id')->default(0);
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `user_freezes` comment'用户冻结历史表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_freezes');
    }
}
