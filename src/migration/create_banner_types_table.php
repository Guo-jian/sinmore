<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('banner_id')->default(0)->index('banner_id')->comment('banner_id');
            $table->unsignedTinyInteger('type')->default(0)->index('type')->comment('1安卓,2ios,3小程序,4pc,5h5,6ipad');
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
        Schema::dropIfExists('banner_types');
    }
}
