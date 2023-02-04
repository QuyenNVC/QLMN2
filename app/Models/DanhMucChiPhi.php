<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DanhMucChiPhi extends Model
{
    use HasFactory;
    protected $table = "danh_muc_chi_phi";
    public $timestamps = false;
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('danh_muc_chi_phi.id_coso', $id_coso) : parent::newQuery();
    }
}
