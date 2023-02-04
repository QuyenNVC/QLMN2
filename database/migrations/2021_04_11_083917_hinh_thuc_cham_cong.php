<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HinhThucChamCong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hinh_thuc_cham_cong', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('ngay_cong');
            $table->string('ghi_chu')->nullable();
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
        Schema::dropIfExists('hinh_thuc_cham_cong');
    }
}
