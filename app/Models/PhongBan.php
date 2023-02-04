<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PhongBan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'phong_ban';
    protected $fillable = ['id', 'name', 'id_coso'];

    public function staffs()
    {
        return $this->hasMany(\App\Models\NhanVien::class);
    }

    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('phong_ban.id_coso', $id_coso) : parent::newQuery();
    }
}
