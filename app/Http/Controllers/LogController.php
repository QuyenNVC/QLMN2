<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhatKyHoatDong;

class LogController extends Controller
{
    private $table = "log";
    /**
     * get view phu_caps
     * Author: HaoDT
     */
    public function quanLy(Request $request) {
        return view('admin.log');        
    }

    /**
     * api get list hinh_thuc_cham_cong
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $phong_ban = NhatKyHoatDong::join('users', 'users.id', '=', 'log.id_user')->select('log.*', 'log.created_at', 'users.username')->get();

            $data = [
                'status' => true,
                'phu_cap' => $phong_ban
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    /**
     * api create 
     * Author: HaoDT
     */
    // public function createHinhThucChamCong(Request $request) {
    //     try {

    //         $hinhThucChamCongItem = new HinhThucChamCong();
    //         $hinhThucChamCongItem->name = $request->name;
    //         $hinhThucChamCongItem->ngay_cong = $request->ngay_cong;
    //         $hinhThucChamCongItem->ghi_chu = $request->ghi_chu;
    //         $hinhThucChamCongItem->save();
    //         $phongBanItem =  HinhThucChamCong::orderBy('id', 'desc')->first();
    //         AddLog::addLog("create", $this->table, $phongBanItem->id, $phongBanItem->name, '1');
    //         $data = [
    //             'status' => true,
    //             'phong_ban' => $hinhThucChamCongItem
    //         ];
    //         return json_encode($data);
        
    //     }
    //     catch (Exception $e) {
    //         $data = [
    //             'status' => false,
    //             'message' => 'Lỗi server'
    //         ];
    //         return json_encode($data);
    //     }
        
    // }

    /**
     * api delete log
     * Author: HaoDT
     */
    public function deleteLog(Request $request, $id) {
        try {
            $userCheck = NhatKyHoatDong::where('id', $id)->first();
            $data = [
                'status' => true
            ];
            $userCheck->delete();
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
     * api delete log all
     * Author: HaoDT
     */
    public function deleteLogAll(Request $request) {
        try {
            $userCheck = NhatKyHoatDong::whereRaw('1 = 1')->delete();
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
     * api update hinhthucchamcong
     * Author: HaoDT
     */
    // public function updateHinhThucChamCong(Request $request) {
    //     try {
    //         $hinhThucChamCongCheck = HinhThucChamCong::where('id', $request->id)->first();
    //         if ( $hinhThucChamCongCheck ) {
    //             AddLog::addLog("update", $this->table, $request->id, $hinhThucChamCongCheck->name, '1');
    //             $hinhThucChamCongCheck->name = $request->name;
    //             $hinhThucChamCongCheck->ngay_cong = $request->ngay_cong;
    //             $hinhThucChamCongCheck->ghi_chu = $request->ghi_chu;
    //             $hinhThucChamCongCheck->save();

    //             $data = [
    //                 'status' => true,
    //                 'users' => $hinhThucChamCongCheck
    //             ];
    //             return json_encode($data);
    //         } else {
    //             $data = [
    //                 'status' => false,
    //                 'message' => 'Lỗi dữ liệu'
    //             ];
    //             return json_encode($data);
    //         }
    //     }
    //     catch (Exception $e) {
    //         $data = [
    //             'status' => false,
    //             'message' => 'Lỗi server'
    //         ];
    //         return json_encode($data);
    //     }
        
    // }
}
