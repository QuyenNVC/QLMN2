<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChamCong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cham_cong', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->bigInteger('month');
            $table->bigInteger('year');
            $table->string('ma_nv');
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
        Schema::dropIfExists('cham_cong');
    }
}
