<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HinhThucChamCong;
use App\Service\AddLog;

class HinhThucChamCongController extends Controller
{
    private $table = "hinh_thuc_cham_cong";
    /**
     * get view phu_caps
     * Author: HaoDT
     */
    public function quanLy(Request $request) {
        return view('admin.hinh_thuc_cham_cong');        
    }

    /**
     * api get list hinh_thuc_cham_cong
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $phong_ban = HinhThucChamCong::all();

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
    public function createHinhThucChamCong(Request $request) {
        try {

            $hinhThucChamCongItem = new HinhThucChamCong();
            $hinhThucChamCongItem->name = $request->name;
            $hinhThucChamCongItem->ngay_cong = $request->ngay_cong;
            $hinhThucChamCongItem->ghi_chu = $request->ghi_chu;
            $hinhThucChamCongItem->save();
            $phongBanItem =  HinhThucChamCong::orderBy('id', 'desc')->first();
            AddLog::addLog("create", $this->table, $phongBanItem->id, $phongBanItem->name, $request->session()->get('id'));
            $data = [
                'status' => true,
                'phong_ban' => $hinhThucChamCongItem
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
     * api create phu_cap
     * Author: HaoDT
     */
    public function deleteHinhThucChamCong(Request $request, $id) {
        try {
            $userCheck = HinhThucChamCong::where('id', $id)->first();
            AddLog::addLog("delete", $this->table, $id, $userCheck->name, $request->session()->get('id'));
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
     * api update hinhthucchamcong
     * Author: HaoDT
     */
    public function updateHinhThucChamCong(Request $request) {
        try {
            $hinhThucChamCongCheck = HinhThucChamCong::where('id', $request->id)->first();
            if ( $hinhThucChamCongCheck ) {
                AddLog::addLog("update", $this->table, $request->id, $hinhThucChamCongCheck->name, $request->session()->get('id'));
                $hinhThucChamCongCheck->name = $request->name;
                $hinhThucChamCongCheck->ngay_cong = $request->ngay_cong;
                $hinhThucChamCongCheck->ghi_chu = $request->ghi_chu;
                $hinhThucChamCongCheck->save();

                $data = [
                    'status' => true,
                    'users' => $hinhThucChamCongCheck
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
}
