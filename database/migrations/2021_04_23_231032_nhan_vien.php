<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NhanVien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->string('ma_nv');
            $table->string('fullname');
            $table->string('gender');
            $table->date('birthday');
            $table->string('dan_toc');
            //$table->integer('id_chuc_vu');
            $table->string('cmnd');
            $table->string('noi_cap');
            $table->date('ngay_cap');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('luong_ngay');
            $table->string('address');
            $table->date('ngay_vao_lam');
            $table->date('ngay_nghi_lam')->nullable();
            $table->integer('id_phong_ban');
            $table->integer('status_nghi_viec');
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
        Schema::dropIfExists('nhan_vien');
    }
}
