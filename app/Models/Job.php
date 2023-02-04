<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $table = "job";
    public $timestamps = false;

    // public function students() {
    //     return $this->hasMany('App\Models\Student');
    // }
}
