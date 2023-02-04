<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SuatAn;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuatAnPolicy
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

    public function save(User $user, SuatAn $item) {
        return $user->isSuperAdmin() || $user->id_coso === $item->id_coso; 
    }
}
