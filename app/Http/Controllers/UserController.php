<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Jobs\SendEmailForgetPassword;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Service\AddLog;
use App\Models\SchoolYear;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;
use App\Services\UserService;

use Illuminate\Support\Str;

class UserController extends Controller
{
    private $table = "users";
    /**
     * get view login
     * Author: HaoDT
     */
    public function quanLy() {
        return view('admin.user');
    }

    /**
     * get form login
     * Author: HaoDT
     */
    public function formLogin(Request $request) {
        Auth::logout();
        $request->session()->forget('user');
        $request->session()->forget('id');
        $request->session()->forget('idPB');
        return view('user.form-login');
    }

    /**
     * api post to check data login
     * Author: HaoDT
     */
    public function postLogin(Request $request) {
        $user = User::where('username', $request->username)->where('isActived', 1)->whereNull('isDeleted')->get()->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('user', $user->username);
                $request->session()->put('id', $user->id);
                if ($user->username == 'admin') {
                    $request->session()->put('idPB', "-1");
                } else {
                    $request->session()->put('idPB', $user->id_phong_ban);
                }
                $data = [
                    'status' => true,
                    'admin' => false
                ];

                if ($user->username == 'admin') {
                    $data = [
                        'status' => true,
                        'admin' => true
                    ];
                }
                Auth::login($user);
            } else {                
                $data = [
                    'status' => false,
                    'message'   => 'Sai mật khẩu'
                ]; 
            }
            $year = date("Y");
            $school_year = SchoolYear::where('name', (string)$year)->first();
            if(!$school_year) {
                $school_year = new SchoolYear();
                $school_year->name = $year;
                $year_after = $year+1;
                $school_year->period = (string)$year."-".(string)$year_after;
                $school_year->save();
            }
            $month = date("m");
            if($month > 6) {
                $year_locked = $year - 1;
            } else {
                $year_locked = $year - 2;
            }
            SchoolYear::where('name', "<=", $year_locked)
            ->update(['isLocked' => 1]);
            return json_encode($data);
        } else {
            $data = [
                'status' => false,
                'message'   =>  'Tài khoản không tồn tại hoặc chưa được xác thực'
            ];
            return json_encode($data);
        }
    }

    
    /**
     * api get list users
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $items = UserService::getList($request);

            $data = [
                'status' => true,
                'items' => $items,
                'id_coso'    =>  Auth::user()->id_coso
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                "message"   =>  $e->getMessage()
            ];
            return json_encode($data);
        }
        
    }
    // public function getList(Request $request) {
    //     try {
    //         $user = User::join('phong_ban', 'phong_ban.id', '=', 'users.id_phong_ban')->select(['users.*', 'phong_ban.name as phong_ban_name'])->get()->toArray();

    //         $data = [
    //             'status' => true,
    //             'users' => $user
    //         ];
    //         return json_encode($data);
    //     }
    //     catch (Exception $e) {
    //         $data = [
    //             'status' => false
    //         ];
    //         return json_encode($data);
    //     }
        
    // }

    /**
     * api create user
     * Author: HaoDT
     */
    public function createUser(Request $request) {
        try {
            $userCheck = User::where('username', $request->username)->whereNull('isDeleted')->first();
            if ( !$userCheck ) {
                $user = new User();
                $user->name = $request->name;
                $user->username = $request->username;
                $user->phone_number = $request->phone_number;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->isActived = 1;
                $user->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                // $user->id_phong_ban = $request->room;
                if(Auth::user()->cannot('save', $user)) {
                    abort(403);
                }
                $user->save();
                foreach($request->roles as $role) {
                    $item = new UserRole();
                    $item->user_id = $user->id;
                    $item->role_id = $role;
                    $item->save();
                }
                // $phongBanItem =  User::orderBy('id', 'desc')->first();
                // AddLog::addLog("create", $this->table, $phongBanItem->id, $phongBanItem->name, $request->session()->get('id'));
                $data = [
                    'status' => true,
                    // 'users' => $user
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Tên người dùng đã tồn tại'
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
     * api create user
     * Author: HaoDT
     */
    public function deleteUser(Request $request) {
        try {
            foreach($request->array as $id) {
                $userCheck = User::where('id', $id)->first();
                AddLog::addLog("delete", $this->table, $id, $userCheck->username, Auth::id());
                $userCheck->isDeleted = 1;
                $userCheck->save();
                UserRole::where('user_id', $userCheck->id)->delete();
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

    /**
     * api create user
     * Author: HaoDT
     */
    public function updateUser(Request $request) {
        // try {
            $user = User::where('username', $request->username)->first();
            // if not exist => create
            // else dont create
            if ( $user ) {
                $user->name = $request->name;
                // $user->username = $request->username;
                $user->phone_number = $request->phone_number;
                $user->email = $request->email;
                if ($request->password != '') {
                    $user->password = Hash::make($request->password);
                }
                $user->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                $user->save();
                UserRole::where('user_id', $user->id)->delete();
                foreach($request->roles as $role) {
                    $item = new UserRole();
                    $item->user_id = $user->id;
                    $item->role_id = $role;
                    $item->save();
                }
                // $phongBanItem =  User::orderBy('id', 'desc')->first();
                // AddLog::addLog("create", $this->table, $phongBanItem->id, $phongBanItem->name, $request->session()->get('id'));
                $data = [
                    'status' => true,
                    // 'users' => $user
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Tên người dùng đã tồn tại'
                ];
                return json_encode($data);
            }
            // if ( $userCheck ) {
            //     AddLog::addLog("update", $this->table, $userCheck->id, $userCheck->user_namename, $request->session()->get('id'));
            //     $userCheck->name = $request->name;
            //     $userCheck->username = $request->username;
            //     if ($request->password != '') {
            //         $userCheck->password = Hash::make($request->password);
            //     }
            //     $userCheck->id_phong_ban = $request->room;
            //     $userCheck->save();

            //     $data = [
            //         'status' => true,
            //         'users' => $userCheck
            //     ];
            //     return json_encode($data);
            // } else {
            //     $data = [
            //         'status' => false,
            //         'message' => 'Tên người không tồn tại'
            //     ];
            //     return json_encode($data);
            // }
        // }
        // catch (Exception $e) {
        //     $data = [
        //         'status' => false,
        //         'message' => 'Lỗi server'
        //     ];
        //     return json_encode($data);
        // }
        
    }

    /**
     * api create user
     * Author: HaoDT
     */
    public function getDoiMatKhau(Request $request) {
        return view('admin.doi-mat-khau');
        
    }


    /**
     * api create user
     * Author: HaoDT
     */
    public function postDoiMatKhau(Request $request) {
        $user = User::whereRaw('id = ?', $request->session()->get('id'))->first();
        
        if ($user) {
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                $data = [
                    'status' => true,
                    'message' => 'Đổi mật khẩu thành công.'
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Mật khẩu cũ không khớp. Vui lòng thử lại.'
                ];
                return json_encode($data);
            }
        } else {
            $data = [
                'status' => false,
                'message' => 'Lỗi server!'
            ];
            return json_encode($data);
        }
    }

    public function activeAccount(Request $request) {
        try {
            foreach($request->array as $id) {
                $user = User::find($id);
                if($user) {
                    $user->isActived = 1;
                    $user->save();
                }
            }
            $data = [
                'status'    =>  true
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

    public function disableAccount(Request $request) {
        try {
            $user = User::find($request->id);
            if($user) {
                $user->isActived = null;
                $user->save();
                $data = [
                    'status'    =>  true
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Tài khoản không tồn tại'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return response()->json($data);
    }

    public function insertRolePermission(Request $request) {
        $permissions = Permission::whereNull('forAll')->get();
        foreach($permissions as $permission) {
            $role_permission = new RolePermission();
            $role_permission->id_role = 1;
            $role_permission->id_permission = $permission->id;
            $role_permission->save();
        }
    }

    public function forgetPassword() {
        return view('user.forget-password');
    }

    public function submitForgetPassword(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);
            $user = User::where('name', $request->fullname)->where('username', $request->username)->where('email', $request->email)->get()->first();
            if($user) {
                // $user->isActived = null;
                // $user->save();
                $token = Str::random(100);
                $user->remember_token = $token;
                $user->save();
                $data = [
                    'url'   =>  env('APP_URL').'reset-password/'.$token,
                    'name'  =>  $user->name
                ];
                SendEmailForgetPassword::dispatch($data, $user->email);

                $data = [
                    'status'    =>  true
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Tài khoản không tồn tại'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    public function resetPasswordForm($token) {
        return view('user.reset-password')->with('token', $token);
    }

    public function submitResetPassword(Request $request) {
        try {
            $item = User::where('remember_token', $request->token)->where('isActived', 1)->whereNull('isDeleted')->get()->first();
            if($item) {
                $item->password = Hash::make($request->password);
                $item->remember_token = null;
                $item->save();
                $data = [
                    'status'    =>  true
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Token không hợp lệ'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }
}
