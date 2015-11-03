<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatUsersTable extends Migration {


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_users', function(Blueprint $table){
            $table->string('openid');
            $table->primary('openid');
            $table->string('token', 48);
            $table->unique('token');
            $table->boolean('subscribe');
            $table->string('unionid')->nullable();
            $table->string('nickname');
            $table->tinyInteger('sex');
            $table->string('language');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('headimgurl');
            $table->integer('subscribe_time');
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
        Schema::drop('wechat_users');
    }

}
