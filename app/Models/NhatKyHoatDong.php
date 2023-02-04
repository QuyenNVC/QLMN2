<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKyHoatDong extends Model
{
    use HasFactory;
    protected $table = 'log';
    protected $fillable = ['id', 'action', 'table_name', 'id_user', 'id_table', 'value_name_table'];
    public $timestamps = true;
}
