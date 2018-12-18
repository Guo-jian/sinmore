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
            $table->unsignedInteger('pid')->default(0)->index('pid')->comment('上级id');
            $table->string('name', 15)->default('')->comment('名称');
            $table->unsignedInteger('sort')->default(0)->index('sort')->comment('排序值');
            $table->string('thumb')->default('')->comment('缩略图');
            $table->string('pic')->default('')->comment('推荐图');
            $table->unsignedTinyInteger('status')->default(1)->index('status')->comment('状态:1为正常,0为冻结');
            $table->unsignedTinyInteger('hot')->default(0)->index('hot')->comment('推荐值');
            $table->unsignedTinyInteger('level')->default(1)->comment('层级');
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
        Schema::dropIfExists('categories');
    }
}
