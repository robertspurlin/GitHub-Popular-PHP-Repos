<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            /**
             * I didn't use id() here because I don't want it to autoincrement.
             * It will represent the repository ID from GitHub instead.
             * 
             * Using the same names as the GitHub response defined here - https://docs.github.com/en/rest/reference/search#search-repositories
             */
            $table->integer('id')->unique();
            $table->string('name');
            $table->string('url');
            $table->timestamp('created_at');
            $table->timestamp('pushed_at');
            $table->longText('description')->nullable();
            $table->integer('stargazers_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repositories');
    }
}
