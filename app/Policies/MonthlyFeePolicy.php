<?php

namespace App\Policies;

use App\Models\MonthlyFee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MonthlyFeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MonthlyFee  $monthlyFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MonthlyFee $monthlyFee)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MonthlyFee  $monthlyFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MonthlyFee $monthlyFee)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MonthlyFee  $monthlyFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MonthlyFee $monthlyFee)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MonthlyFee  $monthlyFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MonthlyFee $monthlyFee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MonthlyFee  $monthlyFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MonthlyFee $monthlyFee)
    {
        //
    }

    public function save(User $user, MonthlyFee $monthlyFee) {
        return $user->isSuperAdmin() || $user->id_coso === $monthlyFee->id_coso; 
    }
}
