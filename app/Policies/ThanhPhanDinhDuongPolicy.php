<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ThanhPhanDinhDuong;

class ThanhPhanDinhDuongPolicy
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

    public function save(User $user, ThanhPhanDinhDuong $record) {
        return $user->isSuperAdmin() || $user->id_coso === $record->id_coso; 
    }
}
