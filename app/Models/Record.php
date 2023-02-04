<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Record extends Model
{
    use HasFactory;
    protected $table = 'enrollment_records';
    // protected $fillable = ['id', 'name'];
    public $timestamps = false;

    public function class()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'id_class');
    }

    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('enrollment_records.id_coso', $id_coso) : parent::newQuery();
    }
}
