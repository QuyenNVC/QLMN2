<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PhongBan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhongBanPolicy
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

    public function save(User $user, PhongBan $item) {
        return $user->isSuperAdmin() || $user->id_coso === $item->id_coso; 
    }
}
