<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\RolePermission;
use App\Service\AddLog;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    private $table = 'roles';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role');
    }

    public function getList() {
        try {
            $items = Role::all();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $item = new Role();
            $item->name = $request->name;
            $item->save();
            
            AddLog::addLog("create", $this->table, $item->id, $item->name, Auth::id());
            $data = [
                'status' => true,
            ];
            return json_encode($data);
        
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $item = Role::where('id', $request->id)->first();
            if ( $item ) {
                AddLog::addLog("update", $this->table, $request->id, $item->name, Auth::id());
                $item->name = $request->name;
                $item->save();       
                $data = [
                    'status' => true,
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Lỗi dữ liệu'
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            foreach($request->array as $id) {
                $role = Role::where('id', $id)->first();
                AddLog::addLog("delete", $this->table, $id, $role->name, Auth::id());
                $role->delete();
                UserRole::where('role_id', $role->id)->delete();
                RolePermission::where('id_role', $role->id)->delete();
            }
            $data = [
                'status' => true
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
    }

    public function getPermissions($id) {
        try {
            $items = RolePermission::where('id_role', $id)->select('id_permission')->get();
            $data = [
                'status'    =>  true,
                'items'     =>  $items
            ];
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    public function handleAuthorize(Request $request) {
        try {
            RolePermission::where('id_role', $request->role)->delete();
            foreach($request->rolePermissions as $permission) {
                $item = new RolePermission();
                $item->id_role = $request->role;
                $item->id_permission = $permission;
                $item->save();
            }
            $data = [
                'status'    =>  true
            ];
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }
}
