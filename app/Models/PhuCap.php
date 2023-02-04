<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuCap extends Model
{
    use HasFactory;
    protected $table = 'phu_cap';
    protected $fillable = ['id', 'name', 'phu_cap', 'don_vi_tinh', 'ghi_chu'];
    public $timestamps = false;
}
