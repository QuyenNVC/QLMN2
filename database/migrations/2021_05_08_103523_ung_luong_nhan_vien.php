<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UngLuongNhanVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ung_luong_nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->string('tien_ung');
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
        Schema::dropIfExists('ung_luong_nhan_vien');
    }
}
