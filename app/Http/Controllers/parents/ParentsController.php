<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use Exception;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App\Models\User;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // NHÓM PHỤ HUYNH
    public function formDangKy() {
        return view('parents.sign_up');
    }

    public function signup(Request $request) {
        try {

            $phone_number = trim($request->phone_number);
            $user = User::where('username', $phone_number)->get()->first();
            if($user) {
                $data = [
                    'status'    => false,
                    'message'   =>  'Số điện thoại này đã được đăng ký!'
                ];
            } else {
                $user = new User();
                $user->name = $request->fullname;
                $user->username = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->phone_number = $request->phone_number;
                $user->email = $request->email;
                $user->save();
                $customClaims = JWTFactory::customClaims([
                    'id' => $user->id,
                    'sub'   => env('API_ID'),
                    'iss'   => config('app.name'),
                    'iat'   => Carbon::now()->timestamp,
                    // 'exp'   => JWTFactory::getTTL(),
                    'nbf'   => Carbon::now()->timestamp,
                    'jti'   => uniqid(),
                ]);
                $payload = $customClaims->make();
                $token = JWTAuth::encode($payload);
                $url = env('APP_URL').'verify/'.$token;
                $message = [
                    'name' => $user->name,
                    'url' => $url,
                ];
                SendEmail::dispatch($message, $user);
                $data = [
                    'status'    =>  true
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

    public function verify($token) {
        try {
            $token = JWTAuth::parseToken();
            $id = $token->getPayload()->get('id');
            $user = User::find($id);
            if($user) {
                if($user->isActived) {
                    return view('errors.mail-verify')->with(['title'=>'Đường dẫn đã hết hạn', 'description' => 'Đường dẫn đã hết hạn']);
                } else {
                    $user->isActived = 1;
                    $user->email_verified_at = time();
                    $user->save();
                    return redirect('phu-huynh/dang-nhap');
                }
            } else {
                return view('errors.mail-verify')->with(['title'=>'Tài khoản không tồn tại', 'description' => 'Tài khoản không tồn tại']);
            }
        } catch(Exception $e) {
            return view('errors.mail-verify')->with(['title'=>'Mã lỗi: '.$e->getCode(), 'description' => $e->getMessage()]);
        }
    }

    public function formDangNhap() {
        return view('parents.login');
    }

    public function login(Request $request) {
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
                    'message'   => 'Đăng nhập thành công!'
                ];
                return redirect('phu-huynh/');
            } else {                
                $data = [
                    'status' => false,
                    'message'   => 'Tài khoản hoặc mật khẩu không chính xác!'
                ]; 
            }
        } else {
            $data = [
                'status' => false,
                'message'   =>  'Tài khoản không tồn tại hoặc chưa được xác thực'
            ];
        }
        return response()->json($data);
    }
}
