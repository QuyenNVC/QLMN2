<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Info extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "info";
    public function newQuery($excludeDeleted = true) {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery($excludeDeleted)
            ->where('info.id', $id_coso) : parent::newQuery($excludeDeleted);
    }
}
