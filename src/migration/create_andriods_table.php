<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAndriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('andriods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->default('')->comment('升级地址');
            $table->string('apk')->default('')->comment('安装包文件');
            $table->unsignedTinyInteger('version')->default(0)->index('version')->comment('版本号');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `andriods` comment'安卓版本表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('andriods');
    }
}
