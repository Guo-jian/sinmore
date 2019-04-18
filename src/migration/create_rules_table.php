<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->index('pid')->comment('所属父级id');
            $table->string('title', 50)->default('')->comment('权限标题');
            $table->string('rule', 50)->default('')->index('rule')->comment('规则名称:url');
            $table->string('desc', 50)->default('')->comment('规则描述');
            $table->unsignedTinyInteger('type')->default(1)->comment('状态:1主菜单,2子菜单,3url');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态:1为正常,0为无效');
            $table->string('path', 50)->default('')->comment('前台路由');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `rules` comment'权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
