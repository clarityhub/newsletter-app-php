<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type', 10)->default('github');
            $table->text('url');
            $table->text('html_url');
            $table->text('repository_url');
            $table->text('repository_title');
            $table->text('title');
            $table->text('description');
            $table->text('html_description');
            $table->text('html_short_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
