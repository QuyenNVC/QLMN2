<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HinhThucDiemDanh;
use Illuminate\Auth\Access\HandlesAuthorization;

class HinhThucDiemDanhPolicy
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

    public function save(User $user, HinhThucDiemDanh $model) {
        return $user->isSuperAdmin() || $user->id_coso === $model->id_coso; 
    }
}
