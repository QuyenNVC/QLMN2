<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TangGiamLuong;
use App\Service\AddLog;
use App\Models\TangGiamLuongNhanVien;

class TangGiamLuongController extends Controller
{
    private $table = "tang_giam_luong";
    /**
     * get view tang_giam_luong
     * Author: HaoDT
     */
    public function quanLy(Request $request) {
        return view('admin.tang_giam_luong');        
    }

    /**
     * api get list phu_caps
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $phong_ban = TangGiamLuong::all();

            $data = [
                'status' => true,
                'tang_giam_luong' => $phong_ban
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
     * api create phu_cap
     * Author: HaoDT
     */
    public function deleteTangGiamLuong(Request $request, $id) {
        try {
            $userCheck = TangGiamLuong::where('id', $id)->first();
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
     * api create 
     * Author: HaoDT
     */
    public function createTangGiamLuong(Request $request) {
        try {

            $phuCapItem = new TangGiamLuong();
            $phuCapItem->name = $request->name;
            $phuCapItem->tang_giam = $request->tang_giam;
            $phuCapItem->ghi_chu = $request->ghi_chu;
            $phuCapItem->save();

            $phongBanItem =  TangGiamLuong::orderBy('id', 'desc')->first();
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
     * api create user
     * Author: HaoDT
     */
    public function updateTangGiamLuong(Request $request) {
        try {
            $phuCapCheck = TangGiamLuong::where('id', $request->id)->first();
            if ( $phuCapCheck ) {
                AddLog::addLog("update", $this->table, $request->id, $phuCapCheck->name, $request->session()->get('id'));
                $phuCapCheck->name = $request->name;
                $phuCapCheck->tang_giam = $request->tang_giam;
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
    public function getTangGiamLuongNhanVien() {
        return view('admin.tang_giam_luong_nhan_vien');
    }

    /**
     * update phu cap nhan vien
     * Author: HaoDT
     */
    public function updateTangGiamLuongNhanVien(Request $request) {
        try {
            $month = $request->thang;
            $year = $request->nam;
            $phuCap = $request->phuCap;


            $item = TangGiamLuongNhanVien::whereRaw('ma_nv = ? and month = ? and year = ?', [$phuCap["ma_nv"], $month, $year])->first();
            if ($item) {
                $item->data = json_encode($phuCap);
                $item->save();
            } else {
                $phuCapMoi = new TangGiamLuongNhanVien();
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
