<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasPermission($id_permission) {
        $roleIds = UserRole::where('user_id', $this->getKey())->get();
        $check = null;
        foreach($roleIds as $roleId) {
            $check = RolePermission::where('id_role', $roleId->role_id)->where('id_permission', $id_permission)->get()->first();
            if($check) {
                return true;
            }
        }
        return false;
    }

    public function isSuperAdmin() {
        $superAdmin = UserRole::where('user_id', $this->getKey())->where('role_id', env('ID_ROLE_SUPER_ADMIN'))->first();
        return $superAdmin ? true : false;
    }
}
