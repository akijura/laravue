<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Projects extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->nullable(false);
            $table->longText('description')->nullable(false);
            $table->date('begin_date');
            $table->date('end_date');
            $table->integer('main_status_id')->nullable(false);
            $table->integer('type_status')->nullable(false);
            $table->integer('author_id')->nullable(false);
            $table->integer('basic_status')->nullable(false);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('projects');
    }
}