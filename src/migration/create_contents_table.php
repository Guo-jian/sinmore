<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->default('')->comment('标题');
            $table->string('desc')->default('')->comment('描述');
            $table->string('title')->default('')->comment('title');
            $table->string('description')->default('')->comment('description');
            $table->string('keywords')->default('')->comment('keywords');
            $table->text('content')->comment('内容');
            $table->unsignedInteger('view')->default(0)->comment('浏览量');
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
        Schema::dropIfExists('contents');
    }
}
