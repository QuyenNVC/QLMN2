<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuanLyChi;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuanLyChiPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function save(User $user, QuanLyChi $item) {
        return $user->isSuperAdmin() || $user->id_coso === $item->id_coso; 
    }
}
