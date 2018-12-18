<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->default(0)->index('category_id')->comment('分类id');
            $table->string('name',100)->default('')->comment('资讯名称');
            $table->string('author',20)->default('')->comment('作者');
            $table->string('desc')->default('')->comment('描述');
            $table->string('title')->default('')->comment('title');
            $table->string('description')->default('')->comment('description');
            $table->string('keywords')->default('')->comment('keywords');
            $table->string('thumb')->default('')->comment('封面图');
            $table->string('pic')->default('')->comment('推荐图');
            $table->unsignedInteger('hot')->default(0)->index('hot')->comment('推荐位');
            $table->unsignedInteger('sort')->default(0)->index('sort')->comment('排序');
            $table->unsignedInteger('click')->default(0)->comment('附加点击量');
            $table->unsignedInteger('view')->default(0)->comment('真实点击量');
            $table->unsignedTinyInteger('status')->default(1)->index('status')->comment('状态:1为正常,0为冻结');
            $table->unsignedTinyInteger('top')->default(0)->index('top')->comment('状态:1为置顶,0为未置顶');
            $table->string('content')->comment('内容');
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
        Schema::dropIfExists('infos');
    }
}
