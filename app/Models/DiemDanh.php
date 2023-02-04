<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemDanh extends Model
{
    use HasFactory;
    protected $table = 'diem_danh';
    protected $fillable = [];
    public $timestamps = false;
}
