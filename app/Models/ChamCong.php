<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamCong extends Model
{
    use HasFactory;
    protected $table = 'cham_cong';
    protected $fillable = [];
    public $timestamps = false;
}
