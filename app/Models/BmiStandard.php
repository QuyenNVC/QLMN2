<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmiStandard extends Model
{
    use HasFactory;
    protected $table = 'bmi_standard';
    public $timestamps = false;
}
