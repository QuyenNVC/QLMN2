<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NhaCungCap;
use Illuminate\Auth\Access\HandlesAuthorization;

class NhaCungCapPolicy
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

    public function save(User $user, NhaCungCap $item) {
        return $user->isSuperAdmin() || $user->id_coso === $item->id_coso; 
    }
}
