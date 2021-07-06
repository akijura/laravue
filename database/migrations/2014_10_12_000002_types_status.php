<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypesStatus extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->nullable(false);
            $table->longText('description')->nullable(false);
            $table->integer('main_status_id')->nullable(false);
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
        Schema::dropIfExists('types_status');
    }
}
