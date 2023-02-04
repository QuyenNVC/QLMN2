<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostMonthlyFee extends Model
{
    use HasFactory;
    protected $table = 'cost_monthly_fee';
    public $timestamps = false;
}
