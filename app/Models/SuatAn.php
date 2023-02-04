<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SuatAn extends Model
{
    use HasFactory;
    protected $table = 'suat_an_dinh_duong';
    public $timestamps = false;
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('suat_an_dinh_duong.id_coso', $id_coso) : parent::newQuery();
    }
}
