<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MonthlyFee extends Model
{
    use HasFactory;
    protected $table = "monthly_fee";
    public $timestamps = false;
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('monthly_fee.id_coso', $id_coso) : parent::newQuery();
    }
}
