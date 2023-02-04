<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TangGiamLuong extends Model
{
    use HasFactory;
    protected $table = 'tang_giam_luong';
    protected $fillable = ['id', 'name', 'tang_giam', 'ghi_chu'];
    public $timestamps = false;
}
