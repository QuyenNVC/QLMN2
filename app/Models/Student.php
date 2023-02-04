<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = "student";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $keyType = 'string';

    // public function class() {
    //     return $this->belongsTo('App\Models\Classes');
    // }

    // public function job() {
    //     return $this->belongsTo('App\Models\Job');
    // }

    // public function doituong() {
    //     return $this->belongsTo('App\Models\Doituong');
    // }
}
