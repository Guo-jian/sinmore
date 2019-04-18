<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('version')->default(0)->index('version')->comment('版本号');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `ios` comment'ios版本表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ios');
    }
}
