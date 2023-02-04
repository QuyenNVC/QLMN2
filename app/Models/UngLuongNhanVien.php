<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UngLuongNhanVien extends Model
{
    use HasFactory;
    protected $table = 'ung_luong_nhan_vien';
    protected $fillable = [];
    public $timestamps = false;
}
