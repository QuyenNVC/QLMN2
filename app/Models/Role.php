<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use HasFactory;
    protected $table = "roles";
    public $timestamps = false;
    public function newQuery() {
        $is_super_admin = Auth::user()->isSuperAdmin();
        // var_dump($is_super_admin);
        return $is_super_admin ? parent::newQuery() : parent::newQuery()->where('id', '<>', env("ID_ROLE_SUPER_ADMIN"));
    }
}
