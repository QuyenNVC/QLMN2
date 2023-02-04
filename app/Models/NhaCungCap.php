<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NhaCungCap extends Model
{
    use HasFactory;
    protected $table = "nha_cung_cap";
    public $timestamps = false;
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('nha_cung_cap.id_coso', $id_coso) : parent::newQuery();
    }
}
