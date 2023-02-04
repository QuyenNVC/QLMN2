<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuCapNhanVien extends Model
{
    use HasFactory;
    protected $table = 'phu_cap_nhan_vien';
    protected $fillable = [];
    public $timestamps = false;
}
