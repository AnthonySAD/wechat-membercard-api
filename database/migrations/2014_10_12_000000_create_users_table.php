<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid',28);
            $table->string('name',50)->nullable();
            $table->string('avatar',255)->nullable();
            $table->string('subscribe',10)->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->string('language',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('province',50)->nullable();
            $table->string('country',50)->nullable();
            $table->string('privilege',255)->nullable();
            $table->enum('status',['normal','frozen'])->default('normal');

            $table->timestamps();
            $table->index('openid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
