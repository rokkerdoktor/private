<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FilmgoDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('titles', function(Blueprint $table)
        {
            $table->string('links_quality')->nullable();
            $table->string('links_language')->nullable();
            $table->integer('title_wviews')->nullable()->unsigned();
            $table->integer('title_wrating')->nullable()->unsigned();
            $table->integer('title_mviews')->nullable()->unsigned();
            $table->integer('title_mrating')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('titles', function($table)
        {
            $table->dropColumn('links_quality');
            $table->dropColumn('links_language');
            $table->dropColumn('title_wviews');
            $table->dropColumn('title_wrating');
            $table->dropColumn('title_mviews');
            $table->dropColumn('title_mrating');
        });
    }
}
