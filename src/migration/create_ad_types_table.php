<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_id')->default(0)->index('ad_id')->comment('广告图id');
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
        Schema::dropIfExists('ad_types');
    }
}
