<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Service\AddLog;
// use Illuminate\Support\Facades\Auth;

class NhanVien extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'nhan_vien';
    protected $fillable = [];
    // public $timestamps = false;
    protected $primaryKey = "ma_nv";  //trường khóa chính
    protected $keyType = 'string'; //kieur dữ liệu của trường đó

    public function department()
    {
        return $this->belongsTo(\App\Models\PhongBan::class, 'id_phong_ban');
    }
}
