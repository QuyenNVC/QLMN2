<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TangGiamLuongNhanVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tang_giam_luong_nhan_vien', function (Blueprint $table) {
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
        Schema::dropIfExists('tang_giam_luong_nhan_vien');
    }
}
