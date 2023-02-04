<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doituong extends Model
{
    use HasFactory;
    protected $table = "doi_tuong_hoc_sinh";
    public $timestamps = false;
}
