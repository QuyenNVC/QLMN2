<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DanhMucCoSoVatChat extends Model
{
    use HasFactory;
    protected $table = "danh_muc_co_so_vat_chat";
    public $timestamps = false;
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('danh_muc_co_so_vat_chat.id_coso', $id_coso) : parent::newQuery();
    }
}
