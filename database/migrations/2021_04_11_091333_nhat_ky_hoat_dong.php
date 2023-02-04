<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NhatKyHoatDong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('table_name');
            $table->integer('id_user');
            $table->integer('id_table');
            $table->string('value_name_table')->nullable();
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
        Schema::dropIfExists('log');
    }
}
