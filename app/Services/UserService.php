<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;


class UserService
{
    public static function getList($request) {
        $items = User::whereNull('isDeleted');
        $items = Auth::user()->id_coso ? $items->where('id_coso', Auth::user()->id_coso) : $items;
        $items = $items->get()->toArray();
        for($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $roles = UserRole::where('user_id', $item['id'])->select('role_id')->get();
            if($roles) {
                $role_ids = [];
                foreach($roles as $role) {
                    $role_ids[] = $role->role_id;
                }
                $items[$i]['role_ids'] = $role_ids;
            } else {
                $items[$i][$role_ids] = '';
            }

        }
        return $items;
    }
}
