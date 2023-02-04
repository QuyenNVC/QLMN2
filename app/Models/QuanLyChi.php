<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class QuanLyChi extends Model
{
    use HasFactory;
    protected $table = "quan_ly_chi";
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('quan_ly_chi.id_coso', $id_coso) : parent::newQuery();
    }
}
