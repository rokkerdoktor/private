<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function(Blueprint $table)
		{
            $table->bigIncrements('id')->unsigned();
            $table->integer('title_id')->unsigned()->nullable();
            $table->string('comment',555)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('season')->unsigned()->nullable();
            $table->integer('episode')->unsigned()->nullable();
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
        Schema::drop('comment');
    }
}
