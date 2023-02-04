<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TangGiamLuongNhanVien extends Model
{
    use HasFactory;
    protected $table = 'tang_giam_luong_nhan_vien';
    protected $fillable = [];
    public $timestamps = false;
}
