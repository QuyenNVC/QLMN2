<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhThucChamCong extends Model
{
    use HasFactory;
    protected $table = 'hinh_thuc_cham_cong';
    protected $fillable = ['id', 'name', 'ngay_cong', 'ghi_chu'];
    public $timestamps = false;
}

