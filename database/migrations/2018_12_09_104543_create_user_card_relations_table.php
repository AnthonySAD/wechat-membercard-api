<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCardRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_card_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('card_id');
            $table->integer('rank')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('card_id');
            $table->index('rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_card_relations');
    }
}
