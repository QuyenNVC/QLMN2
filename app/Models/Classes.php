<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Classes extends Model
{
    use HasFactory;
    protected $table = "class";
    public $timestamps = false;

    public function student()
    {
        return $this->hasMany(\App\Models\Record::class);
    }
    public function newQuery() {
        $id_coso = Auth::user()->id_coso;
        return $id_coso ? parent::newQuery()
            ->where('class.id_coso', $id_coso) : parent::newQuery();
    }

}
