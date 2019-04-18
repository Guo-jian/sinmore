<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('user_id')->comment('用户id')->default(0);
            $table->unsignedInteger('admin_id')->index('admin_id')->comment('管理员id')->default(0);
            $table->unsignedTinyInteger('status')->index('status')->comment('状态:0为未处理,1为已处理')->default(0);
            $table->string('content')->comment('内容')->default('');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `contacts` comment'联系我们表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
