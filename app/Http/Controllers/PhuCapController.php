<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhuCap;
use App\Service\AddLog;
use App\Models\PhuCapNhanVien;

class PhuCapController extends Controller
{
    private $table = "phu_cap";
    /**
     * get view phu_caps
     * Author: HaoDT
     */
    public function quanLy(Request $request) {
        return view('admin.phu_cap');        
    }

    /**
     * api get list phu_caps
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $phong_ban = PhuCap::all();

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
    public function createPhuCap(Request $request) {
        try {

            $phuCapItem = new PhuCap();
            $phuCapItem->name = $request->name;
            $phuCapItem->phu_cap = $request->phu_cap;
            $phuCapItem->don_vi_tinh = $request->don_vi_tinh;
            $phuCapItem->ghi_chu = $request->ghi_chu;
            $phuCapItem->save();

            $phongBanItem =  PhuCap::orderBy('id', 'desc')->first();
            AddLog::addLog("create", $this->table, $phongBanItem->id, $phongBanItem->name, $request->session()->get('id'));

            $data = [
                'status' => true,
                'phong_ban' => $phuCapItem
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
    public function deletePhuCap(Request $request, $id) {
        try {
            $userCheck = PhuCap::where('id', $id)->first();
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
     * api create user
     * Author: HaoDT
     */
    public function updatePhuCap(Request $request) {
        try {
            $phuCapCheck = PhuCap::where('id', $request->id)->first();
            if ( $phuCapCheck ) {
                AddLog::addLog("update", $this->table, $request->id, $phuCapCheck->name, $request->session()->get('id'));
                $phuCapCheck->name = $request->name;
                $phuCapCheck->phu_cap = $request->phu_cap;
                $phuCapCheck->don_vi_tinh = $request->don_vi_tinh;
                $phuCapCheck->ghi_chu = $request->ghi_chu;
                $phuCapCheck->save();

                $data = [
                    'status' => true,
                    'users' => $phuCapCheck
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
     * Get phu cap nhan vien
     * Author: HaoDT
     */
    public function getPhuCapNhanVien() {
        return view('admin.phu_cap_nhan_vien');
    }

    /**
     * update phu cap nhan vien
     * Author: HaoDT
     */
    public function updatePhuCapNhanVien(Request $request) {
        try {
            $month = $request->thang;
            $year = $request->nam;
            $phuCap = $request->phuCap;


            $item = PhuCapNhanVien::whereRaw('ma_nv = ? and month = ? and year = ?', [$phuCap["ma_nv"], $month, $year])->first();
            if ($item) {
                $item->data = json_encode($phuCap);
                $item->save();
            } else {
                $phuCapMoi = new PhuCapNhanVien();
                $phuCapMoi->data = json_encode($phuCap);
                $phuCapMoi->month = $month;
                $phuCapMoi->year = $year;
                $phuCapMoi->ma_nv = $phuCap["ma_nv"];
                $phuCapMoi->save();
            }
            

            $data = [
                'status' => true
            ];
            return json_encode($data);
        } catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }
}
