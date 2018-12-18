<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->default('')->comment('banner名称');
            $table->string('pic')->default('')->comment('banner图片');
            $table->unsignedInteger('hot')->default(0)->index('hot')->comment('推荐位');
            $table->unsignedInteger('sort')->default(0)->index('sort')->comment('排序');
            $table->unsignedTinyInteger('type')->default(0)->index('type')->comment('1为跳转,0为不跳转');
            $table->string('url')->default('')->comment('外链');
            $table->unsignedTinyInteger('status')->default(1)->index('status')->comment('状态:1为正常,0为冻结');
            $table->unsignedInteger('view')->default(0)->comment('真实点击量');
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
        Schema::dropIfExists('banners');
    }
}
