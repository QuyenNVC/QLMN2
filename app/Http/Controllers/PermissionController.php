<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function getList(Request $request) {
        try {
            $items = Permission::select(['id', 'name'])->get();
            $data = [
                'status'    =>  true,
                'items'     =>  $items
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return response()->json($data);
    }
}
