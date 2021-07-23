<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BasicStatus extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('basic_status_name')->nullable(false);
            $table->longText('basic_status_description')->nullable(false);
            $table->integer('author_id')->nullable(false);
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
        Schema::dropIfExists('basic_status');
    }
}