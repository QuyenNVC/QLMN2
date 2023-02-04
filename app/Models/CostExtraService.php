<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostExtraService extends Model
{
    use HasFactory;
    protected $table = 'cost_extra_service';
    public $timestamps = false;
}
