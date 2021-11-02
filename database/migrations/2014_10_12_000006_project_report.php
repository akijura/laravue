<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProjectReport extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->nullable(false);
            $table->integer('type_status')->nullable(false);
            $table->integer('user_id')->nullable(false);
            $table->integer('comment_id')->nullable(true);
            $table->integer('status_confirm')->default(0);
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
        Schema::dropIfExists('project_report');
    }
}